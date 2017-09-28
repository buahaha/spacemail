<?php
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

if (!isset($_SESSION['characterID'])) {
  $authtoken = AUTHTOKEN::getFromCookie();
  if ($authtoken) {
    if ($authtoken->verify()) {
      $_SESSION['characterID'] = $authtoken->getCharacterID();
    }
  }
}

if (isset($_SESSION['characterID']) && !isset($_SESSION['characterName'])) {
  $esimail = new ESIMAIL($_SESSION['characterID']);
  $_SESSION['characterName'] = $esimail->getCharacterName();
}

if (isset($_SESSION['characterID']) && isset($_SESSION['characterName'])) {
  if (in_array($_SESSION['characterID'], ADMINS)) {
    $_SESSION['isAdmin'] = True;
  }
}

if (!isset($_SESSION['scopes']) && isset($_SESSION['chsracterID'])) {
    if (!isset($esimail)) {
        $esimail = new ESIMAIL($_SESSION['characterID']);
    }
    $_SESSION['scopes'] = $esimail->getScopes();
}

if (isset($_SESSION['scopes'])) {
    $scopes = $_SESSION['scopes'];

    if (array_intersect($scopes, MAIL_SCOPES) != MAIL_SCOPES) {
        $page = new Page('Scopes are missing');
        $missing = array_diff(MAIL_SCOPES, $scopes);
        $html = '<p>Some of the Scopes required for this app to work are missing, most likely functionality has been added.<br/>You are being redirected and to the EVE login and the following scope'.(count($missing) > 1?'s are':' is').' added to the current ones:<br/><br/>';
        foreach ($missing as $m) {
            $html .= '&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-plus-sign"></span>&nbsp;'.$m;
        }
        $html .= '</p>
                  <a href="login.php?page='.rawurlencode(URL::relative_url()).'" class="btn btn-primary" role="button">Re-login</a>';
        $page->addBody($html);
        $page->display();
        die();
    }
}
?>
