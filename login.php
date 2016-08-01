<?php
  require_once('functions.php');
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="loginstylesheet.css">
  </head>


  <?php

  $username = $password = "";
  $username_error = $password_error = "";



    if (isset($_POST["login"])){

      if (empty($_POST["username"])) {
        //Võid kasutada näitamaks errorit, echo see peale inputi nt
        $username_error = "Enter an username";
      } else {
        // puhastame muutuja võimalikest üleliigsetest sümbolitest
        $username = cleanInput($_POST["username"]);
      }
      if (empty($_POST["password"])) {
        $password_error = "Enter a password";
      } else {
        $password = cleanInput($_POST["password"]);
      }

      if($password_error == "" && $username_error == ""){
        loginUser($username, $password, $yhendus);
      }
}


  ?>


<body onload="play()">

  <!-- pilditeema -->
  <script type="text/javascript">
      var aImages  =  [ "imgs/example/example4.jpg",
                        "imgs/example/example3.jpg",
                        "imgs/example/example2.jpg",
                        "imgs/example/example1.jpg",
                        "imgs/example/example.jpg"];
      var oImage   =  null;
      var iIdx     =  0;
      function play(){
        try{
          //look only once in DOM and cache it
          if(oImage===null){
            oImage  =  window.document.getElementById("iphonesisu");
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

<div class="vasak"> <img src="imgs/example/example2.jpg" alt="" id="iphonesisu" /></div>

<div class="parem">
  <div class="paremlogo"></div>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off">


    <input type="text" name="username" placeholder="Username" class="maininput" value="<?php echo htmlspecialchars($username_error); ?>"><br>

    <input type="password" name="password" placeholder="Password" class="maininput" value="<?php echo htmlspecialchars($password_error); ?>">

    <br>
    <input type="submit" name="login" value="Log In" class="nupukas">

</form>


  <div class="parem2">
    <p>Don't have an account? <a href="index.php">Sign up</a></p>
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
