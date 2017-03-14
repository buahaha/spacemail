<?php
$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  header('Location: '.URL::url_path.'index.php');
  die();
}

if (!isset($_SESSION['isInternal']) || !$_SESSION['isInternal']) {
  header('Location: '.URL::url_path().'index.php');
  die();
}

function getScriptFooter() {
    if (!isset($_SESSION['ajtoken'])) {
        $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
    }
    $html = '<script>$(document).ready(function() {
            var table = $("#membertable").dataTable(
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
                   fixedHeader: {
                       header: true,
                       footer: false
                   },
                   responsive: true,
                   "order": [[ 1, "asc" ]],
               });
               $.fn.editable.defaults.mode = "popup";
               $(".remark").editable({type: "text",
                                      emptytext: "Enter remark",
                                      emptyclass: "custom-editable",
                                      url: "'.URL::url_path().'ajax/aj_changeremark.php",
                                      title: "remark",
                                      success: function(data) {
                                          if (data !== "true") {
                                              BootstrapDialog.show({message: "Something went wrong", type: BootstrapDialog.TYPE_WARNING});
                                          }
                                      }
               });
               $dropdown = $("#contextMenu");
               $(".actionButton").click(function() {
                   var id = $(this).closest("tr").attr("id");
                   $(this).after($dropdown);
                   $dropdown.find(".pending").click({target: $(this), state: "pending", id: id}, ddhandler);
                   $dropdown.find(".approved").click({target: $(this), state: "approved", id: id}, ddhandler);
                   $dropdown.find(".rejected").click({target: $(this), state: "rejected", id: id}, ddhandler);
                   $(this).dropdown();
               });
               function ddhandler(event){
                   var state = event.data.state;
                   var id = event.data.id;
                   $.ajax({
                       type: "POST",
                       url: "'.URL::url_path().'ajax/aj_changestate.php",
                       data: {"charid" : id, "ajtok" : "'.$_SESSION['ajtoken'].'", "state" : state },
                       success:function(data) {
                           if (data !== "true") {
                               BootstrapDialog.show({message: data, type: BootstrapDialog.TYPE_WARNING});
                           } else {
                               event.data.target.text(event.data.state);
                               event.data.target.removeClass("btn-default btn-success btn-danger");
                               if (event.data.state == "approved") {
                                   event.data.target.addClass("btn-success");
                               } else if (event.data.state == "rejected") {
                                   event.data.target.addClass("btn-danger");
                               } else {
                                   event.data.target.addClass("btn-default");
                               }
                           }
                       }
                   });
               }
        });
        function loading(event){
            BootstrapDialog.show({message: "Contacting API servers, please wait...</br><center><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i></center>"});
            return true;
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="js/typeahead.bundle.min.js"></script>
    <script src="js/esi_autocomplete.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">';
    return $html;
}

function getTable() {
    $qry = DB::getConnection();
    $sql = "SELECT members.*, esisso.ownerHash, membercorps.corporationName FROM 
            members LEFT JOIN (pilots INNER JOIN esisso ON pilots.characterID = esisso.characterID) ON members.characterID = pilots.characterID 
            LEFT JOIN membercorps ON members.corporationID = membercorps.corporationID 
            ORDER BY characterName";
    $result = $qry->query($sql);
    $table = '<table id="membertable" class="small table table-striped table-condensed table-hover" cellspacing="0" width="100%">
      <thead>
          <th class="no-sort"></th>
          <th>Pilot</th>
          <th class="no-sort"></th>
          <th>Corp</th>
          <th>Joined</th>
          <th>API Status</th>
          <th class="num">Last Kill</th>
          <th>Last Location</th>
          <th>Last Checked</th>
      </thead>
      <tbody>';
      while ($row = $result->fetch_assoc()) {
          $table .= '<tr id="'.$row['characterID'].'">';
          $table .= '<td><img height="24px" src="https://imageserver.eveonline.com/Character/'.$row['characterID'].'_32.jpg"></td>';
          if ($row['ownerHash'] == '' || $row['ownerHash'] == null) {
              $table .= '<td><a href="info.php?char='.$row['characterID'].'">'.$row['characterName'].'</a></td>';
          } else {
              $table .= '<td><a href="application.php?char_id='.$row['characterID'].'" onclick="return loading()">'.$row['characterName'].'</a></td>';
          }
          $table .= '<td><img height="24px" src="https://imageserver.eveonline.com/Corporation/'.$row['corporationID'].'_32.png"></td>';
          $table .= '<td><a href="info.php?corp='.$row['corporationID'].'">'.$row['corporationName'].'</a></td>';
          $table .= '<td>'.$row['joined'].'</td>';
          if ($row['ownerHash'] == '' || $row['ownerHash'] == null) {
              $table .= '<td><em>No API info</em></td>';
          } else {
              if ($row['lastAPI'] == 'success') {
                  $table .= '<td><span class="btn-xs bg-success">';
              } elseif ($row['lastAPI'] == 'failed') {
                  $table .= '<td><span class="btn-xs bg-danger">';
              }
              $table .= $row['lastAPI'].'</span></td>';
          }
          if ($row['lastKillID'] == 0 || $row['lastKillID'] == null) {
              $table .= '<td data-sort="99999"><em><span class="btn-xs bg-danger">never</span></em></td>';
          } else {
              $ago = round((strtotime("now")-strtotime($row['lastKill']))/(60*60*24));
              if ($ago > 14 && $ago <= 28) {
                  $class = ' btn-warning';
              } elseif ($ago > 28) {
                  $class = ' btn-danger';
              } else {
                  $class = '';
              }
              $table .= '<td data-sort="'.$ago.'"><a class="btn-xs'.$class.'" href="https://zkillboard.com/kill/'.$row['lastKillID'].'/" target="_blank">'.$ago.' day(s) ago</a></td>';
          }
          $locationHist = @json_decode($row['lastLocation'], true);
          $title = '';
          if (count($locationHist)) {
              $lastlocation = reset($locationHist);
              foreach($locationHist as $date => $loc) {
                  $title .= date('y/m/d', strtotime($date)).": ".$loc."\n";
              }
          } else {
              $lastlocation = null;
          }
          $table .= '<td title="'.$title.'">'.$lastlocation.'</td>';
          $table .= '<td>'.$row['lastCheck'].'</td>';
          $table .= '</tr>';
      }
    $table .= '</tbody>
    </table>';
    return $table;
}

$html = '';
$page = new Page('Current Members');
$page->addHeader('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">');
$page->addBody(getTable());
$page->addFooter(getScriptFooter());
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display();
exit;
?>
