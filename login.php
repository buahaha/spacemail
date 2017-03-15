<?php

require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
if (isset($_GET['code'])) {
  $code = $_GET['code'];
  $state = $_GET['state'];
  if ($state != $_SESSION['authstate']) {
    $page = new Page('SSO Login');
    $html = "Error: Invalid state, aborting.";
    session_destroy();
    $page->setError($html);
    $page->display();
    exit;
  }
  $esisso = new ESISSO();
  $esisso->setCode($code);
  if (!$esisso->getError()) {
    $dbsso = new ESISSO(null, $esisso->getCharacterID());
    if (count(array_intersect($esisso->getScopes(), $dbsso->getScopes())) == count($esisso->getScopes())) {
      if (count($esisso->getScopes()) == count($dbsso->getScopes())) {
          $result = $esisso->addToDb();
      } else {
        $esisso = $dbsso;
        $result = true;
        $esisso->setMessage("You were succesfully logged in.");
      }
    } else {
      $result = $esisso->addToDb();
    }
    if ($result) {
        $page = new Page('SSO Login');
        $_SESSION['characterID'] = $esisso->getCharacterID();
        $_SESSION['characterName'] = $esisso->getCharacterName();
        $authtoken = new AUTHTOKEN(null, $_SESSION['characterID']);
        $authtoken->addToDb();
        $authtoken->storeCookie();
        include_once('auth.php');
        $page = new Page('SSO Login');
        if (isset($_GET['page'])) {
            $fwd = $_GET['page'];
        } else {
            $fwd = 'index.php';
        }
        $page->addHeader('<meta http-equiv="refresh" content="2;url='.URL::url_path().$fwd.'">');
        $page->setInfo($esisso->getMessage());
        $page->display();
        exit;
    }
  } else {
    $page = new Page('SSO Login');
    $page->setError($esisso->getMessage());
    $page->display();
    exit;
  }
}

$authurl = "https://login.eveonline.com/oauth/authorize/";
$state = random_str(32); 
$scopes = MAIL_SCOPES;
$_SESSION['authstate'] = $state;
$url = $authurl."?response_type=code&redirect_uri=".rawurlencode(URL::full_url())."&client_id=".ESI_ID."&scope=".implode(' ',$scopes)."&state=".$state;
header('Location: '.$url);
exit;
?>