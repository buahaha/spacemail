<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['characterID'])) {
  $esinot = new ESINOTIFICATIONS($_SESSION['characterID']);
  if ($esinot->getScopes() == MAIL_SCOPES) {
    $scopesOK = True;
  } else {
    $scopesOK = False;
  }
}

$html = '';

if (!isset($_SESSION['characterID']) || !$scopesOK) {
  $page = new Page('Login required');
  $html .= "<div class='col-xs-12'><br/>You need to log in with your EVE account to acces your mails. We do NOT get your account credentials. The login button will redirect you to the single sign on page and afterwards back here.<div class='col-xs-12' style='height: 20px'></div><p><a href='login.php?page=notifications.php'><img height='32px' src='img/evesso.png'></a><br/><br/>If you would like to know what we use your API information for, please read our <a href='disclaimer.php'>disclaimer</a>.</p></div>";
  $page->addBody($html);
  $page->display();
  exit;
}

if (!isset($_SESSION['ajtoken'])) {
  $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
}

$footer = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="js/typeahead.bundle.min.js"></script>
    <script src="js/esi_autocomplete.js"></script>
    <script src="js/bootstrap-contextmenu.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.min.css" integrity="sha256-C4J6NW3obn7eEgdECI2D1pMBTve41JFWQs0UTboJSTg=" crossorigin="anonymous" />';

$page = new Page($esinot->getCharacterName().'\'s notifications');

$page->addBody('');
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>
