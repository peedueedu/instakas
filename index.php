<?php
  require_once('functions.php');
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>



<?php
$requiredfields = '<p style="visibility: hidden; margin: 0px;">aus</p>';
$registersuccess = '<p style="display: none;">aus</p>';
$unexists = '<p style="display:none;">aus</p>';
$passworderror = '<p style="display:none;">aus</p>';

if(isset($_POST["signup"])) {
$uusemail = cleanInput($_POST["email"]);
$uusfullname = cleanInput($_POST["fullname"]);
$uusfullname = ucwords($uusfullname);
$uususername = cleanInput($_POST["username"]);
$uuspassword = cleanInput($_POST["password"]);

$getusernames = getusernames($yhendus);

for($i = 0; $i < count($getusernames); $i++): ?>
  <?php  $getusernames[$i]->registeredusername;
  $getusernamesarray[]=$getusernames[$i]->registeredusername; ?>
<?php endfor;


if(empty($uusemail) OR empty($uusfullname)
OR empty($uususername) OR empty($uuspassword)) {
  $requiredfields = '<p style="color:red; font-size: 16px; margin: 0px;">*All fields required</p>';
}
else if (in_array($uususername, $getusernamesarray)) {
  $requiredfields = '<p style="display: none;">aus</p> ';
  $unexists = '<p style="color:red; font-size: 16px; margin: 0px;">Username already taken</p>';
}
else if (strlen($uuspassword)<8){
  $passworderror = '<p style="color:red; font-size: 16px; margin: 0px;">Password too short</p>';
  $unexists = '<p style="display: none;">aus</p> ';
  $requiredfields = '<p style="display: none;">aus</p> ';
}
else{
  uususer($uusemail, $uusfullname, $uususername, $uuspassword, $yhendus);
  $registersuccess = '<p style="color:red; font-size:16px; margin: 0px;">Registration successful</p>';
  $requiredfields = '<p style="display: none;">aus</p> ';
  $userpath = 'u/'.$uususername.'/userpics/profilepic/';
  $mode = 0777;
  mkdir($userpath, $mode, true);
  copy('u/index.php', 'u/'.$uususername.'/index.php');
}
}
?>



<body onload="play()">

<!-- pilditeema -->
<script type="text/javascript">
    var aImages  =  [ "imgs/example/example.jpg",
                      "imgs/example/example1.jpg",
                      "imgs/example/example2.jpg",
                      "imgs/example/example3.jpg",
                      "imgs/example/example4.jpg"];
    var oImage   =  null;
    var iIdx     =  0;
    function play(){
      try{
        //look only once in DOM and cache it
        if(oImage===null){
          oImage  =  window.document.getElementById("imgHolder");
        }
        oImage.src  =  aImages[(++iIdx)%(aImages.length)];
        setTimeout('play()',2500);
      }catch(oEx){
        //some error handling here
      }
    }
  </script>

<div class="center">
<div class="center2">

<div class="vasak">

<img id="imgHolder" class="iphone"/>

</div>

<div class="parem">
  <div class="paremlogo"></div>
  <h4 class="hallikas">Sign up to see photos and videos <br>from your friends.</h4>
  <button type="button" class="nupukas">Log in with Facebook</button>
  <p class="or"><span>OR</span></p>

  <form method="post" autocomplete="off" action="index.php">

    <input type="text" name="email" placeholder="Email" class="maininput" ><br>

    <input type="text" name="fullname" placeholder="Full Name" class="maininput" ><br>

    <input type="text" name="username" placeholder="Username" class="maininput"  ><br>

    <input type="password" name="password" placeholder="Password" class="maininput" >

    <br>
    <input type="submit" name="signup" value="Sign up" class="nupukas">
</form>
<?php
echo $requiredfields;
echo $registersuccess;
echo $unexists;
echo $passworderror;
?>
  <p class="hallikas">By signing up, you agree to our <br><b>Terms</b> & <b>Privacy Policy.</b></p>

  <div class="parem2">
    <p>Have an account? <a href="login.php">Log in</a></p>
  </div>

  <div class="app">
    <p>
      Get the app.
    </p>

      <a href="https://itunes.apple.com/app/instagram/id389801252?pt=428156&ct=igweb.unifiedHome.badge&mt=8" target="_blank" ><img src="imgs/appstore.png"  id="appstore" /></a>

    <a href="https://play.google.com/store/apps/details?id=com.instagram.android&referrer=utm_source%3Dinstagramweb%26utm_campaign%3DunifiedHome%26utm_medium%3Dbadge" target="blank" ><img src="imgs/playstore.png" id="playstore" /></a>
  </div>
</div>

</div>

</div>



</body>
</html>
