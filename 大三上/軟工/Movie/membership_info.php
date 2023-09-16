<?php
    require("pdo.php");
    $pdo = new mypdo();
    // 顯示會員資訊
    if(isset($_COOKIE['user_ID'])){
        $user_ID=$_COOKIE['user_ID'];
        $sql="SELECT * FROM `會員` where `會員`.`會員_ID`= '".$user_ID."';";
    }
    else{
        echo '<script>alert("請先登入/註冊");location.href="login.php"</script>';
    }
    $cols = $pdo->bindQuery($sql);
    foreach ($cols as $col){
        foreach ($col as $key => $value){
            if($key=='會員_名稱')$info[0] = $value;
            elseif($key=='會員_信箱')$info[1] = $value;
            elseif($key=='會員_電話')$info[2] = $value;
            elseif($key=='會員_生日')$info[3] = $value;
            elseif($key=='會員_點數')$info[4] = $value;
        }
    }
    //修改資料
    $change = false;
    $sql_mod = "";
    if(isset($_POST['modify_but'])){
        $Name = isset($_POST['Name'])? htmlspecialchars($_POST['Name']):'';
        $Email = isset($_POST['Email'])? htmlspecialchars($_POST['Email']):'';
        $Date = isset($_POST['Date'])? htmlspecialchars($_POST['Date']):'';
        $Tel = isset($_POST['Tel'])? htmlspecialchars($_POST['Tel']):'';
        if($Name!=''){
            $change = true;
            setcookie("in_name",$Name,time()+3600);//cookie存活一小時
            $sql_mod.='`會員`.`會員_名稱`="'.$Name.'" ' ;
        }
        if($Email!=''){
            if($change)$sql_mod.=',';
            $change = true;
            setcookie("in_mail",$Email,time()+3600);//cookie存活一小時
            $sql_mod.='`會員`.`會員_信箱`="'.$Email.'" ';
            $sql= 'SELECT count(*) FROM 會員 where `會員`.`會員_信箱` = "'.$Email.'"AND `會員`.`會員_ID` != "'.$user_ID.'";';
            $rets = $pdo->bindQuery($sql);
            foreach ($rets as $ret){
                foreach($ret as $key => $value){
                    if($value > 0){
                        $change = false;
                        echo '<script>alert("已有重複信箱");location.href="membership_change.php"</script>';
                    }
                }
            }
        }
        if($Date!=''){
            if($change)$sql_mod.=',';
            $change = true;
            setcookie("in_date",$Date,time()+3600);//cookie存活一小時
            $sql_mod.='`會員`.`會員_生日`="'.$Date.'"';
        }
        if($Tel!=''){
            if($change)$sql_mod.=',';
            $change = true;
            setcookie("in_tel",$Tel,time()+3600);//cookie存活一小時
            $sql_mod.='`會員`.`會員_電話`="'.$Tel.'"';
        }
        if($change){
            $sql_mod = 'UPDATE `會員` SET'.$sql_mod.' WHERE `會員`.`會員_ID`="'.$_COOKIE['user_ID'].'";';
            $pdo->bindQuery($sql_mod);
            if(isset($_COOKIE['in_name'])){
                setcookie("in_name", "", time()-(60*60));//刪除cookie
                unset($_COOKIE["in_name"]);//刪除cookie副本
            }
            if(isset($_COOKIE['in_mail'])){
                setcookie("in_mail", "", time()-(60*60));//刪除cookie
                unset($_COOKIE["in_mail"]);//刪除cookie副本
            }
            if(isset($_COOKIE['in_date'])){
                setcookie("in_date", "", time()-(60*60));//刪除cookie
                unset($_COOKIE["in_date"]);//刪除cookie副本
            }
            if(isset($_COOKIE['in_tel'])){
                setcookie("in_tel", "", time()-(60*60));//刪除cookie
                unset($_COOKIE["in_tel"]);//刪除cookie副本
            }
            echo '<script>alert("修改成功!!");location.href="membership_info.php"</script>';
        }
        
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Movie_info</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
            <link href="./membership_info.css" rel="stylesheet"/>
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
                                <a class="dropdown-item" href="#">會員資料</a>
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
                    <li class="breadcrumb-item active" aria-current="page">會員資訊</li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
                <div class="row">
                    <div id="content" class="col-5 offset-md-4 ">
                        <h3><?php echo $info[0]?></h3>
                        <hr/>
                        <h5>電子信箱 : <?php echo $info[1]?></h5>
                        <h5>生日 : <?php echo $info[3]?></h5>
                        <h5>電話 : <?php echo $info[2]?></h5>
                        <h5>點數 : <?php echo $info[4]?> </h5>
                        <a href="membership_change.php"><button  id="mybutton" type="submit" class="btn btn-secondary">修改資料</button></a>
                    </div>
                    <!-- <div id="modify" class="col-4 offset-md-1 "></div> -->
                </div>
            </div>
        </div>
    </body>
    <script src="./membership_info.js"></script>
</html>