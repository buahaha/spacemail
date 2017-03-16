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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.min.css">
    <script>
    $(document).ready(function() {
      $("#bodyarea").wysihtml5({
          toolbar: {
              "blockquote": false,
              "image": false,
              "color": false,
              "lists": false,
              "fa": true,
              "stylesheets": [],
          },
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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.all.min.js"</script>';

function getToBar($recipients = null, $subject = null, $mailbody = null) {
    $html = '<form id="mail" method="post" action="" data-toggle="validator" role="form"><div class="col-xs-12">
        <div class="form-group col-xs-12 col-md-10 col-lg-8">
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
           <input id="inv-name" type="text" class="typeahead form-control" placeholder="Search mail recipients...">
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="form-group col-xs-12 col-md-10 col-lg-8">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" name="subject" required value="'.$subject.'"></input>
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="form-group col-xs-12 col-md-10 col-lg-8">
            <label for="mailbody">Mail:</label>
            <textarea id="bodyarea" class="textarea form-control" rows="15" name="mailbody">'.$mailbody.'</textarea>
        </div>
        <div class="col-xs-12" style="height: 20px;"></div>
        <div class="col-xs-11">
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Send</button>
            <button id="cancelbtn" type="submit2" name="cancel" value="cancel" class="btn btn-primary">Cancel</button>
        </div>
      </div></form>';
    return $html;
}

function idToName($id, $dict) {
  if (isset($dict[$id])) {
    return $dict[$id]['name'];
  } else {
    return 'Unknown';
  }
}

function idToCategory($id, $dict) {
  if (isset($dict[$id])) {
    return $dict[$id]['cat'];
  } else {
    return 'mailing_list';
  }
}


function strparse($html) {
  $html = str_replace ('href="fitting:', 'target="_blank" href="http://o.smium.org/loadout/dna/', $html);
  $html = str_replace ('href="showinfo:1380//', 'target="_blank" href="https://zkillboard.com/character/', $html);
  $html = str_replace ('href="showinfo:2//', 'target="_blank" href="https://zkillboard.com/corporation/', $html);
  $html = str_replace ('href="showinfo:5//', 'target="_blank" href="http://evemaps.dotlan.net/system/', $html);
  $html = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $html);
  $html = preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" target="_blank">$2</a> ', $html." ");
  return $html;
}

$recipients = array();
$subject = null;
$mailbody = null;
$recerror = false;

$page = new Page('Compose new mail');




if(isset($_POST['cancel'])) {
  header('Location: '.URL::url_path());
}

if(!isset($_POST['submit']) && isset($_GET['action']) && isset($_GET['mid']) && ($_GET['action'] == 'fwd' || $_GET['action'] == 're' || $_GET['action'] == 'reall')) {
    $mail = $esimail->readMail($_GET['mid']);
    if($esimail->getError()) {
        $page->setError($esisso->getMessage());
    } else {
        $ids = array($mail['from']);
        foreach ($mail['recipients'] as $r) {
            $ids[] = $r['recipient_id'];
        }
        $dict = EVEHELPERS::esiIdsLookup($ids);
        $mail['from_name'] = idToName($mail['from'], $dict);
        foreach ($mail['recipients'] as $i=>$r) {
            if ($r['recipient_type'] == 'mailing_list') {
                $mail['recipients'][$i]['recipient_name'] = 'Mailing list';
            } else {
                $mail['recipients'][$i]['recipient_name'] = idToName($r['recipient_id'], $dict);
            }
        }
        $mailbody .= '<br /><br />--------------------------------<br />'.$mail['subject'].'<br />Sent: '.date('Y/m/d H:i', strtotime($mail['timestamp'])).'<br />From: '.$mail['from_name'].'<br />To: '.implode(', ', array_column($mail['recipients'],'recipient_name')).'<br /><br />'.strparse($mail['body']);
        if($_GET['action'] == 'fwd') {
            $subject = 'Fw: '.$mail['subject'];
        } else {
            $subject = 'Re: '.$mail['subject'];
        }
        if($_GET['action'] == 're' || $_GET['action'] == 'reall') {
            $recipients[$mail['from']] = array('name' => $mail['from_name'], 'cat' => idToCategory($mail['from'], $dict));
        }
        if($_GET['action'] == 'reall') {
            foreach($mail['recipients'] as $r) {
                if($r['recipient_id'] != $_SESSION['characterID']) {
                    $recipients[$r['recipient_id']] = array('name' => $r['recipient_name'], 'cat' => $r['recipient_type']);
                }
            }
        }
    }
}


if (isset($_POST['submit'])) {
  (isset($_POST['rec'])?$recipients = $_POST['rec']:$recerror = true);
  (isset($_POST['subject'])?$subject = $_POST['subject']:'');
  (isset($_POST['mailbody'])?$mailbody = $_POST['mailbody']:'');

  if ($recerror) {
      $page->setError('Please add at least one Recipient.');
  } else {
      $rec_ary = array();
      foreach($recipients as $id => $rec) {
          $rec_ary[] = array('id' => $id, 'type' => $rec['cat']);
      }
      $esimail->sendMail($rec_ary, htmlspecialchars($subject), preg_replace("/\r\n|\r|\n/", "<br />", $mailbody));
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

