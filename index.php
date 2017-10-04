<?php

$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

function getMailBoxes($esimail) {
    $labels = $esimail->getMailLabels();
    $table = '<ul id="boxlinks" class="nav nav-pills nav-stacked">
                   <li class="spacer hidden-xs"><em>Mail boxes:</em></li>';
        if (null == URL::getQ('label')) {
            if($labels && isset($labels[1])) {
                $l = array_keys($labels)[1];
            } else {
                $l = null;
            }
        } else {
            $l = URL::getQ('label');
        }
        $ml = URL::getQ('mlist');
        foreach ((array)$labels as $k => $label) {
            $table .= '<li'.($l==$k && null == $ml?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label='.$k.'" onclick="return loading()">'.str_replace(array("]", "["), "", $label['name']).($label['unread']?'<span class="badge badge-unread">'.$label['unread'].'</span>':'').'</a></li>';
        }
    $mlists =  $esimail->getMailingLists();
    if (count($mlists)) {
        $table .= '<li class="spacer hidden-xs" style="margin-top: 20px;"><em>Mailing lists:</em></li>';
        foreach ($mlists as $id => $mlist) {
            $table .= '<li'.($l==0 && $ml == $id?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label=0&mlist='.$id.'" onclick="return loading()">'.$mlist.'</a></li>';
        }
    }
    $table .= '</ul>';
    return $table;
}

function mailsPage($esimail) {
    $table = '<div class="row"><div class="col-sm-12 col-md-3 col-lg-2" id="mailboxes">';
    $labels = $esimail->getMailLabels();
    $table .= '<ul id="boxlinks" class="nav nav-pills nav-stacked">
                   <li class="spacer hidden-xs"><em>Mail boxes:</em></li>';
        if (null == URL::getQ('label')) {
            if($labels && isset($labels[1])) {
                $l = array_keys($labels)[1];
            } else {
                $l = null;
            }
        } else {
            $l = URL::getQ('label');
        }
        $ml = URL::getQ('mlist');
        foreach ((array)$labels as $k => $label) {
            $table .= '<li'.($l==$k && null == $ml?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label='.$k.'" onclick="return loading()">'.str_replace(array("]", "["), "", $label['name']).($label['unread']?'<span class="badge badge-unread">'.$label['unread'].'</span>':'').'</a></li>';
        }
    $mlists =  $esimail->getMailingLists();
    if (count($mlists)) {
        $table .= '<li class="spacer hidden-xs" style="margin-top: 20px;"><em>Mailing lists:</em></li>';
        foreach ($mlists as $id => $mlist) {
            $table .= '<li'.($l==0 && $ml == $id?' class="active"':'').'><a style="padding: 7px 7px;" href="index.php?p=mail&label=0&mlist='.$id.'" onclick="return loading()">'.$mlist.'</a></li>';
        }
    } 
    $table .= '</ul>';
    $table .= '</div><div class="col-sm-12 hidden-md hidden-lg" style="height: 20px"></div>
    <div class="col-sm-12 col-sm-9 col-lg-10">
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
          var mlist = '.($ml == null?'null':$ml).';
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
              isreadcol = $(link.closest("tr")).find("i");
              if (isreadcol.hasClass("fa-envelope-o")) {
                  isreadcol.removeClass("fa-envelope-o");
                  isreadcol.addClass("fa-envelope-open-o");
                  box = $("#boxlinks").children(".active");
                  badge = $(box.find(".badge-unread"));
                  if (badge.length) {
                      if (badge.text() == "1") {
                          badge.remove();
                      } else {
                          badge.text(parseInt(badge.text() - 1));
                      }
                  }
                  allbox = $("#boxlinks").children("li:contains(\'All\')");
                  allbadge = $(allbox.find(".badge-unread"));
                  if (allbadge.length) {
                      if (allbadge.text() == "1") {
                          allbadge.remove();
                      } else {
                          allbadge.text(parseInt(allbadge.text() - 1));
                      }
                  }
              }
          }
      </script>
      ';
      return $table;
}

$esimail = new ESIMAIL($_SESSION['characterID']);

$footer = '<script>
          var lastid;
          var mtable;
          var pages;
          var newest;';
if (isset($_SESSION["reload"])) {
    $reload = $_SESSION["reload"];
} elseif (isset($_COOKIE["spacemailreload"])) {
    $reload = $_COOKIE["spacemailreload"];
    $_SESSION["reload"] = $reload;
} else {
    $reload = false;
}
if ($reload) {
    $footer .= '          var reload = true;';
} else {
    $footer .= '          var reload = false;';
}
if (isset($_SESSION["notify"])) {
    $notify = $_SESSION["notify"];
} elseif (isset($_COOKIE["spacemailnotify"])) {
    $notify = $_COOKIE["spacemailnotify"];
    $_SESSION["notify"] = $notify;
} else {
    $notify = false;
}
if ($notify) {
    $footer .= '          var notify = true;';
} else {
    $footer .= '          var notify = false;';
}

$footer .= '          function getmore() {
              $.ajax({
                  url: "fetchmails.php?label="+label+"&lastid="+lastid+"&pages="+pages+mlstring,
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
            if (mlist != undefined) {
                pages = 5
                mlstring = "&mlist="+mlist;
            } else {
                pages = 1
                mlstring = "";
            }
            mtable = $(".jdatatable").DataTable(
               {
                   "ajax": {
                       "url": "fetchmails.php?label="+label+"&pages="+pages+mlstring,
                       "dataSrc": function ( json ) {
                           lastid = json.lastid;
                           if (label == 0) {
                               newest = json.firstid;
                           } else if (reload) {
                               $.ajax({
                                   url: "fetchmails.php?label=0",
                                   success: function(data) {
                                       newest = JSON.parse(data).firstid;
                                   }
                               });
                           }
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
         function doReload() {
             if (newest) {
                 $.ajax({
                     url: "fetchmails.php?label=0",
                     success: function(data) {
                         json2 = JSON.parse(data);
                         new_newest = json2.firstid;
                         unread = json2.unread;
                         if (new_newest > newest) {
                             newest = new_newest;
                             if (notify) {
                                 if (Notification.permission !== "granted")
                                     Notification.requestPermission();
                                 else {
                                     var notification = new Notification("You got EVE mail!", {
                                         icon: "https://spacemail.tk/img/spacemail.png",
                                         body: "You got mail. Currently you have unread "+unread+" mails.",
                                     });
                                 }
                             }
                             if (reload) {
                                 mtable.ajax.reload()
                                 $.ajax({
                                     url: "index.php?p=mail&label="+label+"&mailboxes_only=1",
                                     success: function(data) {
                                         $("#mailboxes").html(data);
                                     }
                                 });
                             }
                         }
                     }
                 });
                 if (reload || notify) {
                     setTimeout(doReload, 180000);
                 }
             }
         }
         if (reload || notify) {
             setTimeout(doReload, 180000);    
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

if (true == URL::getQ('mailboxes_only')) {
    echo getMailBoxes($esimail);
    die;
}

$page = new Page($esimail->getCharacterName().'\'s mailbox');

$page->addBody(mailsPage($esimail));
$page->addFooter($footer);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display("true");
?>
