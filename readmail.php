<?php
$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  echo('<p>You dont have permissions to read mails.</p>');
  die();
}

if (!isset($_GET['cid']) || !isset($_GET['mid'])) {
  echo('<p>Required information missing.</p>');
  die();
}

if (!isset($_SESSION['characterID']) || $_SESSION['characterID'] != $_GET['cid']) {
  echo('<p>You dont have permissions to read this mail.</p>');
  die();
}

function idToName($id, $dict) {
  if (isset($dict[$id])) {
    return $dict[$id];
  } else {
    return 'Unknown';
  }
}

function strparse($html) {
  $html = preg_replace('/(<[^>]+) (style|size)=".*?"/i', '$1', $html);
  $html = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $html);
  $html = str_replace ('href="fitting:', 'target="_blank" href="http://o.smium.org/loadout/dna/', $html);
  $html = str_replace ('href="showinfo:1380//', 'target="_blank" href="https://zkillboard.com/character/', $html);
  $html = str_replace ('href="showinfo:2//', 'target="_blank" href="https://zkillboard.com/corporation/', $html);
  $html = str_replace ('href="showinfo:5//', 'target="_blank" href="http://evemaps.dotlan.net/system/', $html);
  $html = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $html);
  $html = preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" target="_blank">$2</a> ', $html." ");
  return $html;
}

$characterID = $_GET['cid'];
$mailID = $_GET['mid'];

$esimail = new ESIMAIL($characterID);
$mail = $esimail->readMail($mailID);
if ($esimail->getError()) {
    echo('Error fetching mail: '.$esimail->getMessage());
    exit;
} else {
    if(isset($_GET['read']) && !$_GET['read']) {
        $esimail->markRead($mailID);
    }
}
$body = $mail['body'];
$ids = array($mail['from']);
foreach ($mail['recipients'] as $r) {
  $ids[] = $r['recipient_id'];
}
$dict = EVEHELPERS::esiIdsToNames($ids);
$mail['from_name'] = idToName($mail['from'], $dict);
foreach ($mail['recipients'] as $i=>$r) {
    if ($r['recipient_type'] == 'mailing_list') {
        $mail['recipients'][$i]['recipient_name'] = 'Mailing list';
    } else {
        $mail['recipients'][$i]['recipient_name'] = idToName($r['recipient_id'], $dict);
    }
}
$recarray = array();
foreach ($mail['recipients'] as $r) {
    $recarray[] =  '<span class="eve'.strtolower(substr($r['recipient_type'], 0, 4)).'" eveid="'.$r['recipient_id'].'">'.$r['recipient_name'].'</span>';
}

$html = '<div class="row" style="display: none"><div class="col-xs-12"><span class="h5">'.$mail['subject'].'</span></div></div>
           <div class="clearfix"><div class="pull-right">'
             .($characterID == $mail['from']?'':'<a style="margin: 0 2px;" class="btn btn-primary btn-xs" href="compose.php?action=re&mid='.$mailID.'" title="Reply to"><i class="fa fa-reply" aria-hidden="true"></i></a>')
             .((count($mail['recipients']) < 2)?'':'<a style="margin: 0 2px;" class="btn btn-primary btn-xs" href="compose.php?action=reall&mid='.$mailID.'" title="Reply to all"><i class="fa fa-reply-all" aria-hidden="true"></i></a>').'
             <a style="margin: 0 2px;" class="btn btn-primary btn-xs" href="compose.php?action=fwd&mid='.$mailID.'" title="Forward mail"><i class="fa fa-share" aria-hidden="true"></i></a>
           </div></div>
           <div class="well well-sm"><div class="row">
             <div class="col-xs-4 col-md-2 col-lg-1">Date: </div><div class="col-xs-8 col-md-9 col-lg-11">'.date('Y/m/d', strtotime($mail['timestamp'])).'</div>
             <div class="col-xs-4 col-md-2 col-lg-1">Time: </div><div class="col-xs-8 col-md-9 col-lg-11">'.date('H:i', strtotime($mail['timestamp'])).'</div>
             <div class="col-xs-4 col-md-2 col-lg-1">From: </div><div class="col-xs-8 col-md-9 col-lg-11"><span class="evechar" eveid="'.$mail['from'].'">'.$mail['from_name'].'</span></div>
             <div class="col-xs-4 col-md-2 col-lg-1">to: </div><div class="col-xs-8 col-md-9 col-lg-11">'.implode(', ', $recarray).'</div>
           </div></div>
           <div class="well well-sm"><div class="row">
             <div class="col-xs-12"><p>'.strparse($body).'</p></div>
           </div></div>
         </div>
         <script src="js/clickable.php"></script>
         <script src="js/bootstrap-contextmenu.js"></script>';
echo(preg_replace( "/\r|\n/", "", $html));
?>