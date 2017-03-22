<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

function mailsPage($esimail) {
    $table = '<div class="row"><div class="col-sm-12 col-md-2 col-lg-1">';
    $labels = $esimail->getMailLabels();
    $labels['0'] = array('name' => 'others', 'unread' => 0);
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
            $table .= '<li'.($l==$k?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label='.$k.'" onclick="return loading()">'.str_replace(array("]", "["), "", $label['name']).($label['unread']?'<span class="badge badge-unread">'.$label['unread'].'</span>':'').'</a></li>';
        } 
    $table .= '</ul>';
    $table .= '</div><div class="col-sm-12 hidden-md hidden-lg" style="height: 20px"></div>
    <div class="col-sm-12 col-sm-10 col-lg-11">
    <table id="mailstable" class="jdatatable table responsive table-striped table-hover" cellspacing="0" width="100%">
      <thead>
          <th class="all">Time</th>';
          if ($l == 2) {
            $table .= '<th>To:</th>
            <th>Subject</th>
            <th class="num no-sort min-mobile-l"></th>';
          } else {
            $table .= '<th class="all no-sort"></th>
            <th class="num no-sort min-tablet-l"></th>
            <th>From:</th>
            <th>Subject</th>
            <th class="min-tablet-l">To:</th>
            <th class="num no-sort min-mobile-l"></th>';
          }
      $table .= '</thead></table></div></div>
      <script>
          var label = '.$l.';
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

if (!isset($_SESSION['ajtoken'])) {
  $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
}

$footer = '<script>
          var lastid;
          var mtable;
          var pages;
          function getmore() {
              $.ajax({
                  url: "fetchmails.php?label="+label+"&lastid="+lastid+"&pages="+pages,
                  success: function(data) {
                      json = JSON.parse(data);
                      mtable.rows.add(json.data).draw();
                      if (json.lastid < lastid) {
                          lastid = json.lastid;
                          if (json.lastid != 0) {
                              getmore();
                          }
                      }
                  }
              });
          }
          $(document).ready(function() {
            if (label == 2) {
                var columns = [{ "data": "date" },{ "data": "to" },{ "data": "subject" }, {"data" : null,"defaultContent": "<a href=\"#\" title=\"Forward mail\" onclick=\"fwdrow(this)\"><i class=\"fa fa-share\" aria-hidden=\"true\"><\/i><\/a>&nbsp;<a href=\"#\" class=\"faa-parent animated-hover\" title=\"Delete mail\" onclick=\"deleterow(this)\"><i class=\"fa fa-trash faa-shake\" aria-hidden=\"true\"><\/i><\/a>", "width": "32px"}]
            } else {
                var columns =  [{ "data": "date" },{ "data": "isread" },{ "data": "img" },{ "data": "from" },{ "data": "subject" },{ "data": "to" },{ "data": null,"defaultContent": "<a href=\"#\" title=\"Reply to\" onclick=\"replyrow(this)\"><i class=\"fa fa-reply\" aria-hidden=\"true\"><\/i><\/a>&nbsp;<a href=\"#\" title=\"Forward mail\" onclick=\"fwdrow(this)\"><i class=\"fa fa-share\" aria-hidden=\"true\"><\/i><\/a>&nbsp;<a href=\"#\" class=\"faa-parent animated-hover\" title=\"Delete mail\" onclick=\"deleterow(this)\"><i class=\"fa fa-trash faa-shake\" aria-hidden=\"true\"><\/i><\/a>", "width": "50px"}]
            }
            if (label == 0) {
                pages = 5 
            } else {
                pages = 1
            }
            mtable = $(".jdatatable").DataTable(
               {
                   "ajax": {
                       "url": "fetchmails.php?label="+label+"&pages="+pages,
                       "dataSrc": function ( json ) {
                           lastid = json.lastid;
                           getmore();
                           return json.data;
                       },
                  },

                   "columns": columns,
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

         function deletemail(id) {
             BootstrapDialog.show({
                  message: "Are you sure you want to delete this mail?",
                  type: BootstrapDialog.TYPE_WARNING,
                  buttons: [{
                      label: "Delete mail",
                      action: function(dialogItself){
                          dialogItself.close();
                          $.ajax({
                              type: "POST",
                              url: "'.URL::url_path().'ajax/aj_deletemail.php",
                              data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "id" : id},
                              success:function(data) {
                                  if (data !== "true") {
                                      BootstrapDialog.show({message: "Something went wrong..."+data, type: BootstrapDialog.TYPE_WARNING});
                                  } else {
                                      var trow = $("#"+id).closest("tr");
                                      mtable.row(trow).remove().draw(false);
                                      BootstrapDialog.closeAll();
                                  }
                              }
                          });
                      }
                  },{
                      label: "Cancel",
                      action: function(dialogItself){
                          dialogItself.close();
                      }
                  }],
             });
         }
         function fwdrow(btn) {
             var trow = $(btn).closest("tr");
             var id = trow.find("a").first().attr("id");
             window.location = "compose.php?action=fwd&mid="+id;
         }
         function deleterow(btn) {
             var trow = $(btn).closest("tr");
             var id = trow.find("a").first().attr("id");
             deletemail(id);
         }
         function replyrow(btn) {
             var trow = $(btn).closest("tr");
             var id = trow.find("a").first().attr("id");
             window.location = "compose.php?action=re&mid="+id;
         }
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
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.min.css" integrity="sha256-C4J6NW3obn7eEgdECI2D1pMBTve41JFWQs0UTboJSTg=" crossorigin="anonymous" />';

$page = new Page($esimail->getCharacterName().'\'s mailbox');

$page->addBody(mailsPage($esimail));
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>
