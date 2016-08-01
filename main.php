<?php
  require_once('functions.php');


if(!isset($_SESSION['logged_in_user_name'])) {Header("Location: index.php"); exit();}
?>

<?php
  $getvieweruserfollowing = getvieweruserfollowing($yhendus);
for($i = 0; $i < count($getvieweruserfollowing); $i++): ?>
  <?php  $getvieweruserfollowing[$i]->viewerfollowingusername;
  $getvieweruserfollowingarray[]=$getvieweruserfollowing[$i]->viewerfollowingusername?>
<?php endfor;?>

<?php
$getuserfollowers = getuserfollowers($yhendus);
$getuserfollowing = getuserfollowing($yhendus);
$mainuserinfo = getmainuserinfo($yhendus);
$getpiclikes = getpiclikes($pilt, $owner, $yhendus);

$pilt = $_GET["picname"];
$owner = $_GET["owner"];
$getcomments = getcomments($pilt, $owner, $yhendus);

$getvieweruserfollowingarray[] = " ";


$getnotifications = getnotifications($yhendus);

if (isset($_POST["piccomment"])){
$comment = ($_POST["comment"]);
$pilt = $_GET["picname"];
$owner = $_GET["owner"];
insertcomment($comment, $pilt, $owner, $yhendus);
}

if (isset($_POST["unfollowpopup"])) {
$popupuser = ($_POST["popupuser"]);
unfollowpopup($popupuser, $yhendus);
}

if (isset($_POST["followpopup"])) {
$popupuser = ($_POST["popupuser"]);
followpopup($popupuser, $yhendus);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$picname = ($_POST["picname"]);}
if (isset($picname)){
insertpic($picname, $yhendus);
}
?>



<!DOCTYPE html>
<html>
  <head>
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <meta charset="utf-8">
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="mainstylesheet.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  </head>

<body>
<div class="header" style="position: fixed; z-index: 1;">

<a href="feed.php"><div class="logo"> </div>


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



<a href="#popupupload">Upload</a>

<div id="popupupload" class="overlay">
	<div class="popupupload">
		<a class="close" href="#">&times;</a>
		<div class="content">
      <div>
        <form action="upload2.php" method="post" enctype="multipart/form-data">

<input type='file' onchange="readURL(this);" name="fileToUpload" id="fileToUpload"/>
<input type="submit" value="Upload Image" name="submitpicture">

        </form>

          <img id="blah" src="#" alt="your image" style="object-fit: contain;"/>

      <script type="text/javascript">
      function readURL(input) {
           if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                   $('#blah')
                       .attr('src', e.target.result)
                       .width(500)
                       .height(300);
};

               reader.readAsDataURL(input.files[0]);
           }
       }
      </script>

      </div>
      </div>
	</div>
</div>


<div class="nav">

  <div class="explore">

  </div>
  <div class="activity" id="recentact">

  </div>



  <div class="self">

  </div>
</div>
</div>


<div class="bodykas">





  <div class="bodypilt"> <a href="#popup2">

  <?php
$path = $_SESSION['logged_in_user_name'];
$dir = scandir('u/'.$path.'/userpics/profilepic/');

$dircount = count($dir);
if ($dircount <= 2)
{
  echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
   width: 150px; height: 150px;">';
}
else {
  echo '<img src="u/'.$path.'/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
  width: 150px; height: 150px; object-fit: cover;">';
}

?>

</a></a> </div>


  <div class="bodyinf"> <?php echo '<p class="kasutajanimi"> ' .
  ($_SESSION['logged_in_user_name']) . '</p>';?>
<a href="#">Edit Profile</a>
<a href="#popup"><div class="punktikas"></div></a><br>
  <div>
    <?php
    $countfollowers = count($getuserfollowers);
    $countfollowing = count($getuserfollowing);
    $username = $_SESSION['logged_in_user_name'];
    $kaust = 'u/' . $username . '/userpics/';
    $images = glob($kaust . "*.jpg");
            echo '<p class="smallname"> ' . $mainuserinfo->mainfullname . ' <span style="font-weight: normal;"> ' . $mainuserinfo->mainbio . ' </span></p><br><br>';

            echo '<p class="stats"> <b> ' . count($images) .' </b> posts </p>';
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

<div class="pictures">
  <div class="pics">


  <?php
  $username = $_SESSION['logged_in_user_name'];
  $kaust = 'u/' . $username . '/userpics/';
  $images = glob($kaust . "*.jpg");


  foreach(array_reverse($images) as $image)
  {
    $imagen = basename($image);
    $getpiclikecount = getpiclikecount($imagen, $yhendus);
    $getpiccommcount = getpiccommcount($imagen, $yhendus);
    echo '<a href="?owner=' . $username . '&picname=' . $imagen . '#pildikas"><div class="pildid"
    style="background-image: url(' . $image . ')";><div class="picstats">
    <div class="hoverlikes"><span>' . $getpiclikecount . '</span></div>
    <div class="hovercomments"><span> ' . $getpiccommcount .' </span></div>
    </div><div class="piltoverlay">
    </div></div></a>';
  }


  ?>
  </div>
</div>


<!-- following/followers popup -->
<?php for($i = 0; $i < count($getvieweruserfollowing); $i++): ?>
  <?php  $getvieweruserfollowing[$i]->viewerfollowingusername;
  $getvieweruserfollowingarray[]=$getvieweruserfollowing[$i]->viewerfollowingusername?>
<?php endfor;?>



<div id="popup3" class="overlay" >
	<div class="popupfollow">

		<div class="content">

      <div class="followheader">
        <h3 style="padding: 0px 0px 15px 15px; border-bottom: 1px solid #cccccc; margin-bottom: 10px;">Followers</h3>
        <a class="close" href="#">&times;</a>
      </div>



        <?php for($i = 0; $i < count($getuserfollowers); $i++): ?>

        <?php
        $dir = scandir('u/'.$getuserfollowers[$i]->followerusername.'/userpics/profilepic/');
        $dircount = count($dir);
        if ($dircount <= 2)
        {
          echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
           width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;">';
        }
        else {
          echo '<img src="u/'.$getuserfollowers[$i]->followerusername.'/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
          width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;">';
        } ?>
        <a href="u/<?=$getuserfollowers[$i]->followerusername?>" style="text-decoration: none;
          color: inherit;
          padding-left: 20px;
          font-size: 18px;
          margin-top: 0px !important;">
          <?=$getuserfollowers[$i]->followerusername?> </a><br>

          <?php
          if (in_array($getuserfollowers[$i]->followerusername, $getvieweruserfollowingarray)) {
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
    <div class="content">
        <div class="followheader">
          <h3 style="padding: 0px 0px 15px 15px; border-bottom: 1px solid #cccccc; margin-bottom: 10px;">Following</h3>
          <a class="close" href="#">&times;</a>
        </div>


        <?php for($i = 0; $i < count($getuserfollowing); $i++): ?>

        <?php
        $dir = scandir('u/'.$getuserfollowing[$i]->followingusername.'/userpics/profilepic/');
        $dircount = count($dir);
        if ($dircount <= 2)
        {
          echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
           width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;">';
        }
        else {
          echo '<img src="u/'.$getuserfollowing[$i]->followingusername.'/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
          width: 30px; height: 30px; float: left; margin-left: 20px; margin-top: 5px;">';
        } ?>
        <a href="u/<?=$getuserfollowing[$i]->followingusername?>" style="text-decoration: none;
          color: inherit;
          padding-left: 20px;
          font-size: 18px;
          margin-top: 0px !important;">
          <?=$getuserfollowing[$i]->followingusername?> </a><br>


            <form action="#popup4" method="post">
            <input type="hidden" name="popupuser" value="<?=$getuserfollowing[$i]->followingusername;?>">
            <input type="submit" name="unfollowpopup" value="Following" class="followbutton"
            style="background-color: #33cc33; color: white;">
            </form>



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




<div id="popup" class="overlay">
	<div class="popup">
		<a class="close" href="#">&times;</a>
		<div class="content">
      <div>
        <a href="?logout"><input type="button" value="Log out" class="logout"></a><br>

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

        <form action="upload.php" method="post" enctype="multipart/form-data">

            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>

        <a href="#"><input type="button" value="Cancel" class="logout"></a>

      </div>
      </div>
	</div>
</div>



<div id="kast" style="display: none; position:fixed; z-index: 1;">
<div class="triangle"></div>
  <div class="jobunaut">
<?php if (count($getnotifications)==0) {
  echo '<p><b>Recent Activity on your posts</b><br />
When someone comments on or likes ones of your photos, youll see it here.
</p>';
}
else {
  $viewer = $_SESSION['logged_in_user_name'];
  $tyhi = '<img src="imgs/tyhi.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;">';
  echo '<div class="notiflist">';
  for ($i=0; $i < count($getnotifications); $i++):

  if ($getnotifications[$i]->gottype == "like") {
    $dir = scandir('u/'.$getnotifications[$i]->gotnotifier.'/userpics/profilepic/');
    $dircount = count($dir);
    echo '<a href="u/' . $getnotifications[$i]->gotnotifier . '">';

      if ($dircount <= 2) {
        echo $tyhi;
      }
      else {
        echo '<img src="u/' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
      }
    echo '<span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
    <p> liked your photo. </p>
    <a href="?owner=' . $viewer . '&picname=' . $getnotifications[$i]->gotpicturename . '#pildikas">
    <img src="u/' . $viewer .'/userpics/' . $getnotifications[$i]->gotpicturename . ' " class="yourpic"/></a><hr>';
  }
  elseif ($getnotifications[$i]->gottype == "comment") {
    $dir = scandir('u/'.$getnotifications[$i]->gotnotifier.'/userpics/profilepic/');
    $dircount = count($dir);
    echo '<a href="u/' . $getnotifications[$i]->gotnotifier . '">';
    if ($dircount <= 2) {
      echo $tyhi;}
      else {
        echo '<img src="u/' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
      }
    echo '<span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
    <p> commented on your photo. </p>
<a href="?owner=' . $viewer . '&picname=' . $getnotifications[$i]->gotpicturename . '#pildikas">
    <img src="u/' . $viewer .'/userpics/' . $getnotifications[$i]->gotpicturename . ' " class="yourpic"/></a><hr>';
  }

  else {
    $dir = scandir('u/' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/');
    $dircount = count($dir);
    echo '
    <a href="u/' . $getnotifications[$i]->gotnotifier . ' ">';
    if ($dircount <= 2) {
      echo $tyhi;}
    else {
      echo '<img src="u/' . $getnotifications[$i]->gotnotifier . '/userpics/profilepic/profilepic.jpg" class="profpilt" style="width: 30px; height: 30px; border-radius: 100%;"/>';
    }
    echo '
    <span class="notifier"> ' . $getnotifications[$i]->gotnotifier . '</span></a>
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
    });
});
</script>
<div class="triangle" id="triangle" style="display: none;"></div>
<div id="kast" style="display: none; position: fixed;">

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

</div></div>

<div id="pildikas" class="picoverlay" >
	<div class="popuppildikas">
    <a class="close" href="">&times;</a>
    <div class="content">


    <div class="bigpic">

      <?php
      $pilt = $_GET["picname"];
      $owner = $_GET["owner"];
      echo '<div class="pic" style="background-image: url(u/' . $owner . '/userpics/' . $pilt . ');"></div>'; ?>
    </div>

    <div class="meta" style="width: 32.5%; height: 100%;
    position: relative; float: right; padding: 15px; ">

    <div class="nimekas" style="border-bottom: 1px solid #cccccc; padding-bottom: 15px; width: 92%;
     position: absolute;">

     <?php
      $dir = scandir('u/' . $owner . '/userpics/profilepic/');
      $dircount = count($dir);
      if ($dircount <= 2)
      {
        echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
         width: 40px; height: 40px; margin: 0 auto; vertical-align: middle;">';
      }
      else {
        echo '<img src="u/' . $owner . '/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
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
  <?php echo '<a href="../u/' . $getcomments[$i]->gotcommenter . '" style="color: black;">
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

</body>


</html>
