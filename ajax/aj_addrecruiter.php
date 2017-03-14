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
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['charid'])) && (isset($_POST['name'])) && ($_SESSION['isAdmin'] == True)) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM recruiters WHERE characterID=".$_POST['charid'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        echo('Recruiter already added.');
        exit;
      } else {
        if ($stmt = $qry->prepare("INSERT INTO recruiters (characterID, characterName) VALUES (?, ?)")) {
          $stmt->bind_param('is', $charid, $name);
          $charid = $_POST['charid'];
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
