<?php
  require_once('../../functions2.php');
?>




<?php
$baseuname = getcwd();
$uname = basename($baseuname);
$username = $_SESSION['logged_in_user_name'];
if ($uname == $username) {
  header("Location: ../../main.php");
}
$getinfo = getuserinfo($uname, $yhendus);
$getuserfollowers = getuserfollowers($uname, $yhendus);
$getuserfollowing = getuserfollowing($uname, $yhendus);
$getblockedinfo = getblockedinfo($uname, $yhendus);
$getblockerinfo = getblockerinfo($uname, $yhendus);
$getfollowerinfo = getfollowerinfo($uname, $yhendus);
$getvieweruserfollowing = getvieweruserfollowing($yhendus);
$getvieweruserfollowingarray[] = " ";



$pilt = $_GET["picname"];
$owner = $_GET["owner"];

$getcomments = getcomments($pilt, $owner, $yhendus);
$getpiclikes = getpiclikes($pilt, $owner, $yhendus);
$getnotifications = getnotifications($yhendus);

if (isset($_POST["blockuser"])){
blockuser($uname, $yhendus);
}

if (isset($_POST["follow"])){
  follow($uname, $yhendus);
}

if (isset($_POST["unfollow"])){
  unfollow($uname, $yhendus);
}

if (isset($_POST["unblockuser"])){
  unblockuser($uname, $yhendus);
}

if (isset($_POST["followpopup"])){
  $popupuser = ($_POST["popupuser"]);
  followpopup($popupuser, $yhendus);
}

if (isset($_POST["unfollowpopup"])){
  $popupuser = ($_POST["popupuser"]);
  unfollowpopup($popupuser, $yhendus);
}

if (isset($_POST["piccomment"])){
$comment = ($_POST["comment"]);
$pilt = $_GET["picname"];
$owner = $_GET["owner"];
insertcomment($comment, $pilt, $owner, $yhendus);
}

if (isset($_POST["likepic"])){
$pilt = $_GET["picname"];
$owner = $_GET["owner"];
likepic($pilt, $owner, $yhendus);
}

if (isset($_POST["unlikepic"])){
  $pilt = $_GET["picname"];
  $owner = $_GET["owner"];
  unlikepic($pilt, $owner, $yhendus);
}


?>




<!DOCTYPE html>
<html>
  <head>

    
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="../../mainstylesheet.css">
    <link rel="shortcut icon" href="favicon.ico" />
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
  </head>

<body>

<div class="header" style="position: fixed; z-index: 1;">

<a href="../../feed.php"><div class="logo"> </div>


<div class="logo2">

</div></a>

<div class="otsi">
  <input type="text" name="otsi" placeholder="Search" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off">
</div>


<script type="text/javascript">

$(document).ready(function($){
  $('#customerAutocomplte').autocomplete({
    source:'suggest_name.php', 
    minLength:1,
    select: function(event,ui){
      var code = ui.item.id;
      var username = ui.item.username;
      if(code != '') {
        location.href = 'u/' + username;
      }
    },
                // optional
    html: true, 
    // optional (if other layers overlap the autocomplete list)
    open: function(event, ui) {
      $(".ui-autocomplete").css("z-index", 1000);
    }
  });
});

</script>




<div class="nav">

  <div class="explore">

  </div>
  <div class="activity" id="recentact">

  </div>


<a href="../../main.php"><div class="self">

  </div></a>

</div>
</div>




<div class="bodykas" id="myDIV" style="display: none">

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
  width: 150px; height: 150px; object-fit: cover;">';
}

?>

</a> </div>


  <div class="bodyinf"> <?php echo '<p class="kasutajanimi"> ' .
  $uname . '</p>';?>

  <?php
  if (property_exists($getfollowerinfo, "followertrue")) {
    echo '<form action="#" method="post">
    <input type="submit" name="unfollow" value="Unfollow">
    </form>';
  } else {
    echo '<form action="#" method="post">
    <input type="submit" name="follow" value="Follow">
    </form>';
  }
  ?>



<a href="#popup"><div class="punktikas"></div></a><br>
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
              echo '<p class="stats"><a href="#popup3" class="countpopup"> <b> ' . $countfollowers . ' </b> followers</a> </p>';
            }
            if ($countfollowing == 0) {
              echo '<p class="stats"> <b> ' . $countfollowing . ' </b> following </p>';
            }
            else {
              echo '<p class="stats"><a href="#popup4" class="countpopup"> <b> ' . $countfollowing . ' </b> following</a> </p>';
            }
       ?>
  </div>
</div>

</div>

<div class="pictures" id="blockedpic" style="display: none">

<div class="pics">

  <?php
  $kaust = "userpics/";
  $images = glob($kaust . "*.jpg");

  $baseuname = getcwd();
  $uname = basename($baseuname);

  foreach(array_reverse($images) as $image)
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

<!-- pildi popup window -->
<div id="pildikas" class="picoverlay" >
	<div class="popuppildikas">
    <a class="close" href="./#">&times;</a>
    <div class="content">


    <div class="bigpic">

      <?php
      $pilt = $_GET["picname"];
      $owner = $_GET["owner"];
      echo '<div class="pic" style="background-image: url(../' . $owner . '/userpics/' . $pilt . ');"></div>'; ?>
    </div>

    <div class="meta" style="width: 32.5%; height: 100%;
    position: relative; float: right; padding: 15px; ">

    <div class="nimekas" style="border-bottom: 1px solid #cccccc; padding-bottom: 15px; width: 92%;
     position: absolute;">

     <?php
      $dir = scandir('userpics/profilepic/');
      $dircount = count($dir);
      if ($dircount <= 2)
      {
        echo '<img src="../../imgs/tyhi.jpg" style="border-radius: 100%;
         width: 40px; height: 40px; margin: 0 auto; vertical-align: middle;">';
      }
      else {
        echo '<img src="userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
        width: 40px; height: 40px; margin: 0 auto; vertical-align: middle;">';
      }

      echo
      '<p  style="display: inline-block;
      margin: 0 auto; padding-left: 15px; font-size: 20px;">' . $owner . '</p>';
      ?>
</div>
<div class="kommikas" style="position: absolute; top: 85px;
overflow-wrap: break-word; width: 91%;">
  <?php
  for ($i = 0; $i < count($getcomments); $i++): ?>
  <?php echo '<a href="../' . $getcomments[$i]->gotcommenter . '" style="color: black;">
  <b><span style="display: inline-block; padding-bottom: 5px; padding-right: 5px; font-size: 18px;">'
  . $getcomments[$i]->gotcommenter . '</b></span></a>'?>
    <?php echo '<span style="font-size: 18px;">' . $getcomments[$i]->gotcomment . '</span><br>';?>

<?php endfor;?>

</div>


<div class="fuuter" style="bottom: 40px; position: absolute; border-top: 1px solid #cccccc;
padding: 15px; padding-left: 0px; width: 89%;">


    <div style="display: inline-block; top: 20px; position: absolute;">


       <?php if (property_exists($getpiclikes, "gotpicliker")) {
         echo '<form method="post"><button type="submit" name="unlikepic" type="submit"
         class="heart2"></button></form>';
       }
       else {
         echo '<form method="post"><button type="submit" name="likepic" type="submit"
          class="heart"></button></form>';
       }
       ?>

   </div>



  <form class=""  method="post" style="display: inline-block; margin-left: 35px;
  padding-top: 5px; ">
    <input type="text" name="comment" placeholder="Add a comment" style="border: none; width: 350px;">
    <input type="submit" name=piccomment style="display: none;">
  </form>


</div>



    </div>



  </div>
	</div>
</div>





<!-- following/followers popup -->
<?php for($i = 0; $i < count($getvieweruserfollowing); $i++): ?>
  <?php  $getvieweruserfollowing[$i]->viewerfollowingusername;
  $getvieweruserfollowingarray[]=$getvieweruserfollowing[$i]->viewerfollowingusername?>
<?php endfor;?>


<div id="popup3" class="overlay">
	<div class="popupfollow">
		<a class="close" href="#">&times;</a>
		<div class="content">
<div class="followheader">
        <h3 style="padding: 0px 0px 15px 15px; border-bottom: 1px solid #cccccc; margin-bottom: 8px;">Followers</h3>
</div>

        <?php for($i = 0; $i < count($getuserfollowers); $i++): ?>

        <?php
        $dir = scandir('../../u/'.$getuserfollowers[$i]->followerusername.'/userpics/profilepic/');
        $dircount = count($dir);
        if ($dircount <= 2)
        {
          echo '<a href="../../u/' . $getuserfollowers[$i]->followerusername .'">
          <img src="../../imgs/tyhi.jpg" style="border-radius: 100%;
           width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;">';
        }
        else {
          echo '<a href="../../u/' . $getuserfollowers[$i]->followerusername .'">
          <img src="../../u/'.$getuserfollowers[$i]->followerusername.'/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
          width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;"> </a>';
        } ?>
        <a href="../../u/<?=$getuserfollowers[$i]->followerusername?>" style="text-decoration: none;
          color: inherit;
          padding-left: 20px;
          font-size: 18px;
          margin-top: 0px !important;">
          <?=$getuserfollowers[$i]->followerusername?> </a><br>

          <?php
          $viewer = $_SESSION['logged_in_user_name'];
          if ($getuserfollowers[$i]->followerusername == $viewer)
          {
            echo "";
          }
          else if (in_array($getuserfollowers[$i]->followerusername, $getvieweruserfollowingarray)) {
            echo '<form action="#popup3" method="post">
            <input type="submit" name="unfollowpopup" value="Following" class="followbutton"
            style="background-color: #33cc33; color: white;">
            <input type="hidden" name="popupuser" value= ' . $getuserfollowers[$i]->followerusername . '>
            </form>';
          } else {
            echo '<form action="#popup3" method="post">
            <input type="hidden" name="popupuser" value = ' . $getuserfollowers[$i]->followerusername . '>
            <input type="submit" name="followpopup" value="Follow" class="followbutton"
            style="background-color: white;">
            </form>';
          }
          ?>


        <span style="padding-left: 20px;
        padding-bottom: 5px !important;">
          <?=$getuserfollowers[$i]->followerfullname?></span>
          <br><hr style="margin-bottom: -10px;    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
          <br>


        <?php endfor; ?>

      </div>
	</div>
</div>


<div id="popup4" class="overlay">
	<div class="popupfollow">
		<a class="close" href="#">&times;</a>
		<div class="content">
      <div class="followheader">
        <h3 style="padding: 15px; padding-top: 0px; border-bottom: 1px solid #cccccc; margin-bottom: 8px;">Following</h3>
      </div>



        <?php for($i = 0; $i < count($getuserfollowing); $i++): ?>
          <?php
        $dir = scandir('../../u/'.$getuserfollowing[$i]->followingusername.'/userpics/profilepic/');
        $dircount = count($dir);
        if ($dircount <= 2)
        {
          echo '<a href="../../u/' . $getuserfollowing[$i]->followingusername . '">
          <img src="../../imgs/tyhi.jpg" style="border-radius: 100%;
           width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;"></a>';
        }
        else {
          echo '<a href="../../u/' . $getuserfollowing[$i]->followingusername . '">
          <img src="../../u/'.$getuserfollowing[$i]->followingusername.'/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
          width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;"></a>';
        } ?>
        <a href="../../u/<?=$getuserfollowing[$i]->followingusername?>" style="text-decoration: none;
          color: inherit;
          padding-left: 20px;
          font-size: 18px;
          margin-top: 0px !important;">
          <?=$getuserfollowing[$i]->followingusername?> </a><br>


          <?php
          $viewer = $_SESSION['logged_in_user_name'];
          if ($getuserfollowing[$i]->followingusername == $viewer)
          {
            echo "";
          }
          else if (in_array($getuserfollowing[$i]->followingusername, $getvieweruserfollowingarray)) {
            echo '<form action="#popup4" method="post">
            <input type="hidden" name="popupuser" value = ' . $getuserfollowing[$i]->followingusername . '>
            <input type="submit" name="unfollowpopup" value="Following" class="followbutton"
            style="background-color: #33cc33; color: white;">

            </form>';
          } else {
            echo '<form action="#popup4" method="post">
            <input type="hidden" name="popupuser" value = ' . $getuserfollowing[$i]->followingusername . '>
            <input type="submit" name="followpopup" value="Follow" class="followbutton"
            style="background-color: white;">

            </form>';
          }
          ?>

        <span style="padding-left: 20px;">
          <?=$getuserfollowing[$i]->followingfullname?></span>
          <br><hr style="margin-bottom: -10px;    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
          <br>


        <?php endfor; ?>

      </div>
	</div>
</div>


<div class="triangle" id="triangle" style="display: none;"></div>
<div id="kast" style="display: none; position: fixed; z-index: 1;">

  <div class="jobunaut">
<?php if (count($getnotifications)==0) {
  echo '<p><b>Recent Activity on your posts</b><br />
When someone comments on or likes ones of your photos, youll see it here.
</p>';
}
else {
  $viewer = $_SESSION['logged_in_user_name'];
  $tyhi = '<img src="../../imgs/tyhi.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;">';
  echo '<div class="notiflist">';
  for ($i=0; $i < count($getnotifications); $i++):

  if ($getnotifications[$i]->gottype == "like") {
    $dir = scandir('../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/');
    $dircount = count($dir);

    echo '<a href="../' . $getnotifications[$i]->gotnotifier . '">';
    if ($dircount <= 2) {
      echo $tyhi;}
      else {
        echo '<img src="../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
      }
    echo '<span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
    <p> liked your photo. </p>
    <a href="?owner=' . $viewer . '&picname=' . $getnotifications[$i]->gotpicturename . '#pildikas">
    <img src="../' . $viewer .'/userpics/' . $getnotifications[$i]->gotpicturename . ' " class="yourpic"/></a><hr>';
  }
  elseif ($getnotifications[$i]->gottype == "comment") {
    $dir = scandir('../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/');
    $dircount = count($dir);
    echo '<a href="../' . $getnotifications[$i]->gotnotifier . '">';
    if ($dircount <= 2) {
      echo $tyhi;}
      else {
        echo '<img src="../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
      }
    echo '<span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
    <p> commented on your photo. </p>
    <a href="?owner=' . $viewer . '&picname=' . $getnotifications[$i]->gotpicturename . '#pildikas">
    <img src="../' . $viewer .'/userpics/' . $getnotifications[$i]->gotpicturename . ' " class="yourpic"/></a><hr>';
  }
  else {
    $dir = scandir('../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/');
    $dircount = count($dir);
    echo '<a href="../' . $getnotifications[$i]->gotnotifier . '">';
    if ($dircount <= 2) {
      echo $tyhi;}
    else {
        echo '<img src="../' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
    }
    echo '<span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
    <p> started following you. </p><hr>';
  }
  endfor;
  echo '</div></div></div>';
}
?>




<script>
$(function() {
    $( "#recentact" ).click(function() {
        $( "#kast" ).toggle();
        $( "#triangle" ).toggle();
    });
});
</script>




<div id="popup" class="overlay">
	<div class="popup">
		<a class="close" href="#">&times;</a>
		<div class="content">
      <div>
        <a href="#"><input type="button" value="Report user" class="logout"></a><br>

        <?php
        if (property_exists($getblockerinfo, "blocker")) {
          echo '<form action="#" method="post">
          <input type="submit" name="unblockuser" class="logout" value="Unblock this user" >
          </form>';
        } else {
          echo '<form action="#" method="post">
            <input type="submit" name="blockuser" value="Block this user" class="logout">
          </form>';
        }
        ?>


        <a href="#"><input type="button" value="Cancel" class="logout"></a>
      </div>
      </div>
	</div>
</div>


<div id="popup2" class="overlay">
	<div class="popup">
		<a class="close" href="#">&times;</a>
		<div class="content">
      <div>
        <input type="button" value="Change Profile Picture" class="logout" disabled>



        <a href="#"><input type="button" value="Cancel" class="logout"></a>

      </div>
      </div>
	</div>
</div>

<?php $blockedarray = array(); ?>
<?php for($i = 0; $i < count($getblockedinfo); $i++):?>
  <?php  $getblockedinfo[$i]->blockedusername;
  $blockedarray[]=$getblockedinfo[$i]->blockedusername?>
<?php endfor;?>

<?php
$viewer = $_SESSION['logged_in_user_name'];
if (!in_array($viewer, $blockedarray)) {
      echo '
<script type="text/javascript">
document.getElementById("myDIV").style.display = "block";
document.getElementById("blockedpic").style.display = "block";
</script>';}
else {
  echo '<div style="position: absolute; text-align: center; margin: auto;
    top: 20%;
    right: 0;
    bottom: 0;
    left: 0;"><h2>Sorry, this page isnt available.</h2>
  <h4>The link you followed may be broken, or the page may have been removed.
  <a href="../../main.php" style="text-decoration: none; color:gray;"> Go back to Instagram.</a></h4></div>';
}
    ?>

<?php for($i = 0; $i < count($getvieweruserfollowing); $i++): ?>
  <?php  $getvieweruserfollowing[$i]->viewerfollowingusername;
  $getvieweruserfollowingarray[]=$getvieweruserfollowing[$i]->viewerfollowingusername?>
<?php endfor;?>

</body>


</html>
