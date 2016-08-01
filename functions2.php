<?php
require_once('config.php');
//error_reporting(~E_NOTICE);
?>




<?php
  $database = "vhost50495s1";
  session_start();
  $yhendus = new mysqli("codeloops.ee", "vhost50495s1", "eeterpeedu", "vhost50495s1");

  function getuserfollowers($uname, $yhendus){
  $username = $uname;
  $stmt = $yhendus->prepare("SELECT username, fullname FROM user WHERE id_user IN(SELECT follower
  FROM following WHERE followee = (SELECT id_user FROM user WHERE username = '$username'))");
  $stmt->bind_result($followerusername, $followerfullname);
  $stmt->execute();

  $array = array();
  while ($stmt->fetch()) {
  $userfollowers = new StdClass();
  $userfollowers->followerusername = $followerusername;
  $userfollowers->followerfullname = $followerfullname;
  array_push($array, $userfollowers);
  }
  return $array;
  $stmt->close();
  }


  function getuserfollowing($uname, $yhendus){
  $stmt = $yhendus->prepare("SELECT username, fullname FROM user WHERE id_user IN(SELECT followee
  FROM following WHERE follower = (SELECT id_user FROM user WHERE username = '$uname'))");
  $stmt->bind_result($followingusername, $followingfullname);
  $stmt->execute();

  $array = array();
  while ($stmt->fetch()) {
  $userfollowers = new StdClass();
  $userfollowers->followingusername = $followingusername;
  $userfollowers->followingfullname = $followingfullname;
  array_push($array, $userfollowers);
  }
  return $array;
  $stmt->close();
  }

  function getvieweruserfollowing($yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("SELECT username FROM user WHERE id_user IN(SELECT followee
  FROM following WHERE follower = (SELECT id_user FROM user WHERE username = '$viewer'))");
  $stmt->bind_result($viewerfollowingusername);
  $stmt->execute();

  $array = array();
  while ($stmt->fetch()) {
  $vieweruserfollowers = new StdClass();
  $vieweruserfollowers->viewerfollowingusername = $viewerfollowingusername;
  array_push($array, $vieweruserfollowers);
  }
  return $array;
  $stmt->close();
  }

  function getfollowerinfo($uname, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("SELECT follower from following where follower =
  (select id_user from user where username = '$viewer') and followee = (select id_user from user where username = '$uname')");
  $stmt->bind_result($followertrue);
  $stmt->execute();
  $followerinfo = new StdClass();
  if ($stmt->fetch()) {
    $followerinfo->followertrue = $followertrue;
    }
  return $followerinfo;
  $stmt->close();
  }


function getuserinfo($uname, $yhendus){
  $username = $uname;
  $stmt = $yhendus->prepare("SELECT fullname, bio FROM user WHERE username = '$username'");
  $stmt->bind_result($fullname, $bio);
  $stmt->execute();

  $user = new StdClass();
  if($stmt->fetch()) {
      $user->fullname = $fullname;
      $user->bio = $bio;
    }
  return $user;
  $stmt->close();
  }

// kasutajablokitud
  function getblockedinfo($uname, $yhendus){
    $stmt = $yhendus->prepare("SELECT username FROM user WHERE id_user in (SELECT blocked
    FROM blockedusers WHERE blocker = (SELECT id_user FROM user WHERE username = '$uname'))");
    $stmt->bind_result($blockedusername);
    $stmt->execute();

    $array = array();
    while ($stmt->fetch()) {
    $userblocked = new StdClass();
    $userblocked->blockedusername = $blockedusername;
    array_push($array, $userblocked);
    }
    return $array;
    $stmt->close();
}

function getblockerinfo($uname, $yhendus){
$viewer = $_SESSION['logged_in_user_name'];
$stmt = $yhendus->prepare("SELECT blocker from blockedusers where blocker =
(select id_user from user where username = '$viewer') and blocked = (select id_user from user where username = '$uname')");
$stmt->bind_result($blocker);
$stmt->execute();
$blockerinfo = new StdClass();
if ($stmt->fetch()) {
  $blockerinfo->blocker = $blocker;
  }
return $blockerinfo;
$stmt->close();
}

  function blockuser($uname, $yhendus){
      $viewer = $_SESSION['logged_in_user_name'];
      $stmt = $yhendus->prepare("INSERT INTO blockedusers (blocker)
      SELECT id_user FROM user where username = '$viewer'");
      $stmt2 = $yhendus->prepare("UPDATE blockedusers SET blocked = (SELECT id_user FROM user
      where username = '$uname') WHERE blocked IS NULL");
      $stmt3 = $yhendus->prepare("DELETE from following where follower = (SELECT id_user from user
      where username = '$viewer') and followee = (select id_user from user where username = '$uname')
      OR follower = (select id_user from user where username = '$uname') and followee = (SELECT id_user from user
      where username = '$viewer')");
      $stmt->execute();
      $stmt->close();
      $stmt2->execute();
      $stmt2->close();
      $stmt3->execute();
      $stmt3->close();
      header("Refresh:0");
  }
function unblockuser($uname, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("DELETE from blockedusers where blocker = (SELECT id_user from user
  where username = '$viewer') and blocked = (select id_user from user where username = '$uname')");
  $stmt->execute();
  $stmt->close();
  header("Refresh:0");
}

function follow($uname, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("INSERT INTO following (follower)
  SELECT id_user FROM user where username = '$viewer'");
  $stmt2 = $yhendus->prepare("UPDATE following SET followee = (SELECT id_user FROM user
  where username = '$uname') WHERE followee IS NULL");
  $stmt->execute();
  $stmt->close();
  $stmt2->execute();
  $stmt2->close();
  $stmt3 = $yhendus->prepare("INSERT INTO notifications (notifier, notified) values('$viewer', '$uname')");
  $stmt3->execute();
  $stmt3->close();
  header("Refresh:0");
}

function unfollow($uname, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("DELETE FROM following where follower = (select id_user from user
  where username = '$viewer') and followee = (select id_user from user where username = '$uname')");
  $stmt->execute();
  $stmt->close();
  $stmt2 = $yhendus->prepare("DELETE FROM notifications where notifier = '$viewer' and notified= '$uname' and type IS NULL");
  $stmt2->execute();
  $stmt2->close();
  header("Refresh:0");
}

function followpopup($popupuser, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("INSERT INTO following (follower)
  SELECT id_user FROM user where username = '$viewer'");
  $stmt2 = $yhendus->prepare("UPDATE following SET followee = (SELECT id_user FROM user
  where username = '$popupuser') WHERE followee IS NULL");
  $stmt->execute();
  $stmt->close();
  $stmt2->execute();
  $stmt2->close();
  $stmt3 = $yhendus->prepare("INSERT INTO notifications (notifier, notified) values('$viewer', '$popupuser')");
  $stmt3->execute();
  $stmt3->close();
  header("Refresh:0");
}

function unfollowpopup($popupuser, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("DELETE from following where follower = (select id_user from user
  where username = '$viewer') and followee = (select id_user from user where username = '$popupuser')");
  $stmt->execute();
  $stmt->close();
  $stmt2 = $yhendus->prepare("DELETE FROM notifications where notifier = '$viewer' and notified= '$popupuser' and type IS NULL");
  $stmt2->execute();
  $stmt2->close();
  header("Refresh:0");
}


//pildi kommenteerimine
function insertcomment($comment, $pilt, $owner, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("INSERT INTO piccomms(picname, picowner, commenter, comment) values(?,?,?,?)");
  $stmt->bind_param("ssss", $pilt, $owner, $viewer, $comment);
  $stmt->execute();
  $stmt->close();
  $stmt2 = $yhendus->prepare("INSERT INTO notifications(notifier, notified, picturename, type) values(?,?,?, 'comment')");
  $stmt2->bind_param("sss", $viewer, $owner, $pilt);
  $stmt2->execute();
  $stmt2->close();
  header("Refresh:0");
}

function getcomments($pilt, $owner, $yhendus){
  $stmt = $yhendus->prepare("SELECT commenter, comment from piccomms where picowner = '$owner' and picname = '$pilt'");
  $stmt->bind_result($gotcommenter, $gotcomment);
$stmt->execute();
$array = array();
while ($stmt->fetch()) {
$piccomments = new StdClass();
$piccomments->gotcommenter = $gotcommenter;
$piccomments->gotcomment = $gotcomment;
array_push($array, $piccomments);}
return $array;
$stmt->close();
}

function getnotifications($yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("SELECT notifier, picturename, type from notifications where notified = '$viewer' and notifier NOT LIKE '$viewer' order by kuup DESC");
  $stmt->bind_result($gotnotifier, $gotpicturename, $gottype);
  $stmt->execute();
  $array = array();
  while ($stmt->fetch()) {
  $gotnotifications = new StdClass();
  $gotnotifications->gotnotifier = $gotnotifier;
  $gotnotifications->gotpicturename = $gotpicturename;
  $gotnotifications->gottype = $gottype;
  array_push($array, $gotnotifications);}
  return $array;
  $stmt->close();
  }




// pildi likemene
function likepic($pilt, $owner, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("INSERT INTO piclikes(liker, owner, picname) values(?,?,?)");
  $stmt->bind_param("sss", $viewer, $owner, $pilt);
  $stmt->execute();
  $stmt->close();
  $stmt2 = $yhendus->prepare("INSERT INTO notifications(notifier, notified, picturename, type)values(?,?,?, 'like')");
  $stmt2->bind_param("sss", $viewer, $owner, $pilt);
  $stmt2->execute();
  $stmt2->close();
  header("Refresh:0");
}

function getpiclikes($pilt, $owner, $yhendus){
$viewer = $_SESSION['logged_in_user_name'];
$stmt = $yhendus->prepare("SELECT liker from piclikes where liker = '$viewer' and owner = '$owner' and picname = '$pilt'");
$stmt->bind_result($gotpicliker);
$stmt->execute();
$piclikes = new StdClass();
if ($stmt->fetch()) {
    $piclikes->gotpicliker = $gotpicliker;
    }
return $piclikes;
$stmt->close();
}

function getpiclikecount($imagen, $uname, $yhendus){
  $stmt = $yhendus->prepare("SELECT COUNT(*) from piclikes where owner = '$uname' and
  picname = '$imagen'");
  $stmt->bind_result($gotpiclikes);
  $stmt->execute();
  $gotlikes = new StdClass();
  if ($stmt->fetch()) {
  $gotlikes->gotpiclikes = $gotpiclikes;}
  return $gotlikes->gotpiclikes;
  $stmt->close();
}

function getpiccommcount($imagen, $uname, $yhendus){
  $stmt = $yhendus->prepare("SELECT COUNT(*) from piccomms where picowner = '$uname' and
  picname = '$imagen'");
  $stmt->bind_result($gotpiccomms);
  $stmt->execute();
  $gotcomms = new StdClass();
  if ($stmt->fetch()) {
  $gotcomms->gotpiccomms = $gotpiccomms;}
  return $gotcomms->gotpiccomms;
  $stmt->close();
  }



function unlikepic($pilt, $owner, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("DELETE from piclikes where liker = '$viewer' and picname = '$pilt' and owner = '$owner'");
  $stmt->execute();
  $stmt->close();
  $stmt2 = $yhendus->prepare("DELETE FROM notifications where notifier = '$viewer' and notified = '$owner' and picturename = '$pilt' and type = 'like'");
  $stmt2->execute();
  $stmt2->close();
  header("Refresh:0");
  }


  // PILDIFEEDISHIT
function getfeedpics($yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("SELECT picname, owner FROM pictures where owner in (select username from user where id_user in (select 
    followee from following where follower = (select id_user from user where username = '$viewer'))) ORDER BY aeg DESC");
  $stmt->bind_result($feedpicname, $feedowner);
  $stmt->execute();
  $array = array();
  while ($stmt->fetch()) {
    $gotfeed = new StdClass();
    $gotfeed->feedpicname = $feedpicname;
    $gotfeed->feedowner = $feedowner;
    array_push($array, $gotfeed);}
    return $array;
    $stmt->close();
}




//v2ljalogimine

if(isset($_GET['logout'])) { session_destroy(); header('location: index.php'); }

  ?>
