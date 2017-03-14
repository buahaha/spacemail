<?php
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

chdir(str_replace('/ajax','', getcwd()));
require_once('config.php');
require_once('loadclasses.php');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(@isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']==str_replace('/ajax','',URL::url_path().'applications.php') || preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER'])==str_replace('/ajax','',URL::url_path().'application.php')))
  {
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['charid'])) && (isset($_POST['state'])) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM applications WHERE characterID=".$_POST['charid'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        $row = $result->fetch_assoc();
        $sql = "UPDATE applications SET status='".$_POST['state']."' WHERE characterID=".$_POST['charid'];
        $result = $qry->query($sql);
        CACHE::clear($_POST['charid']);
        DBH::addNotifyRecruits($_POST['charid']);
        echo('true');
        exit;
      } else {
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
}
else {
  echo('false');
  exit;
}
?>
