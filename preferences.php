<?php
$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  header('Location: '.URL::url_path.'index.php');
  die();
}

if (isset($_POST["submit"])) {
  $path = URL::path_only();
  $server = URL::server();
  setcookie('spacemailstyle', $_POST["style"], strtotime("now")+3600*24*365, $path, $server, 0);
  $_SESSION['style'] = $_POST["style"];
}

if (isset($_SESSION["style"])) {
    $style = $_SESSION["style"];
} elseif (isset($_COOKIE["spacemailstyle"])) {
    $style = $_COOKIE["spacemailstyle"];
    $_SESSION["style"] = $style;
} else {
    $style = "dark";
}

$html = '<div class="col-xs-12">
           <form id="prefs" role="form" action="" method="post">
             <div class="form-group col-xs-12">
               <label for="style" class="control-label">Please select your preferred site Layout:</label>
               <div class="radio">
                 <label><input type="radio" name="style" value="dark" '.($style == "dark"?'checked ':'').'>Dark</label>
               </div>
               <div class="radio">
                 <label><input type="radio" name="style" value="light" '.($style == "light"?'checked ':'').'>Light</label>
               </div>
             </div>
             <div class="form-group col-xs-12">
            <button type="submit" id="submit" class="btn btn-primary" value="submit" name="submit">Submit</button>
          </div>
        </form></div>';

$page = new Page('My Preferences');

$page->addBody($html);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display();
exit;
?>
