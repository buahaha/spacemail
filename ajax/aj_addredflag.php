<?php
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

chdir(str_replace('/ajax','', getcwd()));
require_once('config.php');
require_once('loadclasses.php');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(@isset($_SERVER['HTTP_REFERER']) && (0 === strpos($_SERVER['HTTP_REFERER'], str_replace('/ajax','',URL::url_path()))))
  {
    if(isset($_SESSION['ajtoken']) && (isset($_POST['id'])) && (isset($_POST['reason'])) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
      $qry = DB::getConnection();
      if ($stmt = $qry->prepare("REPLACE INTO redflags (id, reason, flagged_by) VALUES (?, ?, ?)")) {
        $stmt->bind_param('isi', $id, $reason, $flaggedby);
        $id = $_POST['id'];
        $reason = $_POST['reason'];
        $flaggedby = $_SESSION['characterID'];
        $stmt->execute();
        $stmt->close();
        CACHE::clear();
        echo('true');
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
