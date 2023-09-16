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
    // 處理預設輸入資訊   
    $in_name = isset($_COOKIE['in_name'])? $_COOKIE['in_name']:$info[0];
    $in_mail = isset($_COOKIE['in_mail'])? $_COOKIE['in_mail']:$info[1];
    $in_date = isset($_COOKIE['in_date'])? $_COOKIE['in_date']:$info[3];
    $in_tel = isset($_COOKIE['in_tel'])? $_COOKIE['in_tel']:$info[2];
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
                <form method = "post" action="membership_info.php">
                    <div class="row">
                        <!-- <div id="content" class="col-5 offset-md-1 ">
                            <h3><?php echo $info[0]?></h3>
                            <hr/>
                            <h5>電子信箱 : <?php echo $info[1]?></h5>
                            <h5>生日 : <?php echo $info[3]?></h5>
                            <h5>電話 : <?php echo $info[2]?></h5>
                            <h5>點數 : <?php echo $info[4]?> </h5>
                        </div> -->
                        
                            <div  class="col-4 offset-md-1">
                                <div class="form-group">
                                    <label for="username">會員名稱</label>
                                    <input maxlength="10" name="Name" type="text" value='<?php echo $in_name ?>' class="form-control" id="exampleInputEmail1"  placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">電子信箱</label>
                                    <input name="Email" type="Email" value='<?php echo $in_mail ?>' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                </div>
                            </div>
                            <div  class="col-4 offset-md-1">
                                <div class="form-group">
                                    <label for="birthday">生日</label>
                                    <input name="Date" type="date" value='<?php echo $in_date ?>' class="form-control" id="exampleInputEmail1"  placeholder="Enter Date">
                                </div>
                                <div class="form-group">
                                    <label for="phone">電話</label>
                                    <input maxlength="10" name="Tel" type="tel" value= '<?php echo $in_tel ?>' class="form-control" id="exampleInputEmail1"  placeholder="EX:0123456789">
                                </div>
                                <button name="modify_but" id="modify_but" style="position:relative;left:72%;" type="submit" class="btn btn-secondary">確認修改</button>
                            
                            </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="./membership_info.js"></script>
</html>