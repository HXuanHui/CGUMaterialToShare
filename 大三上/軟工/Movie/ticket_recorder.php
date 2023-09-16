<?php
    require("pdo.php");
    $pdo = new mypdo();
    // 顯示訂票詳細資訊：電影中文名稱、電影英文名稱、訂購日期、場次日期和時間、影城、票種、座位、餐點、付費方式、金額
    if(!isset($_COOKIE['user_ID']))
        echo '<script>alert("請先登入/註冊");location.href="login.php"</script>';
    else
        $user_ID=$_COOKIE['user_ID'];
    if(isset($_GET['ticket_ID']))
        $ticket_ID=$_GET['ticket_ID'];
    else
        echo '<script>alert("讀取訂票資料錯誤");location.href="ticket_info.php"</script>';
    //電影中文名稱、電影英文名稱、訂購日期、場次日期和時間
    $sql="SELECT `電影資訊`.`電影_中文名稱`,`電影資訊`.`電影_英文名稱`,`電影資訊`.`電影_海報`,`訂票紀錄`.`訂票_日期`,`場次`.`場次_時間`,`場次`.`場次_日期`,`場次`.`場次_ID`,`訂票紀錄`.`付款方式_ID`,`放映資訊`.`影城_影廳_ID`,`訂票紀錄`.`訂票_ID`";
    $sql.="FROM ((`放映資訊` JOIN `電影資訊` USING(`電影_ID`))JOIN `場次` USING (`放映_ID`))JOIN `訂票紀錄` USING (`場次_ID`) WHERE `訂票紀錄`.`訂票_ID`='".$ticket_ID." 'AND `訂票紀錄`.`會員_ID`='".$user_ID."';";
    $cols = $pdo->bindQuery($sql);
    foreach ($cols as $col){
        foreach ($col as $key => $value){
            if($key=='電影_中文名稱')$M_ch = $value;
            elseif($key=='電影_英文名稱')$M_en = $value;
            elseif($key=='電影_海報')$M_post = $value;
            elseif($key=='訂票_日期')$T_date = $value;
            elseif($key=='場次_時間')$S_time = $value;
            elseif($key=='場次_日期')$S_date = $value;
            elseif($key=='場次_ID')$S_ID = $value;
            elseif($key=='訂票_ID')$T_ID = $value;
            elseif($key=='付款方式_ID')$P_ID = $value;
            elseif($key=='影城_影廳_ID')$TR_ID = $value;
        }
    }
    //影城
    $sql="SELECT `影城_名稱`,`影廳_名稱` FROM (`影城-影廳` LEFT JOIN `影城` ON `影城-影廳`.`影城_ID`= `影城`.`影城_ID`)
    LEFT JOIN `影廳規格` ON `影廳規格`.`影廳_ID`= `影城-影廳`.`影廳_ID` WHERE `影城-影廳`.`影城_影廳_ID`='".$TR_ID."';";
    $theatres = $pdo->bindQuery($sql);
    foreach ($theatres as $theatre){
        foreach ($theatre as $key => $value){
            if($key=='影城_名稱')$th_name = $value;
            else if($key=='影廳_名稱')$th_name .= " ".$value;
        }
    }
    //票種、座位、金額
    $sql = "SELECT `票種`.`票種_名稱`,`票種`.`票種_價格`,`票種-座位`.`訂票_座位`FROM `票種-座位` JOIN `票種` USING (`票種_ID`) WHERE `票種-座位`.`訂票_ID`='".$T_ID."';";
    $infos = $pdo->bindQuery($sql);
    $T_content="";$seat_content="";$price=0;$TK_kinds=array(0,0,0);
    foreach ($infos as $info){
        foreach ($info as $key => $value){
            if($key=='票種_名稱'){
                if($value == '全票')$TK_kinds[0]+= 1;
                elseif($value == '優待票')$TK_kinds[1]+= 1;
                elseif($value == '敬老票')$TK_kinds[2]+=1;
            }
            elseif($key=='票種_價格')$TK_price = $value;
            elseif($key=='訂票_座位')$T_seat = $value;
        }
        // $T_content.=$TK_name.'*1 ';
        $seat_content .= $T_seat.' ';
        $price += $TK_price;
    }
    for($i = 0;$i<3;$i++){
        if($TK_kinds[$i]>0){
            if($i == 0)$T_content.= '全票*'. $TK_kinds[0].' ';
            elseif($i == 1)$T_content.= '優待票*'. $TK_kinds[1].' ';
            elseif($i == 2)$T_content.= '敬老票*'. $TK_kinds[2].' ';
        }
    }
    //餐點:不一定有
    $sql = "SELECT `套餐`.`套餐_名稱`,`訂餐紀錄`.`套餐_數量`,`套餐`.`套餐_價格` FROM `訂餐紀錄` JOIN `套餐` USING(`套餐_ID`) WHERE `訂餐紀錄`.`訂票_ID`='".$T_ID."';";
    $foods = $pdo->bindQuery($sql);
    // print_r($foods);
    $F_content="";
    foreach ($foods as $food){
        foreach ($food as $key => $value){
            if($key=='套餐_名稱')$F_name = $value;
            elseif($key=='套餐_數量')$F_amt = $value;
            elseif($key=='套餐_價格')$Fvalue = $value;
        }
        $price+=$Fvalue*$F_amt;
        $F_content.=$F_name.'*'.$F_amt.' ';
    }
    if($F_content=="") $F_content="無";
    
    //付費方式
    $sql = "SELECT `付款方式`.`付款方式_名稱` FROM `付款方式` WHERE `付款方式`.`付款方式_ID`='".$P_ID."';";
    $pways = $pdo->bindQuery($sql);
    foreach ($pways as $pway){
        foreach ($pway as $key => $value){
            if($key=='付款方式_名稱')$P_name = $value;
        }
    }
    //輸出
    $content = '<div  class="col-4 offset-md-1">'
                    .'<img  id="img_pos" src="'.$M_post.'" class="card-img-top" alt="pic">'
                .'</div>'
                .'<div id="content" class="col-5 offset-md-1 ">'
                    .'<h3>'.$M_ch.'</h3>'
                    .'<h3 class="h3">'.$M_en.'</h3>'
                    .'<hr/>'
                    .'<h6>訂購日期 : '.$T_date.'</h6>'
                    .'<h6>場次 : '.$S_date.' '.$S_time.'</h6>'
                    .'<h6>影城 : '.$th_name.'</h6>'
                    .'<h6>票種 : '.$T_content.'  </h6>'
                    .'<h6>座位 : '.$seat_content.'</h6>'
                    .'<h6>餐點 : '.$F_content.'</h6>'
                    .'<h6>付款方式 : '.$P_name.'</h6>'
                    .'<h6>金額 : '.$price.'</h6>'
                    .'<input type="hidden" name="pay_amt" value="'.$price.'">';
    //判斷是否已經撥放
    $sql = "SELECT COUNT(*) FROM `場次` WHERE `場次`.`場次_ID`='".$S_ID."'";
    $sql .= " AND (`場次`.`場次_日期`> CAST(now() AS date) OR `場次`.`場次_日期` = CAST(now() AS date) AND `場次`.`場次_時間` <  CAST(now() AS time));";
    $canCancel = $pdo->bindQuery($sql);
    foreach ($canCancel as $cancel){
        foreach ($cancel as $key => $value){
            $canCancel=$value;
        }
    }
    if($canCancel>0){
        $content.='<button  id="mybutton" style="position: absolute; left: 320px;top: 350px;height: 40px;width: 100px;" name="cancel" type="submit" value = '.$T_ID.' class="btn btn-secondary">取消訂票</button>';
    }
    $content.='</div>';
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
            <link href="./ticket_recorder.css" rel="stylesheet"/>
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
                    <li class="breadcrumb-item"><a href="./ticket_info.php">訂票資訊</a></li>
                    <li class="breadcrumb-item active" aria-current="page">訂票詳細資訊</li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" class="jumbotron jumbotron-fluid">
            <div  class="container">
            <form id method = "post" action="./ticket_info.php">
                <div class="row">
                    <?php echo $content ?>
                </div>
            </form>
            </div>
        </div>
        
    </body>
</html>