<?php
require_once('../../functions2.php');
if(isset($_SESSION['logged_in_user_name'])) {require('../template.php');}
else {
  require('../loggedouttemplate.php');
}
?>
