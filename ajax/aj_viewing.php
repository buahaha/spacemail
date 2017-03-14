<?php
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

chdir(str_replace('/ajax','', getcwd()));
require_once('config.php');
require_once('loadclasses.php');

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(@isset($_SERVER['HTTP_REFERER']) && 0 === strpos($_SERVER['HTTP_REFERER'], str_replace('/ajax','',URL::url_path())))
  {
    if(($_POST['ajtok'] == $_SESSION['ajtoken']) && (isset($_POST['charid'])) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
        $qry = DB::getConnection();
        if ($stmt = $qry->prepare("REPLACE INTO viewers (characterID, viewing, lastseen) VALUES (?, ?, NOW())")) {
          $stmt->bind_param('ii', $charid, $viewing);
          $charid = $_SESSION['characterID'];
          $viewing = $_POST['charid'];
          $stmt->execute();
          if ($stmt->errno) {
              echo($stmt->error);
              $stmt->close();
              exit;
          }
          $stmt->close();
        } else {
            echo('false');
            exit;
        }

        if ($stmt = $qry->prepare("SELECT viewers.characterID, esisso.characterName, viewers.viewing FROM viewers LEFT join esisso ON viewers.characterID = esisso.characterID WHERE lastseen >= ? AND NOT viewers.characterID = ?")) {
          $stmt->bind_param('si', $time, $ownid);
          $time = date("Y-m-d H:i:s", time()-30);
          $ownid = $_SESSION['characterID'];
          $stmt->execute();
          if ($stmt->errno) {
              echo($stmt->error);
              $stmt->close();
              exit;
          }
          $result = array();
          $stmt->bind_result($id, $name, $viewing);
          while ($stmt->fetch()) {
            $result[] = array('id' => $id, 'name' => $name, 'viewing' => $viewing);
          }
          $stmt->close();
        } else {
          echo('false');
          exit;
        }
        echo(json_encode($result));
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
?>
