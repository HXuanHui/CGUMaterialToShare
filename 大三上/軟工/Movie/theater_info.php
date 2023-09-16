<?php
    require("pdo.php");
    $pdo = new mypdo();
    $table="";
    $sale_table="";
    $no_sale_table="";
    $theater_ID=isset($_GET['theater_ID'])? htmlspecialchars($_GET['theater_ID']):'';
    //print($theater_ID);
    $sql="SELECT 影城_ID,影城_圖片,影城_名稱,影城_電話,影城_地址 FROM `影城` WHERE 影城_ID='$theater_ID';";
    $rows = $pdo->bindQuery($sql);
    foreach($rows as $row){
        foreach($row as $key => $value){
            if($key=="影城_圖片"){
                $table.='<div  class="col-6 offset-md-1">
                <img  id="img_pos" src="'.$value.'" class="card-img-top" alt="theater">
                </div>';
            }
            else if($key=="影城_名稱"){
                $theater_name=$value;
                $table.='<div id="content" class="col-4">
                <h3>'.$value.'</h3>
                <hr/>';
            }
            else if($key=="影城_電話"){
                $table.='<h5>服務專線：'.$value.'</h5>';
            }
            else if($key=="影城_地址"){
                $table.='<h5>影城地址：'.$value.'</h5>
                    </div>
                </div>';
            }
           
        }
    }
    //上映電影
    $sale_sql="SELECT DISTINCT `電影_海報` ,`電影資訊`.`電影_ID`
    FROM (`影城-影廳` LEFT JOIN `放映資訊` ON `影城-影廳`.`影城_影廳_ID`=`放映資訊`.`影城_影廳_ID`) LEFT JOIN `電影資訊` ON `放映資訊`.`電影_ID`=`電影資訊`.`電影_ID`
    WHERE `影城_ID` = '$theater_ID' AND `放映資訊`.`電影_ID` IS NOT NULL AND `電影_上映日期`<CURDATE();";
    $rows = $pdo->bindQuery($sale_sql);
    foreach($rows as $row){
        foreach($row as $key => $value){
            if($key=="電影_海報"){
                $sale_table.='<div class="mycard">
                            <div class="col-sm-4">
                                <div class="card">
                                    <img class="cardpic" src="'.$value.'" class="card-img-top" alt="pic">';
            }
            else if($key=="電影_ID"){
                $sale_table.='<a href="movie_info.php?onsale=1&movie_ID='.$value.'" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>';
            }
           
        }
    }
    //即將上映
    $no_sale_sql="SELECT DISTINCT `電影_海報` ,`電影資訊`.`電影_ID`
    FROM (`影城-影廳` LEFT JOIN `放映資訊` ON `影城-影廳`.`影城_影廳_ID`=`放映資訊`.`影城_影廳_ID`) LEFT JOIN `電影資訊` ON `放映資訊`.`電影_ID`=`電影資訊`.`電影_ID`
    WHERE `影城_ID` = '$theater_ID' AND `放映資訊`.`電影_ID` IS NOT NULL AND `電影_上映日期`>CURDATE();";
    $rows = $pdo->bindQuery($no_sale_sql);
    foreach($rows as $row){
        foreach($row as $key => $value){
            if($key=="電影_海報"){
                $no_sale_table.='<div class="mycard">
                            <div class="col-sm-4">
                                <div class="card">
                                    <img class="cardpic" src="'.$value.'" class="card-img-top" alt="pic">';
            }
            else if($key=="電影_ID"){
                $no_sale_table.='<a href="movie_info.php?onsale=0&movie_ID='.$value.'" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>';
            }
           
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>theater_info</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="./theater_info.css" rel="stylesheet"/>
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
                        <a style="position:relative ;left:600px" class="nav-item nav-link" href="login.php">會員註冊/登入</a>
                    </div>
                </div>
            </div>
        </nav>
        <div id="mybread">  
            <nav  aria-label="breadcrumb">      
                <ol class="breadcrumb" style="background-color: white;">
                    <li class="breadcrumb-item"><a href="./home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="./theater.php">影城介紹</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $theater_name ?></li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
                <div class="row">
                    <?php
                        echo $table
                    ?>
            </div>
        </div>
        <h4 style="position:relative;left:150px;width: 200px;">上映電影</h4>
        <div id="show_movie">
            <div class="container">
                <div id="card_pos">
                    <div class="row" >
                    <?php
                        if($sale_table!=""){
                            echo $sale_table;
                        }
                        else{
                            echo '<h4 style="letter-spacing: 3px;text-align:center;">目前沒有上映電影</h4>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- <h4 style="position:relative;left:150px;width: 200px;">即將上映</h4>
        <div id="show_movie">
            <div class="container">
                <div id="card_pos">
                    <div class="row" >
                        <?php
                                if($no_sale_table!=""){
                                    echo $no_sale_table;
                                }
                                else{
                                    echo '<h4 style="letter-spacing: 3px;text-align:center;">目前沒有即將上映</h4>';
                                }
                        ?>
                    </div>
                </div>
            </div>
        </div> -->
    </body>
</html>