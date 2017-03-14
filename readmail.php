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

$characterID = $_GET['cid'];
$mailID = $_GET['mid'];

$esimail = new ESIMAIL($characterID);
$mail = $esimail->readMail($mailID);
$body = preg_replace('/(<[^>]+) (style|size)=".*?"/i', '$1', $mail['body']);
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

$html = '<div class="row"><div class="col-xs-12"><span class="h5">'.$mail['subject'].'</span></div></div>
         <div class="row">
             <div class="col-xs-4 col-md-2 col-lg-1">Date: </div><div class="col-xs-8 col-md-9">'.date('Y/m/d', strtotime($mail['timestamp'])).'</div>
             <div class="col-xs-4 col-md-2 col-lg-1">Time: </div><div class="col-xs-8 col-md-9">'.date('H:i:s', strtotime($mail['timestamp'])).'</div>
             <div class="col-xs-4 col-md-2 col-lg-1">From: </div><div class="col-xs-8 col-md-9"><span class="evechar" eveid="'.$mail['from'].'">'.$mail['from_name'].'</span></div>
             <div class="col-xs-4 col-md-2 col-lg-1">to: </div><div class="col-xs-8 col-md-9">'.implode(', ', $recarray).'</div>
             <div class="col-xs-12" style="height: 20px"></div>
             <div class="col-xs-12">'.$body.'</div>
         </div>
         <script src="js/clickable.php"></script>
         <script src="js/bootstrap-contextmenu.js"></script>';
echo($html);
?>
