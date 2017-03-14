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
    if(isset($_SESSION['ajtoken']) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
      $qry = DB::getConnection();
      if ($stmt = $qry->prepare("SELECT redflags.id, redflags.reason, redflags.flagged_by, redflags.time, esisso.characterName FROM `redflags` LEFT JOIN esisso ON redflags.flagged_by = esisso.characterID")) {
        $result = array();
        $stmt->bind_result($id, $reason, $flaggedby, $time, $name);
        $stmt->execute();
        if ($stmt->errno) {
            echo($stmt->error);
            $stmt->close();
            exit;
        }
        while ($stmt->fetch()) {
          $result[$id] = array('id' => $id, 'reason' => $reason, 'by' => $flaggedby, 'time' => date('Y/m/d', strtotime($time)),'byname' => $name);
        }
        $stmt->close();
        echo(json_encode($result));
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
