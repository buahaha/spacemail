<?php
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

chdir(str_replace('/ajax','', getcwd()));
require_once('config.php');
require_once('loadclasses.php');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==str_replace('/ajax','',URL::url_path().'admin.php'))
  {
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['charid'])) && ($_SESSION['isAdmin'] == True)) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM internals WHERE characterID=".$_POST['charid'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        if ($stmt = $qry->prepare("DELETE FROM internals WHERE characterID=?")) {
          $stmt->bind_param('i', $charid);
          $charid = $_POST['charid'];
          $stmt->execute();
          if ($stmt->errno) {
              echo($stmt->error);
              $stmt->close();
              exit;
          }
          $stmt->close();
          echo('Removed');
          exit;
        } else {
          echo('false');
          exit;
        }
      } else {
        echo('Not a recruiter.');
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
