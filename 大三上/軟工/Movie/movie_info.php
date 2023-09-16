<?php
    require("pdo.php");
    $pdo = new mypdo();
    $onsale=isset($_GET['onsale'])? htmlspecialchars($_GET['onsale']):'';
    if($onsale=="1"){//熱售中
        $status="熱售中";
    }
    else if($onsale=="0"){//即將上映
        $status="即將上映";
    }
    $movie_ID=isset($_GET['movie_ID'])? htmlspecialchars($_GET['movie_ID']):'';
    $sql="SELECT `電影_海報`,`電影_中文名稱`,`電影_英文名稱`,`電影_上映日期`,`電影_導演`,`電影_演員`,`電影_長度`,`電影_介紹`,`電影_預告` FROM `電影資訊` WHERE `電影_ID`='$movie_ID';";
    $rows = $pdo->bindQuery($sql);
    $info_table="";
    $intro_table="";
    foreach($rows as $row){
        foreach($row as $key => $value){
            if($key=="電影_海報"){
                $info_table.='<div  class="col-4 offset-md-1">
                <img  id="img_pos" src="'.$value.'" class="card-img-top" alt="pic">
                </div>';
            }
            else if($key=="電影_中文名稱"){
                $movie_name=$value;
                $info_table.='<div id="content" class="col-5 offset-md-1 ">
                    <h4>'.$value.'</h4>';
            }
            else if($key=="電影_英文名稱"){
                $info_table.='<h4 class="h4">'.$value.'</h4><hr/>';
            }
            else if($key=="電影_上映日期"){
                if($value==""){
                    $info_table.='<h6>上映日期 : 無資訊</h6>';
                }
                else{
                    $info_table.='<h6>上映日期 : '.$value.'</h6>';
                }
            }
            else if($key=="電影_導演"){
                if($value==""){
                    $info_table.='<h6>導演 : 無資訊</h6>';
                }
                else{
                    $info_table.='<h6>導演 : '.$value.'</h6>';
                }
                
            }
            else if($key=="電影_演員"){
                if($value==""){
                    $info_table.='<h6>演員 : 無資訊</h6>';
                }
                else{
                    $info_table.='<h6>演員 : '.$value.'</h6>';
                }
               
            }
            else if($key=="電影_長度"){
                if($value==""){
                    $info_table.='<h6>片長 : 無資訊</h6>';
                }
                else{
                    $info_table.='<h6>片長 : '.$value.'</h6>';
                }
                //我要訂票button
                if($onsale=="1"){
                    $info_table.='<button  style="position: absolute; left: 320px;top: 350px;height: 40px;width: 100px;" type="button" class="btn btn-secondary"><a style="color:white ;" href="order_ticket.php?movie_ID='.$movie_ID.'">我要訂票</a></button>
                    </div>
                  </div>';
                }
                else if($onsale=="0"){
                    $info_table.='</div>
                    </div>';
                }
            }
            else if($key=="電影_介紹"){
                $intro_table.='<p>'.$value.'</p>';
            }
            else if($key=="電影_預告"){
                if($value==""){
                    $info_table.='';
                }
                else{
                    $intro_table.='<div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" width="930" height="500" src="'.$value.'"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                }
                

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
          <link href="./movie_info.css" rel="stylesheet"/>
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
                    <li class="breadcrumb-item"><a href="./movies.php?onsale=<?php echo $onsale?>"><?php echo $status?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $movie_name?></li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
                <div class="row">
                    <?php echo $info_table?>
                  </div>
            </div>
        </div>
        <div class="container">
            <!-- Stack the columns on mobile by making one full-width and the other half-width -->
            <div class="row">
                <div  id="box1" class="col-12 col-md-8 offset-md-2">
                    <h4>劇情介紹</h4>
                    <?php echo $intro_table?>
                </div>
            </div>
        </div>
    </body>
</html>