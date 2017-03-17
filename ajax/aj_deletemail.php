<?php
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

chdir(str_replace('/ajax','', getcwd()));
require_once('config.php');
require_once('loadclasses.php');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(@isset($_SERVER['HTTP_REFERER']) && (preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER']) == str_replace('/ajax','',URL::url_path().'index.php') || preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER']) == str_replace('/ajax','',URL::url_path())))
  {
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && isset($_POST['id']) && isset($_SESSION['characterID'])) {
        $esimail = new ESIMAIL($_SESSION['characterID']);
        if($esimail->deleteMail($_POST['id'])) {
          $qry = DB::getConnection();
          $sql = "REPLACE INTO deleted_mails (mailID) VALUES (".$_POST['id'].")";
          $result = $qry->query($sql);
          echo('true');
          exit;
        }
        else {
          echo('false');
          exit;
        }
    }
    else {
      echo('false');
      exit;
    }
  }
  else {
    echo('false');
    exit;
  }
} else {
  echo('false');
  exit;
}
?>
