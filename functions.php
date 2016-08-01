<?php
require_once('config.php');
error_reporting(~E_NOTICE); ?>



<?php
  $database = "vhost50495s1";


	session_start();

	 $yhendus = new mysqli("codeloops.ee", "vhost50495s1", "eeterpeedu", "vhost50495s1");


  function getusernames($yhendus){
    $stmt = $yhendus->prepare("SELECT username FROM user");
    $stmt->bind_result($registeredusername);
    $stmt->execute();

    $array = array();
    while ($stmt->fetch()) {
    $registeredusernames = new StdClass();
    $registeredusernames->registeredusername = $registeredusername;
    array_push($array, $registeredusernames);
    }
    return $array;
    $stmt->close();
    }



  function uususer($uusemail, $uusfullname, $uususername, $uuspassword, $yhendus) {
		$stmt = $yhendus->prepare("INSERT INTO user (email, fullname, username, password) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss", $uusemail, $uusfullname, $uususername, $uuspassword);
		$stmt->execute();
    $stmt->close();
    //header('Location: login.php');
  }


  function getmainuserinfo($yhendus){
      $username = $_SESSION['logged_in_user_name'];
      $stmt = $yhendus->prepare("SELECT id_user, fullname, bio FROM user WHERE username = '$username'");
      $stmt->bind_result($mainuserid_user, $mainfullname, $mainbio);
      $stmt->execute();

  $mainuser = new StdClass();
  if($stmt->fetch()) {
      $mainuser->mainfullname = $mainfullname;
      $mainuser->mainuserid_user = $mainuserid_user;
      $mainuser->mainbio = $mainbio;
    }
  return $mainuser;
  $stmt->close();

  }


function getuserfollowers($yhendus){
$username = $_SESSION['logged_in_user_name'];
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


function getuserfollowing($yhendus){
$username = $_SESSION['logged_in_user_name'];
$stmt = $yhendus->prepare("SELECT username, fullname FROM user WHERE id_user IN(SELECT followee
FROM following WHERE follower = (SELECT id_user FROM user WHERE username = '$username'))");
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

function unfollowpopup($popupuser, $yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("DELETE from following where follower = (select id_user from user
  where username = '$viewer') and followee = (select id_user from user where username = '$popupuser')");
  $stmt->execute();
  $stmt->close();
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
  header("Refresh:0");
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

function insertpic($picname, $yhendus){
$viewer = $_SESSION['logged_in_user_name'];
$stmt = $yhendus->prepare("INSERT INTO pictures(picname, owner) values (?,?)");
$stmt->bind_param("ss", $picname, $viewer);
$stmt->execute();
$stmt->close();
}

function getnotifications($yhendus){
  $viewer = $_SESSION['logged_in_user_name'];
  $stmt = $yhendus->prepare("SELECT notifier, picturename, type from notifications where notified = '$viewer' and notifier not like '$viewer' order by kuup DESC");
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


  function getpiclikecount($imagen, $yhendus){
    $viewer = $_SESSION['logged_in_user_name'];
    $stmt = $yhendus->prepare("SELECT COUNT(*) from piclikes where owner = '$viewer' and
    picname = '$imagen'");
    $stmt->bind_result($gotpiclikes);
    $stmt->execute();
    $gotlikes = new StdClass();
    if ($stmt->fetch()) {
    $gotlikes->gotpiclikes = $gotpiclikes;}
    return $gotlikes->gotpiclikes;
    $stmt->close();
  }


  function getpiccommcount($imagen, $yhendus){
    $viewer = $_SESSION['logged_in_user_name'];
    $stmt = $yhendus->prepare("SELECT COUNT(*) from piccomms where picowner = '$viewer' and
    picname = '$imagen'");
    $stmt->bind_result($gotpiccomms);
    $stmt->execute();
    $gotcomms = new StdClass();
    if ($stmt->fetch()) {
    $gotcomms->gotpiccomms = $gotpiccomms;}
    return $gotcomms->gotpiccomms;
    $stmt->close();
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


  function cleanInput($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
  }


//sisselogimine

  function loginUser($username, $password, $yhendus){
    //Kontrollime kas kasutajanimi või parool on õiged
    $stmt = $yhendus->prepare("SELECT id_user FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->bind_result($id);
    $stmt->execute();

    //Kui päringut ei saa  siis anname veateate ja edastame selle response.
    if(!$stmt->fetch()) {
      exit();
    }
    $stmt->close();

    //Kui päringu sai, siis jätkab sisse logimisega
    $stmt = $yhendus->prepare("SELECT id_user, username FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->bind_result($id_db, $name_db);
    $stmt->execute();
    //Kui saan päringu kätte siis loon Sessiooni muutujad saadud andmetelt, if tähendab, et saab ainult ühe päringu, while võtaks mitu päringut, kui tuleb nii palju.
    if($stmt->fetch()){

      $_SESSION['logged_in_user_id'] = $id_db;
      $_SESSION['logged_in_user_name'] = $name_db;

      header("Location: feed.php");
      exit();
      }

      $stmt->close();

    }

//v2ljalogimine

if(isset($_GET['logout'])) { session_destroy(); header('location: index.php'); }

  ?>
