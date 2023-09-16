<?php
  require("pdo.php");
  $pdo = new mypdo();
  //未登入
  if(!isset($_COOKIE['user_ID'])){
    echo '<script>alert("Please login");location.href="login.php"</script>';
  }
  //電影資訊
  $movie_ID=isset($_GET['movie_ID'])? htmlspecialchars($_GET['movie_ID']):'';
  $sql="SELECT `電影_海報`,`電影_ID`,`電影_中文名稱`,`電影_英文名稱` FROM `電影資訊` WHERE `電影_ID`='$movie_ID';";
  $rows = $pdo->bindQuery($sql);
  foreach($rows as $row){
    foreach($row as $key => $value){
      if($key=="電影_中文名稱"){
        $movie_name=$value;
      }
      else if($key=="電影_英文名稱"){
        $movie_Ename=$value;
      }
      else if($key=="電影_ID"){
        $movie_ID=$value;
      }
      else if($key=="電影_海報"){
        $movie_src=$value;
      }
    }
  }
  //theater location
  $theater_location=$_SERVER['PHP_SELF'].'?movie_ID='.$movie_ID.'&mytheater_ID=';
  //有放映該movie的 影廳
  $theater_sql="SELECT DISTINCT `影城-影廳`.`影城_ID`,`影城_名稱`
  FROM ((`放映資訊` LEFT JOIN `影城-影廳`ON `放映資訊`.`影城_影廳_ID`= `影城-影廳`.`影城_影廳_ID`)
  LEFT JOIN `場次` ON `場次`.`放映_ID`= `放映資訊`.`放映_ID`)
  LEFT JOIN `影城` ON `影城`.`影城_ID`=`影城-影廳`.`影城_ID` WHERE `電影_ID` = '$movie_ID';";
  $theater_rows= $pdo->bindQuery($theater_sql);
  $theater_table="";
  if(!isset($_GET["mytheater_ID"])){
    $theater_table.="<option value='0'>請選擇影廳</option>";
  }
  foreach($theater_rows as $row){
    foreach($row as $key => $value){
      if($key=="影城_ID"){
        if(isset($_GET["mytheater_ID"])){
          $mytheater_ID=isset($_GET['mytheater_ID'])? htmlspecialchars($_GET['mytheater_ID']):'';
          if($mytheater_ID==$value){
            $theater_table.='<option selected value='.$value.'>';
          }
          else{
            $theater_table.='<option value='.$value.'>';
          }
        }
        else{
          $theater_table.='<option value='.$value.'>';
        }
      }
      else if($key=="影城_名稱"){
        $theater_table.=$value.'</option>';
      }

    }
  }
  
  //票種
  $ticket_sql="SELECT `票種_名稱`,`票種_介紹`,`票種_價格`,`票種_ID` FROM `票種`;";
  $ticket_rows= $pdo->bindQuery($ticket_sql);
  $ticket_table="";
  foreach($ticket_rows as $row){
    foreach($row as $key => $value){
      if($key=="票種_名稱"){
        $ticket_table.='<div style="margin: 15px;" class="col-3">
        <div class="mycard_pos">
          <div style="height:270px" class="card">
              <div class="card-header">
                <h4>'.$value.'</h4>
              </div>';
      }
      else if($key=="票種_介紹"){
        $ticket_table.='<div class="card-body style="position:relative;top: 50px">
        <blockquote class="blockquote mb-0">
          <h6>'.$value.'</h6></blockquote>';
      }
      else if($key=="票種_價格"){
        $ticket_table.='<h5>價錢:'.$value.'/張</h5>';
      }
      else if($key=="票種_ID"){
        $ticket_table.='<div class="input-group mb-3" style="width:180px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">數量</span>
                      </div>
                      <input name="'.$value.'" type="number" min="0" max="5" value="0" class="form-control"  aria-describedby="basic-addon1">
                    </div>
                </div>
              </div>
              </div>
              </div>';
      }
    }
  } 
  //付款方式
  $pay_sql="SELECT * FROM `付款方式`";
  $pay_rows= $pdo->bindQuery($pay_sql);
  $pay_table="";
  foreach($pay_rows as $row){
    foreach($row as $key => $value){
      if($key=="付款方式_ID"){
        $pay_table.='<option value='.$value.'>';
      }
      else if($key=="付款方式_名稱"){
        $pay_table.=$value.'</option>';
      }
    }
  }
  //電影日期
  $movie_date_table="";
  if(isset($_GET["mytheater_ID"])){
    $mytheater_ID=isset($_GET['mytheater_ID'])? htmlspecialchars($_GET['mytheater_ID']):'';
    //date location
    $date_location=$_SERVER['PHP_SELF'].'?movie_ID='.$movie_ID.'&mytheater_ID='.$mytheater_ID.'&mydate=';
    $movie_date_sql="SELECT DISTINCT `場次_日期`
      FROM ((`放映資訊` LEFT JOIN `影城-影廳`ON `放映資訊`.`影城_影廳_ID`= `影城-影廳`.`影城_影廳_ID`)
      LEFT JOIN `場次` ON `場次`.`放映_ID`= `放映資訊`.`放映_ID`)
      LEFT JOIN `影城` ON `影城`.`影城_ID`=`影城-影廳`.`影城_ID` WHERE `電影_ID` = '$movie_ID' AND `影城-影廳`.`影城_ID`= '$mytheater_ID'  AND `場次_日期`> CURDATE();";
    $date_rows= $pdo->bindQuery($movie_date_sql);
    if(!isset($_GET["mydate"])){
      $movie_date_table.="<option>請選擇日期</option>";
    }
    foreach($date_rows as $row){
      foreach($row as $key => $value){
        if($key=="場次_日期"){
          if(isset($_GET['mydate'])){
            $mydate=isset($_GET['mydate'])? htmlspecialchars($_GET['mydate']):'';
            if($mydate==$value){
              $movie_date_table.='<option selected value='.$value.'>'.$value.'</option>';
            }
            else{
              $movie_date_table.='<option value='.$value.'>'.$value.'</option>';
            }

          }
          else{
            $movie_date_table.='<option value='.$value.'>'.$value.'</option>';
          }
        }
      }
    }
  }
  else{
    $movie_date_table.="<option value='0'>請先選擇影城</option>";
  }
  //電影時間
  $movie_time_table="";
  if(isset($_GET["mydate"])){
    $mydate=isset($_GET['mydate'])? htmlspecialchars($_GET['mydate']):'';
    //time location
    $time_location=$_SERVER['PHP_SELF'].'?movie_ID='.$movie_ID.'&mytheater_ID='.$mytheater_ID.'&mydate='.$mydate.'&mytime=';
    $movie_time_sql="SELECT DISTINCT `場次_時間`
      FROM ((`放映資訊` LEFT JOIN `影城-影廳`ON `放映資訊`.`影城_影廳_ID`= `影城-影廳`.`影城_影廳_ID`)
      LEFT JOIN `場次` ON `場次`.`放映_ID`= `放映資訊`.`放映_ID`)
      LEFT JOIN `影城` ON `影城`.`影城_ID`=`影城-影廳`.`影城_ID` 
      WHERE `電影_ID` = '$movie_ID' AND `影城-影廳`.`影城_ID`= '$mytheater_ID'  AND `場次_日期`> CURDATE()  AND  `場次_日期`= '$mydate' ORDER BY `場次`.`場次_時間` ASC;";
    $time_rows= $pdo->bindQuery($movie_time_sql);
    if(!isset($_GET["mytime"])){
      $movie_time_table.="<option>請選擇時間</option>";
    }
    foreach($time_rows as $row){
      foreach($row as $key => $value){
        if($key=="場次_時間"){
          if(isset($_GET['mytime'])){
            $mytime=isset($_GET['mytime'])? htmlspecialchars($_GET['mytime']):'';
            if($mytime==$value){
              $movie_time_table.='<option selected value='.$value.'>'.substr($value,0,5).'</option>';
            }
            else{
              $movie_time_table.='<option value='.$value.'>'.substr($value,0,5).'</option>';
            }

          }
          else{
            $movie_time_table.='<option value='.$value.'>'.substr($value,0,5).'</option>';
          }
        }
      }
    }

  }
  else{
    $movie_time_table.="<option value='0'>請先選擇日期</option>";
  }

  //電影版本
  $movie_version_table="";
  if(isset($_GET["mytime"])){
    $mytime=isset($_GET['mytime'])? htmlspecialchars($_GET['mytime']):'';
    //time location
    $version_location=$_SERVER['PHP_SELF'].'?movie_ID='.$movie_ID.'&mytheater_ID='.$mytheater_ID.'&mydate='.$mydate.'&mytime='.$mytime.'&version_ID=';
    $movie_version_sql="SELECT DISTINCT `放映資訊`.`版本_ID`,`版本_名稱`
      FROM (((`放映資訊` LEFT JOIN `影城-影廳`ON `放映資訊`.`影城_影廳_ID`= `影城-影廳`.`影城_影廳_ID`)
      LEFT JOIN `場次` ON `場次`.`放映_ID`= `放映資訊`.`放映_ID`)
      LEFT JOIN `影城` ON `影城`.`影城_ID`=`影城-影廳`.`影城_ID` )
      LEFT JOIN `版本` ON  `版本`.`版本_ID`= `放映資訊`.`版本_ID`
      WHERE `電影_ID` = '$movie_ID' AND `影城-影廳`.`影城_ID`= '$mytheater_ID'  AND `場次_日期`> CURDATE()  
      AND  `場次_日期`= '$mydate' AND  `場次_時間`='$mytime' ;";
    if(!isset($_GET["version_ID"])){
      $movie_version_table.="<option value='0'>請選擇版本</option>";
    }
    $version_rows= $pdo->bindQuery($movie_version_sql);
    foreach($version_rows as $row){
      foreach($row as $key => $value){
        if($key=="版本_ID"){
          if(isset($_GET["version_ID"])){
            $version_ID=isset($_GET['version_ID'])? htmlspecialchars($_GET['version_ID']):'';
            if($version_ID==$value){
              $movie_version_table.='<option selected value='.$value.'>';
            }
            else{
              $movie_version_table.='<option value='.$value.'>';
            }
          }
          else{
            $movie_version_table.='<option value='.$value.'>';
          }
        }
        else if($key=="版本_名稱"){
          $movie_version_table.=$value.'</option>';
        }
      }
    }

  }
  else{
    $movie_version_table.="<option value='0'>請先選擇場次</option>";
  }
  $alert_info="";
  $ticket_info="";
  //按下送出
  if(isset($_GET['beclicked'])=="true"){
    $version_ID=isset($_GET['version_ID'])? htmlspecialchars($_GET['version_ID']):'';
    $mytime=isset($_GET['mytime'])? htmlspecialchars($_GET['mytime']):'';
    $mydate=isset($_GET['mydate'])? htmlspecialchars($_GET['mydate']):'';
    $mytheater_ID=isset($_GET['mytheater_ID'])? htmlspecialchars($_GET['mytheater_ID']):'';
    $pay_ID=isset($_GET['pay_ID'])? htmlspecialchars($_GET['pay_ID']):'';
    $adult_ID=isset($_GET['TK0001'])? htmlspecialchars($_GET['TK0001']):'';
    $older_ID=isset($_GET['TK0003'])? htmlspecialchars($_GET['TK0003']):'';
    $bonus_ID=isset($_GET['TK0002'])? htmlspecialchars($_GET['TK0002']):'';
    // print($bonus_ID+$older_ID+$adult_ID);
    if($mytheater_ID=="0"||$mytime=="0"||$mydate=="0"||$version_ID=="0"||$mytheater_ID==''||$mytime==''||$mydate==''||$version_ID==''||$bonus_ID+$older_ID+$adult_ID==0){
      if($bonus_ID+$older_ID+$adult_ID==0){
        $ticket_info.="ticket info not be completed";
      }
      if($mytheater_ID=="0"||$mytime=="0"||$mydate=="0"||$version_ID=="0"||$mytheater_ID==''||$mytime==''||$mydate==''||$version_ID==''){
        $alert_info.="movie info not be completed";
      }
    }
    else{
      //取出場次ID
      $section_sql="SELECT `場次_ID`
      FROM (((`放映資訊` LEFT JOIN `影城-影廳`ON `放映資訊`.`影城_影廳_ID`= `影城-影廳`.`影城_影廳_ID`)
      LEFT JOIN `場次` ON `場次`.`放映_ID`= `放映資訊`.`放映_ID`)
      LEFT JOIN `影城` ON `影城`.`影城_ID`=`影城-影廳`.`影城_ID` )
      LEFT JOIN `版本` ON  `版本`.`版本_ID`= `放映資訊`.`版本_ID`
      WHERE `電影_ID` = '$movie_ID' AND `影城-影廳`.`影城_ID`= '$mytheater_ID'  AND `場次_日期`= '$mydate' AND `場次_時間`='$mytime' AND `放映資訊`.`版本_ID`='$version_ID';";
      $result_rows= $pdo->bindQuery($section_sql);
      foreach($result_rows as $row){
        foreach($row as $key => $value){
          if($key=='場次_ID'){
            $section_ID=$value;
            // print($section_ID);
          }
        }
      }
      //確認訂票數量是否超過
      //到票種_座位 找出該場次的定位數量
      $check_seat_number_sql="SELECT COUNT(*) FROM `訂票紀錄` LEFT JOIN `票種-座位` ON `訂票紀錄`.`訂票_ID`=`票種-座位`.`訂票_ID` WHERE `場次_ID`='$section_ID';";
      $seat_result= $pdo->bindQuery($check_seat_number_sql);
      foreach($seat_result as $row){
        foreach($row as $key => $value){
          $seat_booked_number=$value;//已被訂購數量
          $available_seat_num=63-$value;//可訂購數量
        }
      }
      //使用者訂購數量超過 座位不足
      if($bonus_ID+$older_ID+$adult_ID>$available_seat_num){
        $ticket_info.="seat not enough only remainder ".$available_seat_num."please choose another date";
      }
    }
    //電影訂票資訊完整 可以跳轉
    //傳送 場次ID 電影ID 訂票數量 付款ID 給 foood_seat.php
    if($ticket_info==""&&$ticket_info==""&&$alert_info==""){
        echo '<script>location.href="food_seat.php?movie_ID='.$movie_ID.'&section_ID='.$section_ID.'&pay_ID='.$pay_ID.'&adult='.$adult_ID.'&older='.$older_ID.'&bonus='.$bonus_ID.'"</script>';
    }
    
    // food_saet.php?movie_ID=.'.$movie_ID.'&section_ID='.$section_ID.'&pay_ID='.$pay_ID.'&audult='.$adult_ID.'&older='.$older_ID.'&bonus='.$bonus_ID.'</script>'
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
          <link href="order_ticket.css" rel="stylesheet"/>
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
                    <li class="breadcrumb-item active" aria-current="page">訂票</li>
                </ol>
            </nav>
        </div>
        <form method="GET" action="order_ticket.php?" >
          <div id="myjumbotron" class="jumbotron jumbotron-fluid">
              <div  class="container">
                  <div class="row">
                      <div  class="col-4 offset-md-1">
                          <img  id="img_pos" src="<?php echo $movie_src?>" class="card-img-top" alt="pic">
                      </div>
                      <div id="content" class="col-5 offset-md-1 ">
                          <h3 style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $movie_name?></h3>
                          <h3 class="h3" style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;"><?php echo $movie_Ename?></h3>
                          <hr/>
                          <input type="hidden" name="movie_ID" value='<?php echo $movie_ID?>'/>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">選擇影城</label>
                              </div>
                              <select name="mytheater_ID" class="custom-select" id="inputGroupSelect01" onchange="window.location='<?php echo $theater_location?>'+this.value"> 
                                  <?php echo $theater_table?>
                              </select>
                          </div>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">選擇日期</label>
                              </div>
                              <select name="mydate" class="custom-select" id="inputGroupSelect01" onchange="window.location='<?php echo $date_location?>'+this.value" >
                                <?php echo $movie_date_table?>
                              </select>
                          </div>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">選擇場次</label>
                              </div>
                              <select name="mytime" class="custom-select" id="inputGroupSelect01" onchange="window.location='<?php echo $time_location?>'+this.value" >
                                <?php echo $movie_time_table?>
                              </select>
                          </div>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">選擇版本</label>
                              </div>
                              <select name='version_ID' class="custom-select" id="inputGroupSelect01" onchange="window.location='<?php echo $version_location?>'+this.value" >
                              <?php echo $movie_version_table?>
                              </select>
                          </div>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">選擇付款方式</label>
                              </div>
                              <select name="pay_ID" class="custom-select" id="inputGroupSelect01" >
                              <?php 
                                echo $pay_table
                              ?>
                              </select>
                          </div>
                          <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $alert_info ?></h6>
                      </div>
                  </div>
              </div>
          </div>
          <h4 style="position:relative;left:150px;width: 200px;">選擇票種/數量</h4>
          <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $ticket_info ?></h6>
          <div style="position: relative;left:50px;" class="container">
            <div class="row">
              <?php 
                echo $ticket_table
              ?>
            </div>
            <button style="position: relative;left:950px; bottom: 36px;height: 40px;width: 100px;" name="beclicked"  value="true" id="mybutton" type="submit" class="btn btn-secondary">Next</button>
          </div>
        </form>
    </body>
</html>