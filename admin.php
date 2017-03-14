<?php
$start_time = microtime(true);
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  header('Location: '.URL::url_path.'index.php');
  die();
}

if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
  header('Location: '.URL::url_path().'index.php');
  die();
}

if (!isset($_SESSION['ajtoken'])) {
  $_SESSION['ajtoken'] = EVEHELPERS::random_str(32);
}

$html = '';
$page = new Page('Admin Panel');

$recruiters = DBH::getRecruiters();
$corps = DBH::getMemberCorps();
$internals = DBH::getInternals();

$html = '<div class="row"><div class="col-xs-12 col-md-10 col-lg-8">
           <h5>Recruiters</h5>
           <table class="small table table-striped table-condensed table-hover" cellspacing="0" width="100%">
             <thead>
               <tr>
                 <th class="col-xs-1"></th>
                 <th class="col-xs-7">Name</th>
                 <th class="col-xs-2">Notify</th>
                 <th class="col-xs-1"></th>
               </tr>
             </thead>
             <tbody>';
             foreach($recruiters as $recruiter) {
               $html .= '<tr id="'.$recruiter['characterID'].'"><td><img height="24px" src="https://imageserver.eveonline.com/Character/'.$recruiter['characterID'].'_32.jpg"></td>';
               $html .= '<td class="name">'.$recruiter['characterName'].'</td>';
               $html .= '<td><input type="checkbox" value="'.$recruiter['characterID'].'"'.($recruiter['notify']?' checked':'').' onchange="changenotify(this)"></input></td>';
               $html .= '<td><button type="button" class="btn btn-link btn-default btn-xs" onclick="delrecruiter(this)"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
             }
$html .= '   </tbody>
           </table>
         </div></div>
         <div class="row">
           <div class="tt-pilot form-group col-xs-12">
             <label for="inv-name" class="control-label">Add Recruiter:</label>
             <input id="inv-name" type="text" class="typeahead pilot form-control">
             <input id="inv-id" type="hidden" values="">
             <button type="button" id="inv-button" class="tt-btn btn btn-primary disabled"><span class="glyphicon glyphicon-plus"></span></button>
           </div>
         </div>
         <div class="row"><div class="col-xs-12 col-md-10 col-lg-8">
           <h5>Mailing characters</h5>
           <p>Character used to send notifications to Recruiters:</p>
             <div class="row"><div class="col-xs-12 col-md-6 col-lg-4">';
           $mail_int_id = DBH::getConfig('mail_int_id');
           if ($mail_int_id) {
               $mailint = new ESIPILOT($mail_int_id, false, true);
               if ($mailint->getError()) {
                   $page->addError($mailint->getMessage());
               }
               $html .= '<div style="display: inline; float: left;"><img class="img img-rounded" style="margin-right: 10px;" src="https://imageserver.eveonline.com/Character/'.$mail_int_id.'_32.jpg"></div><div style="display: inline">'.$mailint->getCharacterName().'</br><p class="small">Balance: '.number_format($mailint->getBalance()).' ISK</p></div>';
           } else {
               $html .= '<p>None yet.</p>';
           }
$html .=   '  </div><div class="col-xs-12 col-md-6 col-lg-4"><a class="btn btn-primary" href="'.URL::url_path().'login.php?login=regmail_int_logoff'.'">Change</a></div></div>
            <div class="col-xs-12" style="height: 10px;"></div>
            <p>Character used for outside communication:</p>
              <div class="row"><div class="col-xs-12 col-md-6 col-lg-4">';
           $mail_ext_id = DBH::getConfig('mail_ext_id');
           if ($mail_ext_id) {
               $mailext = new ESIPILOT($mail_ext_id, false, true);
               if ($mailext->getError()) {
                   $page->addError($mailext->getMessage());
               }
               $html .= '<div style="display: inline; float: left;"><img class="img img-rounded" style="margin-right: 10px;" src="https://imageserver.eveonline.com/Character/'.$mail_ext_id.'_32.jpg"></div><div style="display: inline">'.$mailext->getCharacterName().'</br><p class="small">Balance: '.number_format($mailext->getBalance()).' ISK</p></div>';
               //print_r($mailext->getBalance());
           } else {
               $html .= '<p>None yet.</p>';
           }
           $html .= '</div><div class="col-xs-12 col-md-6 col-lg-3"><a class="btn btn-primary" href="'.URL::url_path().'login.php?login=regmail_ext_logoff'.'">Change</a></div></div></div></div>
           <div class="row"><div class="col-xs-12 col-md-10 col-lg-8">
           <h5>Member Corps</h5>
           <table class="small table table-striped table-condensed table-hover" cellspacing="0" width="100%">
             <thead>
               <tr>
                 <th class="col-xs-2"></th>
                 <th class="col-xs-9">Name</th>
                 <th class="col-xs-1"></th>
               </tr>
             </thead>
             <tbody>';
             foreach($corps as $corp) {
               $html .= '<tr id="'.$corp['corporationID'].'"><td><img height="24px" src="https://imageserver.eveonline.com/Corporation/'.$corp['corporationID'].'_32.png"></td>';
               $html .= '<td class="name">'.$corp['corporationName'].'</td>';
               $html .= '<td><button type="button" class="btn btn-link btn-default btn-xs" onclick="delmembercorp(this)"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
             }
$html .= '   </tbody>
           </table>
         </div></div>
         <div class="row">
           <div class="tt-pilot form-group col-xs-12">
             <label for="crp-name" class="control-label">Add Member Corp:</label>
             <input id="crp-name" type="text" class="typeahead corp form-control">
             <input id="crp-id" type="hidden" values="">
             <button type="button" id="crp-button" class="tt-btn btn btn-primary disabled"><span class="glyphicon glyphicon-plus"></span></button>
           </div>
         </div>
         <div class="row"><div class="col-xs-12 col-md-10 col-lg-8">
           <h5>Internal auditors</h5>
           <table class="small table table-striped table-condensed table-hover" cellspacing="0" width="100%">
             <thead>
               <tr>
                 <th class="col-xs-1"></th>
                 <th class="col-xs-7">Name</th>
                 <th class="col-xs-2">Notify</th>
                 <th class="col-xs-1"></th>
               </tr>
             </thead>
             <tbody>';
             foreach($internals as $int) {
               $html .= '<tr id="'.$int['characterID'].'"><td><img height="24px" src="https://imageserver.eveonline.com/Character/'.$int['characterID'].'_32.jpg"></td>';
               $html .= '<td class="name">'.$int['characterName'].'</td>';
               $html .= '<td><input type="checkbox" value="'.$int['characterID'].'"'.($int['notify']?' checked':'').' onchange="changeintnotify(this)"></input></td>';
               $html .= '<td><button type="button" class="btn btn-link btn-default btn-xs" onclick="delinternal(this)"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
             }
$html .= '   </tbody>
           </table>
         </div></div>
         <div class="row">
           <div class="tt-pilot form-group col-xs-12">
             <label for="inv2-name" class="control-label">Add internal Auditor:</label>
             <input id="inv2-name" type="text" class="typeahead pilot2 form-control">
             <input id="inv2-id" type="hidden" values="">
             <button type="button" id="inv2-button" class="tt-btn btn btn-primary disabled"><span class="glyphicon glyphicon-plus"></span></button>
           </div>
         </div>';


$html2 = '<script src="js/typeahead.bundle.min.js"></script>
         <script src="js/esi_autocomplete.js"></script>
         <script src="js/bootstrap-dialog.min.js"></script>
         <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
         <script>
         function changenotify(cb) {
             var state = cb.checked;
             var char_id = $(cb).attr("value");
             $.ajax({
                 type: "POST",
                 url: "'.URL::url_path().'ajax/aj_changenotify.php",
                 data: {"charid" : char_id, "ajtok" : "'.$_SESSION['ajtoken'].'", "state" : state},
                 success:function(data)
                 {
                   if (data !== "true") {
                       BootstrapDialog.show({message: "something went wrong", type: BootstrapDialog.TYPE_WARNING});
                   }
                 }
                 });
         }
         function delrecruiter(btn) {
             var row = btn.closest("tr");
             var char_id = $(row).attr("id");
             var name = $(row).children(".name").text();
             BootstrapDialog.show({
                  message: "Are you sure you want to remove "+name+" from the list of Recruiters?",
                  buttons: [{
                      label: "Remove",
                      action: function(dialogItself){
                          dialogItself.close();
                          $.ajax({
                              type: "POST",
                              url: "'.URL::url_path().'ajax/aj_delrecruiter.php",
                              data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "charid" : char_id},
                              success:function(data) {
                                  if (data == "false") {
                                      BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                                  } else {
                                      BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
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

         function delmembercorp(btn) {
             var row = btn.closest("tr");
             var corp_id = $(row).attr("id");
             var name = $(row).children(".name").text();
             BootstrapDialog.show({
                  message: "Are you sure you want to remove "+name+" from the list of Member Corps?",
                  buttons: [{
                      label: "Remove",
                      action: function(dialogItself){
                          dialogItself.close();
                          $.ajax({
                              type: "POST",
                              url: "'.URL::url_path().'ajax/aj_delmembercorp.php",
                              data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "corpid" : corp_id},
                              success:function(data) {
                                  if (data == "false") {
                                      BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                                  } else {
                                      BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
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

         function delinternal(btn) {
             var row = btn.closest("tr");
             var char_id = $(row).attr("id");
             var name = $(row).children(".name").text();
             BootstrapDialog.show({
                  message: "Are you sure you want to remove "+name+" from the list of Recruiters?",
                  buttons: [{
                      label: "Remove",
                      action: function(dialogItself){
                          dialogItself.close();
                          $.ajax({
                              type: "POST",
                              url: "'.URL::url_path().'ajax/aj_delinternal.php",
                              data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "charid" : char_id},
                              success:function(data) {
                                  if (data == "false") {
                                      BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                                  } else {
                                      BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
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

         $(document).ready(function() {
           $( "#inv-button" ).click(function() {
             var char_id = $("#inv-id").val();
             var char_name = $("#inv-name").val();
             $.ajax({
                 type: "POST",
                 url: "'.URL::url_path().'ajax/aj_addrecruiter.php",
                 data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "charid" : char_id, "name" : char_name },
                 success:function(data) {
                     if (data == "false") {
                         BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                     } else {
                         BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
                     }
                 }
             });
           });

           $( "#inv2-button" ).click(function() {
             var char_id = $("#inv2-id").val();
             var char_name = $("#inv2-name").val();
             $.ajax({
                 type: "POST",
                 url: "'.URL::url_path().'ajax/aj_addinternal.php",
                 data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "charid" : char_id, "name" : char_name },
                 success:function(data) {
                     if (data == "false") {
                         BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                     } else {
                         BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
                     }
                 }
             });
           });


           $( "#crp-button" ).click(function() {
             var corp_id = $("#crp-id").val();
             var corp_name = $("#crp-name").val();
             $.ajax({
                 type: "POST",
                 url: "'.URL::url_path().'ajax/aj_addmembercorp.php",
                 data: {"ajtok" : "'.$_SESSION['ajtoken'].'", "corpid" : corp_id, "name" : corp_name },
                 success:function(data) {
                     if (data == "false") {
                         BootstrapDialog.show({message: "Something went wrong...", type: BootstrapDialog.TYPE_WARNING});
                     } else {
                         BootstrapDialog.show({message: data, onhide: function(){location.reload();}});
                     }
                 }
             });
           });
         });
         </script>';
$page->addHeader('<link href="css/typeaheadjs.css" rel="stylesheet">');
$page->addBody($html);
$page->addFooter($html2);
$page->setBuildTime(number_format(microtime(true) - $start_time, 3));
$page->display();
exit;
?>
