<?php
    require("pdo.php");
    $pdo = new mypdo();
    $aler_info="";//紀錄警告資訊
    $member_ID="";//紀錄ID
    $Mypass="";//紀錄密碼
    $Email=isset($_POST['Email'])? htmlspecialchars($_POST['Email']):'';
    $Password=isset($_POST['Password'])? htmlspecialchars($_POST['Password']):'';
    //偵錯訊息
    if(isset($_POST['Email'])){
        //沒輸入Email
        if($Email=="") $aler_info.="Email not input";
        //檢查email是否存在
        else{
            $Exit="SELECT COUNT(*) FROM `會員` WHERE `會員_信箱`= '$Email';";
            $return=$pdo->bindQuery($Exit);
            foreach($return as $row){
                foreach($row as $key => $value){
                    $num=$value;
                }
            }
            //沒註冊
            if($num==0){
                $aler_info.="Email not Exit please register";
            }
            //已註冊
            else if($num==1){
                if(isset($_POST['Password'])){
                    if($Password=="") $aler_info.=" Password not input";
                    else{
                        $Exit_password="SELECT `會員_ID`,`會員_密碼` FROM `會員` WHERE `會員_信箱`='$Email';";
                        $return_password=$pdo->bindQuery($Exit_password);
                        foreach($return_password as $row){
                            foreach($row as $key => $value){
                                if($key=="會員_ID"){
                                    $member_ID=$value;
                                }
                                else if($key=="會員_密碼"){
                                    $Mypass=$value;
                                }
                                
                            }
                        }
                        if($Password==$Mypass){
                            setcookie("user_ID",$member_ID,time()+3600*24*3);//cookie存活三天
                            echo '<script>alert("Login successful");location.href="home.php"</script>';
                             
                        }
                        else{
                            $aler_info="Password error";
                            
                        }

                    }

                }
            }
        }
    }
    //如果有錯誤 清除cookie
    if($aler_info!=""){
        setcookie("user_ID", "", time()-(60*60*24*7));//刪除cookie
        unset($_COOKIE["user_ID"]);//刪除cookie副本
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="./login.css" rel="stylesheet"/>
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
                    <li class="breadcrumb-item active" aria-current="page">會員註冊_登入</li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
                <div class="row">
                    <div id="content" class="col-6 offset-md-3 ">
                        <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $aler_info ?></h6>
                        <h2 style="letter-spacing: 3px;position: relative;left: 40%;">Login</h2>
                        <form method="post" action="login.php">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Email address</label>
                              <input name="Email" type="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Password</label>
                              <input name="Password" type="Password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <a href="register.php"><button id="mybutton1" type="button" class="btn btn-outline-secondary">註冊</button></a>
                            <button id="mybutton2" type="submit" class="btn btn-secondary">登入</button>
                        </form>
                    </div>
                  </div>
            </div>
        </div>
    </body>
</html>