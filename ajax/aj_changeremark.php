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
    if((isset($_POST['pk'])) && (isset($_POST['value'])) && (($_SESSION['isRecruiter'] == True) || ($_SESSION['isAdmin'] == True))) {
      $qry = DB::getConnection();
      $sql = "SELECT * FROM applications WHERE characterID=".$_POST['pk'];
      $result = $qry->query($sql);
      if ($result->num_rows) {
        if ($stmt = $qry->prepare("UPDATE applications SET remarks=? WHERE characterID=?")) {
          $stmt->bind_param('si', $remark, $id);
          $remark = $_POST['value'];
          $id = $_POST['pk'];
          $stmt->execute();
          $stmt->close();
          CACHE::clear($id);
          echo('true');
        } else {
          echo('false');
          exit;
        }
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
    echo('false 1');
    exit;
  }
}
else {
  echo('false');
  exit;
}
?>
