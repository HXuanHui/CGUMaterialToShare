<?php
    require("pdo.php");
    $pdo = new mypdo();
    if(!isset($_COOKIE['user_ID'])){
        echo '<script>alert("請先登入/註冊");location.href="login.php"</script>';
    }
    $user_ID=$_COOKIE['user_ID'];
     //退票
    if(isset($_POST['cancel'])){
        $cancel_ID = $_POST['cancel'];
        $sql="DELETE FROM `票種-座位` WHERE `票種-座位`.`訂票_ID` = '".$cancel_ID."';";
        $sql.="DELETE FROM `訂餐紀錄` WHERE `訂餐紀錄`.`訂票_ID` = '".$cancel_ID."';";
        $sql.="DELETE FROM `訂票紀錄` WHERE `訂票紀錄`.`訂票_ID` = '".$cancel_ID."';";
        if(isset($_POST['pay_amt'])){
            $sql.="UPDATE `會員` SET `會員`.`會員_點數`=`會員`.`會員_點數`+ ".$_POST['pay_amt']."*0.1 WHERE `會員`.`會員_ID`='".$user_ID."';";
        }
        $pdo->bindQuery($sql);
        echo '<script>alert("刪除成功!");</script>';
    }
    // 顯示訂票資訊：電影中文名稱、電影英文名稱、訂購日期、場次日期和時間
    $sql="SELECT `電影資訊`.`電影_中文名稱`,`電影資訊`.`電影_英文名稱`,`訂票紀錄`.`訂票_日期`,`場次`.`場次_時間`,`場次`.`場次_日期`,`訂票紀錄`.`訂票_ID`,`電影資訊`.`電影_海報`";
    $sql.= "FROM ((`放映資訊` JOIN `電影資訊` USING(`電影_ID`))JOIN `場次` USING (`放映_ID`))JOIN `訂票紀錄` USING (`場次_ID`) WHERE `訂票紀錄`.`會員_ID`='".$user_ID."'ORDER BY `訂票紀錄`.`訂票_日期` DESC;";
    $cols = $pdo->bindQuery($sql);
    $table = "";
    foreach ($cols as $col){
        foreach ($col as $key => $value){
            if($key=='電影_中文名稱')$M_ch = $value;
            elseif($key=='電影_英文名稱')$M_en = $value;
            elseif($key=='電影_海報')$M_post = $value;
            elseif($key=='訂票_日期')$T_date = $value;
            elseif($key=='場次_日期')$S_date = $value;
            elseif($key=='場次_時間')$S_time = $value;
            elseif($key=='訂票_ID')$T_ID = $value;
        }
        // print($T_ID);
        //顯示
        $table.='<div class="col-md-6">
        <div class="card mb-3" style="max-width: 500px;">
            <div class="row no-gutters">
            <div class="col-md-4">
                <img src="'.$M_post.'" class="card-img" alt="theater">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                <h4 class="card-title" style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;">'.$M_ch.'</h4>
                <h6 class="card-title" style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;">'.$M_en.'</h6>
                <hr/>
                <h6 class="card-text">訂購日期 : '.$T_date.'</h6>
                <h6 class="card-text">場次 : '.$S_date.'</h6>
                </div>
            </div>
            </div>
        </div>
            <a href="./ticket_recorder.php?ticket_ID='.$T_ID.'"  name="ticket_ID" value='.$T_ID.' class="stretched-link"></a>
        </div>';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ticket_info</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
            <link href="./ticket_info.css" rel="stylesheet"/>
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
                                <a class="dropdown-item" href="#">訂票紀錄</a>
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
                    <li class="breadcrumb-item active" aria-current="page">訂票資訊</li>
                </ol>
            </nav>
        </div>
        
        <div id="myticket" class="container">
            <div class="row">
                <?php echo $table?>
            </div>
        </div>
    </body>
</html>
