
<?php
  require("pdo.php");
  $pdo = new mypdo();
  $movie_ID=isset($_GET['movie_ID'])? htmlspecialchars($_GET['movie_ID']):'';
  $section_ID=isset($_GET['section_ID'])? htmlspecialchars($_GET['section_ID']):'';
  $pay_ID=isset($_GET['pay_ID'])? htmlspecialchars($_GET['pay_ID']):'';
  $adult=isset($_GET['adult'])? htmlspecialchars($_GET['adult']):'';
  $older=isset($_GET['older'])? htmlspecialchars($_GET['older']):'';
  $bonus=isset($_GET['bonus'])? htmlspecialchars($_GET['bonus']):'';
  $href="food_seat.php?movie_ID=".$movie_ID."&section_ID=".$section_ID."&pay_ID=".$pay_ID."&adult=".$adult."&older=".$older."&bonus=".$bonus;
  //電影資訊
  $sql="SELECT `電影_ID`,`電影_中文名稱` FROM `電影資訊` WHERE `電影_ID`='$movie_ID';";
  $rows = $pdo->bindQuery($sql);
  foreach($rows as $row){
    foreach($row as $key => $value){
      if($key=="電影_中文名稱"){
        $movie_name=$value;
      }
      else if($key=="電影_ID"){
        $movie_ID=$value;
      }
    }
  }
  //套餐
  $food_sql="SELECT `套餐_圖片`,`套餐_名稱`,`套餐_介紹`,`套餐_ID` FROM `套餐`;";
  $food_rows = $pdo->bindQuery($food_sql);
  $food_table="";
  foreach($food_rows as $row){
    foreach($row as $key => $value){
      if($key=="套餐_圖片"){
        $food_table.='<div  class="col-5">
        <div class="mycard_pos">
          <div class="card">
              <div class="food_card" style="width: 27.7em;height:450px">
                  <img src="'.$value.'" class="card-img-top" alt="...">';
      }
      else if($key=="套餐_名稱"){
        $food_table.='<div class="card-body">
        <h5 class="card-title">'.$value.'</h5>';
      }
      else if($key=="套餐_介紹"){
        $food_table.='<p class="card-text">'.$value.'</p>';
      }
      else if($key=="套餐_ID"){
        $food_table.='<div class="input-group mb-3" style="top:25px;width: 200px;position:relative;left: 50%;">
        <div class="input-group-prepend" ">
            <span class="input-group-text" id="basic-addon1">數量</span>
          </div>
          <input name="'.$value.'" type="number" min="0" max="5" value="0" class="form-control" aria-describedby="basic-addon1">
        </div>
          </div>
          </div>
          </div>
          </div>
        </div>';
      }
    }
  }
  //座位
  $seat_sql="SELECT `訂票_座位` FROM `訂票紀錄` LEFT JOIN `票種-座位` ON `訂票紀錄`.`訂票_ID`=`票種-座位`.`訂票_ID` WHERE `場次_ID`='$section_ID' ORDER BY `票種-座位`.`訂票_座位` ASC;";
  $seat_rows = $pdo->bindQuery($seat_sql);
  $seat_array = array();
  $seat_name=array("A","B","C","D","E","F","G","H","I");
  foreach($seat_rows as $row){
    foreach($row as $key => $value){
      array_push($seat_array,$value);
    }
  }
  $seat_table="";
  for( $i=1 ; $i<8 ; $i++ ){
    $seat_table.='<tr>
    <th scope="row">'.$i.'</th>';
    for($j=0 ; $j<9 ; $j++ ){
      $temp=$seat_name[$j].strval($i);
      if(in_array($temp, $seat_array)){
        $seat_table.='<td><input disabled  name="seat[]" value="'.$temp.'" type="checkbox" >'.$temp.'</td>';
      }
      else{
        $seat_table.='<td><input  name="seat[]" value="'.$temp.'" type="checkbox" >'.$temp.'</td>';
      }
    }
    $seat_table.='</tr>';
  }
  //下單處理
  $seat_info="";
  if(isset($_POST['buy'])=="true"){
    $seat=isset($_POST ["seat"])?$_POST ["seat"]:'';
    // print_r($seat[0]);
    if($seat==''){
      $seat_info="please choose seat";
    }
    else{
      if(count($seat)!=($adult+$older+$bonus)){
        $seat_info.="The number of your booked ticket is ".($adult+$older+$bonus).'. Please try again';
      }
    }
    if($seat_info==""){
      //訂票紀錄 取出最大值
      $max_sql="SELECT MAX(訂票_ID) from `訂票紀錄` ;";
      $MAX=$pdo->bindQuery($max_sql);
      foreach($MAX as $row){
          foreach($row as $key => $value){
          $Max_number=$value;
          //過濾出數值
          $Max_number = (int) filter_var($Max_number, FILTER_SANITIZE_NUMBER_INT);
        }
      }
      //訂票紀錄 sql
      $ticket_Max_number="T".str_pad($Max_number+1,4,'0',STR_PAD_LEFT);
      $user_ID=$_COOKIE['user_ID'];
      $data=date("Y-m-d H:i:s");
      $ticket_sql="INSERT INTO `訂票紀錄` (`訂票_ID`, `會員_ID`, `場次_ID`, `付款方式_ID`, `訂票_日期`) VALUES ('$ticket_Max_number', '$user_ID', '$section_ID', '$pay_ID', '$data');";
      $pdo->bindQuery($ticket_sql);
      // print($ticket_sql);

      //票種座位 取出最大值
      for($g=0;$g<count($seat);$g++){
        $myseat=$seat[$g];
        $max_sql="SELECT MAX(票種_座位_ID) from `票種-座位` ;";
        $MAX=$pdo->bindQuery($max_sql);
        foreach($MAX as $row){
            foreach($row as $key => $value){
            $Max_number=$value;
            //過濾出數值
            $Max_number = (int) filter_var($Max_number, FILTER_SANITIZE_NUMBER_INT);
          }
        }
        $seat_Max_number="TS".str_pad($Max_number+1,4,'0',STR_PAD_LEFT);
        if($adult>0){
          $seat_sql="INSERT INTO `票種-座位` (`票種_座位_ID`, `訂票_ID`, `票種_ID`, `訂票_座位`) VALUES ('$seat_Max_number', '$ticket_Max_number', 'TK0001', '$myseat');";
          $adult-=1;
        }
        else if($older>0){
          $seat_sql="INSERT INTO `票種-座位` (`票種_座位_ID`, `訂票_ID`, `票種_ID`, `訂票_座位`) VALUES ('$seat_Max_number', '$ticket_Max_number', 'TK0003', '$myseat');";
          $older-=1;
        }
        else if($bonus>0){
          $seat_sql="INSERT INTO `票種-座位` (`票種_座位_ID`, `訂票_ID`, `票種_ID`, `訂票_座位`) VALUES ('$seat_Max_number', '$ticket_Max_number', 'TK0002', '$myseat');";
          $bonus-=1;
        }
        // print($seat_sql);
        $pdo->bindQuery($seat_sql);
      }
      //訂餐 不用取最大值 因為訂票_ID和套餐_ID是主鍵
      $food_ID_sql="SELECT `套餐_ID` FROM `套餐`;";
      $food_rows = $pdo->bindQuery($food_ID_sql);
      $food_ID_array = array();
      foreach($food_rows  as $row){
        foreach($row as $key => $value){
          array_push($food_ID_array,$value);
        }
      }
      // print_r($food_ID_array);
      for($i=0;$i<count($food_ID_array);$i++){
        if(isset($_POST[$food_ID_array[$i]])){
          $food_number=$_POST[$food_ID_array[$i]];
          if($food_number>0){
            $food_sql="INSERT INTO `訂餐紀錄` (`訂票_ID`, `套餐_ID`, `套餐_數量`) VALUES ('$ticket_Max_number', '$food_ID_array[$i]', '$food_number');";
            $pdo->bindQuery($food_sql);
          }
        }
      }
      //跳轉為至尚未決定
      echo '<script>alert("booking successful");location.href="ticket_info.php"</script>';

    }

  }
 
  
?>
<!DOCTYPE html>
<html>
    <head>
        <title>order</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="./food_seat.css" rel="stylesheet"/>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a id="nav_logo" class="navbar-brand" href="./home.php">MOVIE</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div id="nav_font">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            電影介紹
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="movies.php?onsale=1">熱售中</a>
                                
                                <a class="dropdown-item" href="movies.php?onsale=0">即將上映</a>
                            </div>
                        </li>
                        <a class="nav-item nav-link" href="theater.php">影城介紹</a>
                        <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            會員專區
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="forget_password.php">忘記密碼</a>
                                <a class="dropdown-item" href="membership_info.php">會員資料</a>
                                <a class="dropdown-item" href="ticket_info.php">訂票紀錄</a>
                            </div>
                        </li>
                        <a style="position:relative ;left:650px" class="nav-item nav-link" href="login.php">會員註冊</a>
                    </div>
                </div>
            </div>
        </nav>
        <div id="mybread">  
            <nav  aria-label="breadcrumb">      
                <ol class="breadcrumb" style="background-color: white;">
                    <li class="breadcrumb-item"><a href="./home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="./movies.php?onsale=1">熱售中</a></li>
                    <li class="breadcrumb-item"><a href="./movie_info.php?onsale=1&movie_ID=<?php echo $movie_ID?>"><?php echo $movie_name?></a></li>
                    <li class="breadcrumb-item"><a href="./order_ticket.php?movie_ID=<?php echo $movie_ID?>">訂票</a></li>
                    <li class="breadcrumb-item active" aria-current="page">餐點_座位</li>
                </ol>
            </nav>
        </div>
        <form method="POST" action=<?php echo $href?>>
          <h4 style="letter-spacing:3px;text-align:center;">Screen</h4>
          <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div class="container">
              <div class="row">
                <div  class="col-10 offset-md-1">
                <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $seat_info ?></h6>
                  <table class="table">
                    <div class="table-responsive-sm">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">座位</th>
                          <th scope="col">A</th>
                          <th scope="col">B</th>
                          <th scope="col">C</th>
                          <th scope="col">D</th>
                          <th scope="col">E</th>
                          <th scope="col">F</th>
                          <th scope="col">G</th>
                          <th scope="col">H</th>
                          <th scope="col">I</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          echo $seat_table; 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>      
          </div>

          <h4 style="position:relative;left:150px;width: 200px;">選擇餐點</h4>
          <div  class="container">
            <div class="row">
              <?php
                echo $food_table
              ?>
            </div>
          </div>
          <button name="buy"  value="true" id="mybutton" type="submit" class="btn btn-secondary">下單</button>
        </form>
    </body>
</html>