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
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['corpid'])) && ($_SESSION['isAdmin'] == True)) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM membercorps WHERE corporationID=".$_POST['corpid'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        if ($stmt = $qry->prepare("DELETE FROM membercorps WHERE corporationID=?")) {
          $stmt->bind_param('i', $corpid);
          $corpid = $_POST['corpid'];
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
        echo('Not a member corp.');
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
