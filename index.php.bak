<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

function mailsPage($esimail) {
    $table = '<div class="row"><div class="col-sm-12 col-md-2 col-lg-1">';
    $labels = $esimail->getMailLabels();
    $labels['0'] = 'others';
    $table .= '<ul class="nav nav-pills nav-stacked">';
        if (null == URL::getQ('label')) {
            if($labels) {
                $l = array_keys($labels)[0];
            } else {
                $l = null;
            }
        } else {
            $l = URL::getQ('label');
        }
        foreach ((array)$labels as $k => $label) {
            $table .= '<li'.($l==$k?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label='.$k.'" onclick="return loading()">'.str_replace(array("]", "["), "", $label).'</a></li>';
        } 
    $table .= '</ul>';
    if ($l == 'none') {
        $mails = $esimail->getMails(array(0));
    } else {
        $mails = $esimail->getMails(array($l));
    }
    $table .= '</div><div class="col-sm-12 hidden-md hidden-lg" style="height: 20px"></div>
    <div class="col-sm-12 col-sm-10 col-lg-11">
    <table id="mailstable" class="jdatatable table responsive table-striped table-hover" cellspacing="0" width="100%">
      <thead>
          <th class="all">Time</th>';
          if ($l == 2) {
            $table .= '<th>To:</th>
            <th>Subject</th>';
          } else {
            $table .= '<th class="all no-sort"></th>
            <th class="num no-sort min-mobile-l"></th>
            <th>From:</th>
            <th>Subject</th>
            <th class="min-tablet-l">To:</th>';
          }
      $table .= '</thead>
      <tbody>';
      foreach ((array)$mails as $mail) {
          $fromline = '<td><img height="24px" src="https://imageserver.eveonline.com/Character/'.$mail['from'].'_32.jpg"></td><td><span class="evechar" eveid="'.$mail['from'].'">'.$mail['from_name'].'<span></td>';
          $recarray = array();
          foreach ($mail['recipients'] as $r) {
              $recarray[] =  '<span class="eve'.strtolower(substr($r['recipient_type'], 0, 4)).'" eveid="'.$r['recipient_id'].'">'.$r['recipient_name'].'</span>';
          }
          $toline = '<td>'. implode(', ', $recarray).'</td>';
          $table .= '<tr><td>'.date('y/m/d H:i', strtotime($mail['timestamp'])).'</td>';
          (($l == 2)?$table .= $toline:$table .= '<td><i class="fa fa-envelope'.($mail['is_read']?'-open':'').'-o" aria-hidden="true"></i></td>'.$fromline);
          $table .= '<td><a href="#" id="'.$mail['mail_id'].'" onclick="readmail(this, '.($mail['is_read']?'1':'0').'); return false;">'.$mail['subject'].'</a></td>';
          (($l != 2)?$table .= $toline:'');
          $table .= '</tr>';
          
      }
      $table .= '</tbody></table></div></div>
      <script>
          function readmail(link, isread) {
              var id = $(link).attr("id");
              var subject = $(link).text();
              var dialog = new BootstrapDialog(
                  {message: "Fetching mail...</br><center><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></center>",
                  title: subject,
                  buttons: [{
                      label: "Close",
                      action: function(dialogRef) {
                          dialogRef.close();
                      }
                  }],});
              dialog.open();
              $.get("readmail.php?mid="+id+"&cid='.$esimail->getCharacterID().'&read="+isread, function(data, status){
                  dialog.setMessage(data);
              });
          }
      </script>
      ';
      return $table;
}

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

$html = '';

if (!isset($_SESSION['characterID']) || !$scopesOK) {
  $page = new Page('Login required');
  $html .= "<div class='col-xs-12'><br/>You need to log in with your EVE account to acces your mails. We do NOT get your account credentials. To Login button will redirect you to the single sign on page and afterwards back here.<div class='col-xs-12' style='height: 20px'></div><p><a href='login.php'><img height='32px' src='img/evesso.png'></a><br/><br/>If you would like to know, what we use your API information for, please red our <a href='disclaimer.php'>disclaimer</a>.</p></div>";
  $page->addBody($html);
  $page->display();
  exit;
}

$footer = '<script>
          $(document).ready(function() {
            $(".jdatatable").dataTable(
               {
                   "bPaginate": true,
                   "pageLength": 25,
                   "aoColumnDefs" : [ {
                       "bSortable" : false,
                       "aTargets" : [ "no-sort" ]
                   }, {
                       "sClass" : "num-col",
                       "aTargets" : [ "num" ]
                   } ], 
                   "order": [[ 0, "desc" ]],
                   fixedHeader: {
                       header: true,
                       footer: true
                   },
                   responsive: {details: false},
               });
          });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="js/typeahead.bundle.min.js"></script>
    <script src="js/esi_autocomplete.js"></script>
    <script src="js/bootstrap-contextmenu.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">';

$page = new Page($esimail->getCharacterName().'\'s mailbox');

$page->addBody(mailsPage($esimail));
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>
