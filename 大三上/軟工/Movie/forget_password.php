<!-- 
    Email:groupviicinemas@gmail.com
    Password:software2022
    name:Group VII 

    app password:wxdgpdksidmwnjla
-->

<?php
    require 'phpmailer/includes/PHPMailer.php';
    require 'phpmailer/includes/SMTP.php';
    require 'phpmailer/includes/Exception.php';
    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

    require("pdo.php");
    $pdo = new mypdo();
    $aler_info="";
    function sanitize_my_email($field) {
        $field = filter_var($field, FILTER_SANITIZE_EMAIL);
        if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
    $Email=isset($_POST['Email'])? htmlspecialchars($_POST['Email']):'';
    if(isset($_POST['Email'])){
        if($Email=="") $aler_info.="Email not input";
        else{
            $sql="SELECT COUNT(*) FROM `會員` WHERE `會員_信箱`='$Email';";
            $result=$pdo->bindQuery($sql);
            foreach($result as $row){
                foreach($row as $key => $value){
                    $Email_Exit=$value;
                }
            }
            if($Email_Exit!=1){
                //代表Email還未註冊
                $aler_info.="This Email not yet be registered";
            }
            else{
                //使用者輸入已註冊正確的Email
                $to_email = $Email;
                $subject = 'Forget password PHP Mail';
                $message = 'This mail is sent using the PHP mail function';
                $headers = 'From: Movie @ company . com';
                $secure_check = sanitize_my_email($to_email);
                if($secure_check==false){
                    $aler_info.="This Email is invalid input";
                }
                else{

                    $newPswd = "";
                    for($i = 0; $i < 8; $i++){ 
                        $seed_words = rand(0,35);
                        if($seed_words > 9){
                            $seed_words += 87;
                            $seed_words = chr($seed_words);
                        }
                        $newPswd .= $seed_words;
                    }

                    $mailContent = "<p>您的新密碼:<br/>";
                    $mailContent .= $newPswd;
                    $mailContent .= "<br/><br/>請使用此組密碼登入威秀影城帳戶，並建議您立即修改密碼。<br/></p>";

                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = "tls";
                    $mail->Port = "587";
                    $mail->CharSet = "utf-8";
                    $mail->Username = "groupviicinemas@gmail.com";          //帳號
                    $mail->Password = "wxdgpdksidmwnjla";                   //密碼
                    //$mail->SMTPDebug  = 1;
                    $mail->Encoding = "base64";
                    $mail->isHTML(true);                                    //內容HTML格式
                    $mail->From = "groupviicinemas@gmail.com";              //寄件者信箱
                    $mail->FromName = "Group VII";                          //寄信者姓名
                    $mail->Subject = "威秀影城 會員新密碼";                   //信件主旨
                    $mail->Body = $mailContent;                             //信件內容
                    $mail->AddAddress($to_email);                           //收件者信箱
                     
                    if(!$mail->send()) {
                        $aler_info .= "信件發送失敗\n";
                    } else {    
                        $aler_info .= "新密碼已寄送到您的信箱，請前往察看後重新登入。\n";
                    }

                    $sql = "UPDATE `會員` SET `會員_密碼` = '".$newPswd."' WHERE `會員_信箱` = '".$to_email."'";
                    $pdo -> bindQuery($sql);
                }
            }
        }
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Forget_password</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable = no">
          <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
          <link href="./forget_password.css" rel="stylesheet"/>
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
                                <a class="dropdown-item" href="#">忘記密碼</a>
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
                    <li class="breadcrumb-item active" aria-current="page">忘記密碼</li>
                </ol>
            </nav>
        </div>
        <div id="myjumbotron" style="height: 400px;"  class="jumbotron jumbotron-fluid">
            <div  class="container">
                <div class="row">
                    <div id="content" class="col-6 offset-md-3 ">
                        <h6 style="letter-spacing: 3px;text-align:center;color:tomato" ><?php echo $aler_info ?></h6>
                        <h2 style="letter-spacing: 3px;position: relative;left: 20%;">Forget Password</h2>
                        <form  style="margin-top:30px" method="post" action="forget_password.php">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Email address</label>
                              <input name="Email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                              <button id="mybutton" type="submit" class="btn btn-secondary">更改密碼</button>
                            </div>
                        </form>
                    </div>
                  </div>
            </div>
        </div>
    </body>
    
</html>