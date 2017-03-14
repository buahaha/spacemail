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
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['corpid'])) && (isset($_POST['name'])) && ($_SESSION['isAdmin'] == True)) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM membercorps WHERE corporationID=".$_POST['corpid'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        echo('Corp already added.');
        exit;
      } else {
        if ($stmt = $qry->prepare("INSERT INTO membercorps (corporationID, corporationName) VALUES (?, ?)")) {
          $stmt->bind_param('is', $corpid, $name);
          $corpid = $_POST['corpid'];
          $name = $_POST['name'];
          $stmt->execute();
          if ($stmt->errno) {
              echo($stmt->error);
              $stmt->close();
              exit;
          }
          $stmt->close();
          echo('Added');
          exit;
        } else {
          echo('false');
          exit;
        }
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
