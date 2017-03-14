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
    if(isset($_SESSION['ajtoken']) && (isset($_POST['id'])) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
      $qry = DB::getConnection();
      if ($stmt = $qry->prepare("DELETE FROM redflags WHERE id = ?")) {
        $stmt->bind_param('i', $id );
        $id = $_POST['id'];
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
