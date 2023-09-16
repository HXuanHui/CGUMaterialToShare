<?php
    require("pdo.php");
    $pdo = new mypdo();
    $aler_info="";
    $Name=isset($_POST['Name'])? htmlspecialchars($_POST['Name']):'';
    $Email=isset($_POST['Email'])? htmlspecialchars($_POST['Email']):'';
    $Password=isset($_POST['Password'])? htmlspecialchars($_POST['Password']):'';
    $Phone=isset($_POST['Phone'])? htmlspecialchars($_POST['Phone']):'';
    $Birthday=isset($_POST['Birthday'])? htmlspecialchars($_POST['Birthday']):'';
    //偵錯訊息
    if(isset($_POST['Name'])){
        if($Name=="") $aler_info.="Name";
    }
    if(isset($_POST['Email'])){
        if($Email=="") $aler_info.=" Email";
        //檢查email是否存在
        $Exit="SELECT COUNT(*) FROM `會員` WHERE `會員_信箱`= '$Email';";
        $return=$pdo->bindQuery($Exit);
        foreach($return as $row){
            foreach($row as $key => $value){
                $num=$value;
            }
        }
        if($num>0){
            $aler_info.=" Email has be registered";
        }


    }
    if(isset($_POST['Password'])){
        if($Password=="") $aler_info.=" Password";
    }
    if(isset($_POST['Phone'])){
        if($Phone=="") $aler_info.=" Phone";
    }
    if(isset($_POST['Birthday'])){
        if($Birthday=="") $aler_info.=" Birthday";
    }
    if($aler_info!=""){
        $aler_info.=" error";
    }
    //print($aler_info);
    if(isset($_POST['Name'])&&isset($_POST['Email'])&&isset($_POST['Password'])&&isset($_POST['Phone'])&&isset($_POST['Birthday'])&&$aler_info==""){
        $max_sql="SELECT MAX(會員_ID) from `會員` ;";
        $MAX=$pdo->bindQuery($max_sql);
        foreach($MAX as $row){
            foreach($row as $key => $value){
                $Max_number=$value;
                // $Max_number =  substr($Max_number,1);
                $Max_number = (int) filter_var($Max_number, FILTER_SANITIZE_NUMBER_INT);
            }
        }
        $Max_number="M".str_pad($Max_number+1,4,'0',STR_PAD_LEFT);
        //print($Max_number);
        $sql="INSERT INTO `會員` (`會員_ID`, `會員_名稱`, `會員_密碼`, `會員_信箱`, `會員_電話`, `會員_生日`, `會員_點數`) VALUES ('$Max_number','$Name','$Password', '$Email', '$Phone', '$Birthday', '0');";
        $pdo->bindQuery($sql);
        echo '<script>alert("Register successful");location.href="login.php"</script>';
    }
    
    
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="register.css" rel="stylesheet"/>
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
                    </div>
                </div>
            </div>
        </nav>
        <div id="mybread">  
            <nav  aria-label="breadcrumb">      
                <ol class="breadcrumb" style="background-color: white;">
                    <li class="breadcrumb-item"><a href="./home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="./login.php">會員註冊_登入</a></li>
                    <li class="breadcrumb-item active" aria-current="page">會員註冊</li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
                <form method="post" action="register.php" >
                    <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $aler_info ?></h6>
                    <h2 style="letter-spacing: 3px;text-align:center;">Register</h2>
                    <div class="row">
                        <div class="col-4 offset-md-1 ">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input maxlength="15" name="Name" type="text" class="form-control" id="exampleInputEmail1"  placeholder="Enter name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input maxlength="50" name="Email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input maxlength="20" name="Password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-4 offset-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Phone</label>
                                <input maxlength="10" name="Phone" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter phone">
                              </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Birthday</label>
                              <input name="Birthday" type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
                            </div>
                            <button id="mybutton" type="submit" class="btn btn-secondary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>