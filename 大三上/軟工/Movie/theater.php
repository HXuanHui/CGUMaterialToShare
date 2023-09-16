<?php
    require("pdo.php");
    $pdo = new mypdo();
    $sql="SELECT 影城_ID,影城_圖片,影城_名稱,影城_地址,影城_電話 FROM `影城`;";
    $rows = $pdo->bindQuery($sql);
    $table="";
    foreach($rows as $row){
      foreach($row as $key => $value){
        if($key=="影城_ID"){
            $table.='<div class="col-md-6">
            <a href="./theater_info.php?theater_ID='.$value.'" class="stretched-link"></a>';
        }
        else if($key=="影城_圖片"){
            $table.='<div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="'.$value.'" class="card-img" alt="theater">
                </div>';
        }
        else if($key=="影城_名稱"){
            $table.='<div class="col-md-7">
            <div class="card-body">
            <h5 style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;">'.$value.'</h5>
            <hr/>';
        }
        else if($key=="影城_地址"){
            $table.='<p style="overflow: hidden;white-space: nowrap;text-overflow:ellipsis;">影城地址 : '.$value.'</p>';
        }
        else if($key=="影城_電話"){
            $table.='<p class="card-text">服務專線 : '.$value.'</p>
                            </div>
                        </div>
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
        <title>Movie_info</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="./theater_1.css" rel="stylesheet"/>
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
                        <a class="nav-item nav-link" href="#">影城介紹</a>
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
                    <li class="breadcrumb-item active" aria-current="page">影城介紹</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <?php
                    echo $table
                ?>
            </div>
        </div>
        
          
    </body>
</html>