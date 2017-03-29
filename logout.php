<?php

require_once('config.php');
require_once('loadclasses.php');

$_SESSION = array();
session_destroy();
$path = URL::path_only();
$server = URL::server();
setcookie('spacemail', "", time()-3600, $path, $server, 1);
unset($_COOKIE['spacemail']);

$page = new Page('SSO Login');
$page->setInfo("You were logged out.");
$page->display();
exit;
?>
