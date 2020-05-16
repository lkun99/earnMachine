function validate_code(){
    var code = document.getElementById('code').value;
    var reg = new RegExp('^[0-9]+$');
    if(!reg.test(code)){
        document.getElementById('code').setAttribute("style","border: 2px solid red;");
    } else {
        document.getElementById('code').setAttribute("style","border: 1px solid #80bdff;");
    }
}
function validate_form(){
    var reg = new RegExp('^[_A-z0-9]*((-|\s)*[_A-z0-9])*$');
    var nick = document.getElementById('nick').value;
    if(!reg.test(nick)){
        document.getElementById('nick').setAttribute("style","border: 2px solid red;");
        return false;
    } 
    var password = document.getElementById('password').value;
    if(password.length <= 5){
        document.getElementById('password').setAttribute("style","border: 2px solid red;");
        return false;
    }
    var confirm_password = document.getElementById('confirm_password').value;
    if(password != confirm_password){
        document.getElementById('confirm_password').setAttribute("style","border: 2px solid red;");
        return false;
    } else{ 
        return true;
    }
}
function change_avatar() {
    var avatar = document.getElementById('avatar-holder');
    var avatar_img = document.getElementById('avatar');
    var button = document.getElementById('av-main');
    avatar.addEventListener("mouseenter", function () {
       var i = 100;
       setInterval(function () {
          i--;
          if (i > 30) avatar_img.style.filter = "brightness(" + i + "%)";
          else return 0;
       }, 1);
       button.style.display = "inherit";

    });
    avatar.addEventListener("mouseleave", function () {
       avatar_img.style.filter = "brightness(100%)";
       button.style.display = "none";
    });
 }
 function set_file() {
    var input_file = document.getElementById('fileToUpload');
    var input_submit = document.getElementById('submitInput');
    if (input_file.value) {
       input_submit.click();
    } else {
       input_file.click();
       var button = document.getElementById('av-main');
       button.innerHTML = 'Potwierdź';
       button.style.color = 'red';
       button.style.borderColor = 'red';
       var avatar = document.getElementById('avatar-holder');
       
    }
    //input_submit.click();

 }
 function hide_cookie(){
     document.getElementById('cookie_alert').setAttribute("style","display:none;");
 }