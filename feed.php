<?php
  require_once('functions2.php');
if(!isset($_SESSION['logged_in_user_name'])) {Header("Location: index.php"); exit();}

$getfeedpics = getfeedpics($yhendus);

$getnotifications = getnotifications($yhendus);

$pilt = $_GET["picname"];
$owner = $_GET["owner"];

$getcomments = getcomments($pilt, $owner, $yhendus);

if (isset($_POST["piccomment"])){
$comment = ($_POST["comment"]);
$pilt = $_GET["picname"];
$owner = $_GET["owner"];
insertcomment($comment, $pilt, $owner, $yhendus);
}


if (isset($_POST["feedcomment"])){
$comment = ($_POST["comment"]);
$pilt = $_POST["picname"];
$owner = $_POST["owner"];
insertcomment($comment, $pilt, $owner, $yhendus);
}

if (isset($_POST["feedlikepic"])){
  $pilt = $_POST["picname"];
  $owner = $_POST["owner"];
likepic($pilt, $owner, $yhendus);
}
?>




<!DOCTYPE html>
<html>
<head>
	<title>peedugram</title>

<meta charset="utf-8">
    <title>instagrami ripoff</title>
    <link rel="stylesheet" type="text/css" href="mainstylesheet.css">
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

<div class="logo"> </div>


<div class="logo2">

</div>

<div class="otsi">
  <input type="text" name="otsi" placeholder="Search" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off">

<script type="text/javascript">

$(document).ready(function($){
  $('#customerAutocomplte').autocomplete({
    source:'suggest_name.php',
    minLength:2,
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

</div>
<div class="nav">

  <div class="explore">

  </div>
  <div class="activity" id="recentact">

  </div>


<a href="main.php"><div class="self"></div></a>

</div>
</div>

<script>
$(function() {
    $( "#recentact" ).click(function() {
        $( "#kast" ).toggle();
        $( "#triangle" ).toggle();
    });
});
</script>

<div id="kast" style="display: none; position: fixed; z-index: 1;">
<div class="triangle"></div>
  <div class="jobunaut">
<?php if (count($getnotifications)==0) {
  echo '<p><b>Recent Activity on your posts</b><br/>
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
</div></div>

<div class="feedpics">
  <?php

  for ($i=0; $i < count($getfeedpics); $i++): ?>
  <?php
  $dir = scandir('u/' . $getfeedpics[$i]->feedowner . '/userpics/profilepic/');
  $dircount = count($dir);

  echo  '<div class="feedpic"> <div class="upper">';

  if ($dircount <= 2)
  {
    echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
     width: 30px; height: 30px;  vertical-align: middle; padding: 13px;">';
  }
  else {
    echo '<img src="u/' . $getfeedpics[$i]->feedowner . '/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
    width: 30px; height: 30px; vertical-align: middle; padding: 13px;">';
  }
  echo '<p><a href="u/' . $getfeedpics[$i]->feedowner .'"> ' . $getfeedpics[$i]->feedowner . '</p></a></div>

  <div class="img"><img src="u/' . $getfeedpics[$i]->feedowner . '/userpics/' . $getfeedpics[$i]->feedpicname . '"</div>


  <div class="comments">';
$pilt = $getfeedpics[$i]->feedpicname;
$owner = $getfeedpics[$i]->feedowner;
?>
<?php
$getcomments = getcomments($pilt, $owner, $yhendus);  
for ($o = 0; $o < count($getcomments); $o++): ?>
  <?php echo '<a href="u/' . $getcomments[$o]->gotcommenter . '" style="color: black;">
  <b><span style="display: inline-block; padding-bottom: 5px; padding-right: 5px; font-size: 18px;">'
  . $getcomments[$o]->gotcommenter . '</b></span></a>'?>
    <?php echo '<span class="lastoftype" style="font-size: 18px;">' . $getcomments[$o]->gotcomment . '</span><br>';?>

<?php endfor;?>


<?php echo '<form action="" method="post">
    <input type="hidden" name="picname" value="' . $getfeedpics[$i]->feedpicname . '">
    <input type="hidden" name="owner" value="'. $getfeedpics[$i]->feedowner . '">
    <input type="submit" name="feedlikepic" value="" class="heart" style="display: inline-block; position: relative;">
  </form>
    <form action="" method="post">
      <input type="text" name="comment" placeholder="Add a comment" style="border: none; width: 50%; margin-left: 40px; position: absolute;
    margin-top: -20px;">
      <input type="hidden" name="picname" value="' . $getfeedpics[$i]->feedpicname . '">
      <input type="hidden" name="owner" value="'. $getfeedpics[$i]->feedowner . '">
      <input type="submit" name="feedcomment" style="display: none;">
    </form>
  </div>
  </div>
  </div>
';
  ?>
<?php endfor; ?>


</div>

<div id="pildikas" class="picoverlay" >
  <div class="popuppildikas">
    <a class="close" href="#">&times;</a>
    <div class="content">


    <div class="bigpic">

      <?php
      $pilt = $_GET["picname"];
	$viewer = $_SESSION['logged_in_user_name'];      
        $owner = $viewer;
       $getcomments = getcomments($pilt, $owner, $yhendus);
      echo '<div class="pic" style="background-image: url(u/' . $owner . '/userpics/' . $pilt . ');"></div>'; ?>
    </div>

    <div class="meta" style="width: 32.5%; height: 100%;
    position: relative; float: right; padding: 15px; ">

    <div class="nimekas" style="border-bottom: 1px solid #cccccc; padding-bottom: 15px; width: 92%;
     position: absolute;">

     <?php
      $dir = scandir('u/' . $viewer . '/userpics/profilepic/');
      $dircount = count($dir);
      if ($dircount <= 2)
      {
        echo '<img src="imgs/tyhi.jpg" style="border-radius: 100%;
         width: 40px; height: 40px; margin: 0 auto; vertical-align: middle;">';
      }
      else {
        echo '<img src="u/' . $viewer . '/userpics/profilepic/profilepic.jpg" style="border-radius: 100%;
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



</body>
</html>
