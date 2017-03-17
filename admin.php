<?php
$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  header('Location: '.URL::url_path.'index.php');
  die();
}

if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
  header('Location: '.URL::url_path().'index.php');
  die();
}

if (!isset($_SESSION['ajtoken'])) {
  $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
}

$html = '';
$page = new Page('Admin Panel');

$page->display();
exit;
?>
