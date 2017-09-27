<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

function calPage($esicalendar) {
    $events = $esicalendar->getEvents();
    $cal = '';
    if (null == URL::getQ('m') || null == URL::getQ('y')) {
        $m = date('m');
        $y = date('Y');
    } else {
        $m = URL::getQ('m');
        $y = URL::getQ('y');
    }
    $cal .= '<h4>'.date('F Y', strtotime($y.'/'.$m.'/01')).'</h4>';
    $w = (int)date('W', strtotime($y.'/'.$m.'/01'));
    $dto = new DateTime();
    if ($m == 1 && $w > 50) {
        $dto->setISODate($y-1, $w);
    } else {
        $dto->setISODate($y, $w);
    }
    $next = false;
    do {
        $i = 0;
        $cal .= '<div class="row cal-row">';
        do {
            $cal .= '<div class="panel small '.($i > 4?'panel-primary ':'panel-default ').'cal-cell" id="'.$dto->format('Y-m-d').'"'.($dto->format('m') != $m?'style=" opacity: 0.4" ':'').'>
                         <div class="panel-heading"><span class="hidden-xs">'.$dto->format('D').'&nbsp;</span>' .$dto->format('j').'</div>
                         <div class="panel-body tiny">';
                         foreach ($events as $event) {
                             $evdate = new DateTime($event['event_date']);
                             //print $evdate->format('Ymd');
                             if ($evdate->format('Ymd') == $dto->format('Ymd')) {
                                 $cal .= '<a href="#" id="'.$event['event_id'].'" onclick="viewevent(this);" class="cal-event" title="'.$event['title'].'">';
                                 if ($event['event_response'] == 'accepted') {
                                     $cal .= '<span class="glyphicon glyphicon-ok-sign text-success" title="'.str_replace('_', ' ', $event['event_response']).'">&nbsp;</span>';
                                 } elseif ($event['event_response'] == 'declined') {
                                     $cal .= '<span class="glyphicon glyphicon-remove-sign text-danger" title="'.str_replace('_', ' ', $event['event_response']).'">&nbsp;</span>';
                                 } elseif ($event['event_response'] == 'tentative') {
                                     $cal .= '<span class="glyphicon glyphicon-question-sign text-primary" title="'.str_replace('_', ' ', $event['event_response']).'">&nbsp;</span>';
                                 } else {
                                     $cal .= '<span class="glyphicon glyphicon-stop" title="'.str_replace('_', ' ', $event['event_response']).'">&nbsp;</span>';
                                 }
                                 ($event['importance']?$cal .='<span class="text-danger"><b>!</b>&nbsp;</span>':'');
                                 $cal .= $evdate->format('h:i').'<span class="hidden-xs"> '.$event['title'].'</span></a><br />';
                             } elseif ($evdate->format('m') > $dto->format('m')+1) {
                                 continue;
                             }
                         }
                $cal .= '</div>
                     </div>';
            $dto->modify('+1 days');
            $i++;
        } while ($i < 7);
        $cal .= '</div>';
        if (((int)$dto->format('m') > $m && (int)$dto->format('Y') == $y) || (int)$dto->format('Y') > $y) {
            $next = true;
        }
    } while (!$next);
    $cal .= '<div class="row"><div class="pull-right">
        <ul class="pager col-xs-12">
            <li><a href="calendar.php?y='.($m==1?$y-1:$y).'&m='.($m==1?12:$m-1).'"><span class="glyphicon glyphicon-chevron-left"></span>Previous month</a></li>
            <li><a href="calendar.php?y='.($m==12?$y+1:$y).'&m='.($m==12?1:$m+1).'">Next month<span class="glyphicon glyphicon-chevron-right"></span></a></li>
        </ul>
    </div></div>
    <script>
        var dialog;
        function viewevent(link) {
            var id = $(link).attr("id");
            var subject = $(link).attr("title");
            dialog = new BootstrapDialog(
                {message: "Fetching event...</br><center><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></center>",
                title: subject,
                buttons: [{
                    label: "Close",
                    action: function(dialogRef) {
                        dialogRef.close();
                    }
                }],});
            dialog.open();
            console.log(id);
            console.log('.$esicalendar->getCharacterID().');
            $.get("readevent.php?eid="+id+"&cid='.$esicalendar->getCharacterID().'", function(data, status){
                dialog.setMessage(data);
            });
        }
        function rsvp(id, response) {
            $.ajax({
                url: "readevent.php?eid="+id+"&cid='.$esicalendar->getCharacterID().'&rsvp="+response,
                success: function(data) {
                    dialog.setMessage(data);
                }
            });
        }
    </script>';
    return $cal;
}

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['characterID'])) {
  $esicalendar = new ESICALENDAR($_SESSION['characterID']);
  if ($esicalendar->getScopes() == MAIL_SCOPES) {
    $scopesOK = True;
  } else {
    $scopesOK = False;
  }
}

$html = '';

if (!isset($_SESSION['characterID']) || !$scopesOK) {
  $page = new Page('Login required');
  $html .= "<div class='col-xs-12'><br/>You need to log in with your EVE account to acces your calendar. We do NOT get your account credentials. The login button will redirect you to the single sign on page and afterwards back here.<div class='col-xs-12' style='height: 20px'></div><p><a href='login.php?page=".rawurlencode("calendar.php?".URL::getQueryString())."'><img height='32px' src='img/evesso.png'></a><br/><br/>If you would like to know what we use your API information for, please read our <a href='disclaimer.php'>disclaimer</a>.</p></div>";
  $page->addBody($html);
  $page->display();
  exit;
}

if (!isset($_SESSION['ajtoken'])) {
  $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
}

$footer = '<script>
          $(document).ready(function() {
          });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="js/bootstrap-contextmenu.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">';

$page = new Page($esicalendar->getCharacterName().'\'s calendar');

$page->addBody(calPage($esicalendar));
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>
