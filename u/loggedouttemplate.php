<?php
  require_once('../../functions2.php');
?>

<?php
//if(!isset($_SESSION['logged_in_user_name'])) {Header("Location: index.php"); exit();}
?>

<?php
$baseuname = getcwd();
$uname = basename($baseuname);


$getinfo = getuserinfo($uname, $yhendus);
$getuserfollowers = getuserfollowers($uname, $yhendus);
$getuserfollowing = getuserfollowing($uname, $yhendus);
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="../../mainstylesheet.css">
  </head>

<body>
  <div class="header">

  <a href="../../index.php"> <div class="logo"> </div>


  <div class="logo2">

  </div></a>

  <div class="otsi">
    <input type="text" name="otsi" placeholder="Search">
  </div>
  <div class="nav">

<div style="margin-top: -20px;">
  <a href="#popup"><p style="display: inline-block; padding: 5px 18px 5px 18px;
  color: white; margin-right: 25px; border-radius: 20px;
  background-color: #0080ff;">
    Get the app
  </p></a>
  <h4 style="display: inline-block;"><a href="../../index.php" style="text-decoration: none; color: black;">Sign up</a> /
    <a href="../../login.php" style="text-decoration: none; color: black;">Log in</a></h4>
</div>

<!-- get the app popup -->

<div id="popup" class="overlay">
	<div class="popupfollow" style="width: 415px; min-height: 20px; height: 205px; position: absolute;
  margin: auto; top: 0; right: 0; bottom: 0; left: 0; overflow: hidden; border-radius: 5px;">
		<a class="close" href="#" style="top: 34%; right: 36%; color: gray;">&times;</a>
		<div class="content" style="text-align: center;">
<h3 style="padding-top: 7%; margin-bottom: 25px;">
  Experience the best version of <br>Instagram by getting the app
</h3>
<div class="app">

    <a href="https://itunes.apple.com/app/instagram/id389801252?pt=428156&ct=igweb.unifiedHome.badge&mt=8" target="_blank" ><img src="../../imgs/appstore.png"  id="appstore" /></a>

  <a href="https://play.google.com/store/apps/details?id=com.instagram.android&referrer=utm_source%3Dinstagramweb%26utm_campaign%3DunifiedHome%26utm_medium%3Dbadge" target="blank" ><img src="../../imgs/playstore.png" id="playstore" /></a>
</div>



      </div>
	</div>
</div>




  </div>
  </div>

<div class="bodykas">


  <div class="bodypilt">

  <?php

$dir = scandir('userpics/profilepic/');

$dircount = count($dir);
if ($dircount <= 2)
{
  echo '<img src="../../imgs/tyhi.jpg" style="border-radius: 100%;
   width: 150px; height: 150px;">';
}
else {
  echo '<img src="userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
  width: 150px; height: 150px;">';
}

?>

</a> </div>


  <div class="bodyinf"> <?php echo '<p class="kasutajanimi"> ' .
  $uname . '</p>';?>
<a href="#">Follow</a>
<br>
  <div>
    <?php
    $countfollowers = count($getuserfollowers);
    $countfollowing = count($getuserfollowing);
    $kaust = "userpics/";
    $images = glob($kaust . "*.jpg");

            echo '<p class="smallname"> ' . $getinfo->fullname . ' <span style="font-weight: normal;"> ' . $getinfo->bio . ' </span></p><br><br>';
            echo '<p class="stats"> <b> ' . count($images) . ' </b> posts </p>';
            if ($countfollowers == 0) {
              echo '<p class="stats"> <b> ' . $countfollowers . ' </b> followers </p>';
            }
            else {
              echo '<p class="stats"> <b> ' . $countfollowers . ' </b> followers</p>';
            }
            if ($countfollowing == 0) {
              echo '<p class="stats"> <b> ' . $countfollowing . ' </b> following </p>';
            }
            else {
              echo '<p class="stats"> <b> ' . $countfollowing . ' </b> following </p>';
            }
       ?>
  </div>
</div>

</div>

<div class="pictures">

<div class="pics">

  <?php
  $kaust = "userpics/";
  $images = glob($kaust . "*.jpg");

  $baseuname = getcwd();
  $uname = basename($baseuname);

  foreach($images as $image)
  {
    $imagen = basename($image);
    $getpiclikecount = getpiclikecount($imagen, $uname, $yhendus);
    $getpiccommcount = getpiccommcount($imagen, $uname, $yhendus);
    echo '<a href="?owner=' . $uname . '&picname=' . $imagen . '#pildikas"><div class="pildid"
    style="background-image: url(' . $image . ')";><div class="picstats">
    <div class="hoverlikes"><span>' . $getpiclikecount . '</span></div>
    <div class="hovercomments"><span> ' . $getpiccommcount .' </span></div>
    </div><div class="piltoverlay">
    </div></div></a>';
  }

  ?>

</div>
</div>

</body>


</html>
