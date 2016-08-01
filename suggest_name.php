<?php
$viewer = $_SESSION['logged_in_user_name'];
require_once('functions.php');
$mysqli = new mysqli("codeloops.ee", "vhost50495s1", "eeterpeedu", "vhost50495s1");
/* Connect to database and set charset to UTF-8 */
if($mysqli->connect_error) {
  echo 'Database connection failed...' . 'Error: ' . $mysqli->connect_errno . ' ' . $mysqli->connect_error;
  exit;
} else {
  $mysqli->set_charset('utf8');
}
/* retrieve the search term that autocomplete sends */
$term = trim(strip_tags($_GET['term'])); 
$a_json = array();
$a_json_row = array();
if ($data = $mysqli->query("SELECT fullname, username FROM user WHERE fullname LIKE '%$term%' OR username LIKE '%$term%'  AND username NOT LIKE '%$viewer%' ORDER BY username, fullname")) {
	while($row = mysqli_fetch_array($data)) {
		$fullname = htmlentities(stripslashes($row['fullname']));
		$username = htmlentities(stripslashes($row['username']));
		$id_user = htmlentities(stripslashes($row['id_user']));
		$a_json_row["id"] = $code;
		$a_json_row["username"] = $username;
		$a_json_row["value"] = $fullname.' '.$username;
		$a_json_row["label"] = $fullname.' '.$username;
		array_push($a_json, $a_json_row);
	}
}
// jQuery wants JSON data
echo json_encode($a_json);
flush();
 
$mysqli->close();
?>