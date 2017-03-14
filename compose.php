<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['characterID'])) {
  $esimail = new ESIMAIL($_SESSION['characterID']);
  if ($esimail->getScopes() == MAIL_SCOPES) {
    $scopesOK = True;
  } else {
    $scopesOK = False;
  }
}

if (!isset($_SESSION['characterID']) || !$scopesOK) {
  $page = new Page('Login required');
  $html = "<div class='col-xs-12'><br/>You need to log in with your EVE account to acces your mails. We do NOT get your account credentials. To Login button will redirect you to the single sign on page and afterwards back here.<div class='col-xs-12' style='height: 20px'></div><p><a href='login.php?login=apply&page=apply.php'><img height='32px' src='img/evesso.png'></a><br/><br/>If you would like to know, what we use your API information for, please red our <a href='disclaimer.php'>disclaimer</a>.</p></div>";
  $page->addBody($html);
  $page->display();
  exit;
}

$footer = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="js/esi_autocomplete.js"></script>
    <script src="js/bootstrap-contextmenu.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js" integrity="sha256-dHf/YjH1A4tewEsKUSmNnV05DDbfGN3g7NMq86xgGh8=" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() {
      $( "#inv-button" ).click(function() {
        var id = $("#inv-id").val();
        var cat = $("#inv-cat").val();
        var name = $("#inv-name").val();
        $( "#recipients" ).append( \'<div class="token btn-sm bg-primary"><input type="hidden" name="rec[\'+id+\'][cat]" value="\'+cat+\'"></input><input type="hidden" name="rec[\'+id+\'][name]" value="\'+name+\'"></input>\'+name+\'&nbsp;<span class="btn btn-xs glyphicon glyphicon-remove" onclick="delrecipient(this)"></span></div>\' );
      });
    });
    $(document).on("keypress", ":input:not(textarea)", function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });
    $( "#cancelbtn" ).click(function() {
      $("#mail").find("*").removeAttr("required");
      $("#mail").submit();
    });
    function delrecipient(btn) {
        var tok = btn.closest(".token").remove();
    }
    </script>';

function getToBar($recipients = null, $subject = null, $mailbody = null) {
    $html = '<form id="mail" method="post" action="" data-toggle="validator" role="form"><div class="col-xs-12">
        <div class="form-group col-xs-10 col-sm-8 col-md-7 col-lg-6">
            <label for="recipients">To:</label>
            <div type="text" class="well well-sm" name="recipients" id="recipients" style="margin-bottom: 2px">&nbsp;';
    foreach($recipients as $id => $rec) {
        $html .= '<div class="token btn-sm bg-primary">
                  <input type="hidden" name="rec['.$id.'][cat]" value="'.$rec['cat'].'"></input>
                  <input type="hidden" name="rec['.$id.'][name]" value="'.$rec['name'].'"></input>'
                  .$rec['name'].'<span class="btn btn-xs glyphicon glyphicon-remove" onclick="delrecipient(this)"></span></div>';
    }
    $html .= '</div>
        </div>
        <div class="form-group col-xs-12">
           <input id="inv-name" type="text" class="typeahead form-control">
           <input id="inv-id" type="hidden" values="">
           <input id="inv-cat" type="hidden" values="">
           <button type="button" id="inv-button" class="tt-btn btn btn-primary disabled"><span class="glyphicon glyphicon-user"><span class="glyphicon glyphicon-plus"></span></span></button>
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="form-group col-xs-10 col-sm-8 col-md-7 col-lg-6">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" name="subject" required value="'.$subject.'"></input>
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="form-group col-xs-10 col-sm-8 col-md-7 col-lg-6">
            <label for="mailbody">Mail:</label>
            <textarea class="form-control" rows="15" name="mailbody" required>'.$mailbody.'</textarea>
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Send</button>
            <button id="cancelbtn" type="submit2" name="cancel" value="cancel" class="btn btn-primary">Cancel</button>
        </div>
      </div></form>';
    return $html;
}

$recipients = array();
$subject = null;
$mailbody = null;
$recerror = false;

$page = new Page('Compose new mail');

if(isset($_POST['cancel'])) {
  header('Location: '.URL::url_path());
} elseif (isset($_POST['submit'])) {
  (isset($_POST['rec'])?$recipients = $_POST['rec']:$recerror = true);
  (isset($_POST['subject'])?$subject = $_POST['subject']:'');
  (isset($_POST['mailbody'])?$mailbody = $_POST['mailbody']:'');

  if ($recerror) {
      $page->setError('Please add at least one Recipient.');
  } else {
      $rec_ary = array();
      foreach(array_unique($recipients) as $id => $rec) {
          $rec_ary[] = array('id' => $id, 'type' => $rec['cat']);
      }
      $esimail->sendMail($rec_ary, htmlspecialchars($subject), preg_replace("/\r\n|\r|\n/", "<br />", htmlspecialchars($mailbody)));
      if($esimail->getError()) {
          $page->setError($esisso->getMessage());
      } else {
          $page = new Page('Done.');
          $page->addBody('<p>Your mail has been sent.</p><br/><br/><a class="btn btn-primary" href="'.URL::url_path().'">Back to inbox</a>');
          $page->display();
          exit;
      }
  }
}

$page->addHeader('<link href="css/typeaheadjs.css" rel="stylesheet">');
$page->addBody(getToBar($recipients, $subject, $mailbody));
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>

