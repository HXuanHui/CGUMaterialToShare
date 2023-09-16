var mybutton=document.getElementById("mybutton");
var modify_form=document.getElementById("modify");
var modify_but=document.getElementById("modify_but");
var name = "哈囉";
function handle_click()
{
    modify_form.innerHTML=
    '<form method = "post" action="membership_info.php">'
        +'<div class="form-group">'
            +'<label for="username">會員名稱1</label>'
            +'<input maxlength="10" name="Name" type="text" value=<?php if(isset($_COOKIE["in_name"]))echo $_COOKIE["in_name"] ?> class="form-control" id="exampleInputEmail1"  placeholder="Enter name">'
        +'</div>'
        +'<div class="form-group">'
            +'<label for="exampleInputEmail1">電子信箱2</label>'
            +'<input name="Email" type="Email" value=<?php if(isset($_COOKIE["in_mail"]))echo $_COOKIE["in_name"] ?> class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">'
        +'</div>'
        +'<div class="form-group">'
            +'<label for="birthday">生日</label>'
            +'<input name="Date" type="date" value=<?php if(isset($_COOKIE["in_date"]))echo $_COOKIE["in_date"] ?>class="form-control" id="exampleInputEmail1"  placeholder="Enter Date">'
        +'</div>'
        +'<div class="form-group">'
            +'<label for="phone">電話</label>'
            +'<input name="Tel" type="tel" value=<?php if(isset($_COOKIE["in_tel"]))echo $_COOKIE["in_tel"] ?>class="form-control" id="exampleInputEmail1"  placeholder="EX:0123456789">'
        +'</div>'
        +'<button name="modify_but" id="modify_but" style="position:relative;left:72%; type="submit" class="btn btn-secondary">確認修改</button>'
    +'</form>'
}
/*onclick="modify_info()"*/
function modify_info(){
    alert("修改成功")
}