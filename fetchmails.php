<?php

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

$data = array('data' => array(), 'lastid' => 0);

if (!isset($_SESSION['characterID']) || !$scopesOK) {
  echo(json_encode($data));
  exit;
}

$labels = $esimail->getMailLabels();
$labels['0'] = 'others';

if (null == URL::getQ('label')) {
    if($labels) {
        $l = array_keys($labels)[0];
    } else {
        $l = null;
    }
} else {
    $l = URL::getQ('label');
}

if ($l == 'none') {
    $mails = $esimail->getMails(array(0), URL::getQ('lastid'), URL::getQ('pages'));
} else {
    $mails = $esimail->getMails(array($l), URL::getQ('lastid'), URL::getQ('pages'));
}

foreach ((array)$mails as $mail) {
    $temp = array();
    $temp['date'] = date('y/m/d H:i', strtotime($mail['timestamp']));
    $temp['isread'] = '<i class="fa fa-envelope'.($mail['is_read']?'-open':'').'-o" aria-hidden="true"></i>';
    $temp['img'] = '<img height="24px" src="https://imageserver.eveonline.com/Character/'.$mail['from'].'_32.jpg">';
    $temp['from'] = '<span class="evechar" eveid="'.$mail['from'].'">'.$mail['from_name'].'<span>';
    $recarray = array();
    foreach ($mail['recipients'] as $r) {
        $recarray[] =  '<span class="eve'.strtolower(substr($r['recipient_type'], 0, 4)).'" eveid="'.$r['recipient_id'].'">'.$r['recipient_name'].'</span>';
    }
    $temp['to'] = implode(', ', $recarray);
    $temp['subject'] = '<a href="#" id="'.$mail['mail_id'].'" onclick="readmail(this, '.($mail['is_read']?'1':'0').'); return false;">'.$mail['subject'].'</a>';
    $data['data'][] = $temp;
    $data['lastid'] = $mail['mail_id'];
}

echo(json_encode($data));

?>