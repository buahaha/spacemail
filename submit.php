<?php
require_once('auth.php');
require_once('config.php');
require_once('loadclasses.php');

if (isset($_POST['addAlt'])) {
    if(!isset($_POST['form']['alts'])) {
        $_POST['form']['alts'] = array();
    }
    $_SESSION['form'] = $_POST['form'];
    header('Location: '.URL::url_path().'login.php?login=regalt_logoff');   
} elseif (isset($_POST['submit'])) {
    $pf=($_POST['form']);
    $app = DBH::getApplication($pf['characterID']);
    if ($app) {
        if ($app['status'] == 'approved') {
            $page->setInfo('Your application has already beeen approved, you should get informed by out recruitement office shortly.');
            $page->display();
            exit;
        } elseif ($app['status'] == 'rejected') {
            $page->setInfo('We are sorry to inform you that your application has been rejected. If you have any further questions, please get in touch with our Recruiters.');
            $page->display();
            exit;
        } else {
            $method = 'update';
        }
    } else {
        $method = 'insert';
    }
    $qry = DB::getConnection();
    if ($method == 'insert') {
        $stmt = $qry->prepare("INSERT INTO applications (characterID,timezone,roles,implants,skills,hearabout,whyleave,additional) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('issiisss', $charID, $tz, $roles, $imps, $sps, $hear, $why, $add);
        }
    } else {
        $stmt = $qry->prepare("UPDATE applications SET timezone=?,roles=?,implants=?,skills=?,hearabout=?,whyleave=?,additional=? WHERE characterID=?");
        if ($stmt) {
            $stmt->bind_param('ssiisssi', $tz, $roles, $imps, $sps, $hear, $why, $add, $charID);
        }
    }
    if ($stmt) {
        if (!isset($pf['additional'])) {
            $pf['additional'] = '';
        }
        $charID = $pf['characterID']; 
        $tz = $pf['timezone'];
        $roles = $pf['roles'];
        if ($pf['implants']=='yes') {
            $imps=1;
        } else {
            $imps=0;
        } 
        $sps = $pf['skills'];
        $hear = $pf['hearaboutus']; 
        $why = $pf['whyleaving'];
        $add = $pf['additional'];
        $stmt->execute();
        if ($stmt->errno) {
            $page = new Page('Submission failed.');
            $page->setError($stmt->error);
            $page->addHeader('<meta http-equiv="refresh" content="3;url='.URL::url_path().'">');
            $page->display();
            exit;
        } else {
            unset($_SESSION['form']);
        }
        $stmt->close();
        if ($method == 'insert') {
            $page = new Page('Application submitted.');
        } else {
            $page = new Page('Application updated.');
        }
        if (isset($pf['alts'])) {
            if (count($pf['alts'])) {
                if ($stmt = $qry->prepare("REPLACE INTO alts (characterID, altID) VALUES (?, ?)")) {
                    $stmt->bind_param('ii', $charID, $altID);
                    foreach ($pf['alts'] as $id => $altName) {
                        $charID = $pf['characterID'];
                        $altID = $id;
                        $stmt->execute();
                    }
                    $stmt->close();
                } else {
                    $page = new Page('Submission failed.');
                    $html = "Ohoh, something went wrong, please report this to us.";
                    $page->setError($html);
                    $page->addHeader('<meta http-equiv="refresh" content="3;url='.URL::url_path().'">');
                    $page->display();
                    exit;
                }
            }
        }
        $html = "Please be patient while we process your application.";
        $page->setInfo($html);
        $page->addHeader('<meta http-equiv="refresh" content="3;url='.URL::url_path().'">');
        $page->display();
        exit;
    } else {
        $page = new Page('Submission failed.');
        $html = "Ohoh, something went wrong, please report this to us.";
        $page->setError($html);
        $page->addHeader('<meta http-equiv="refresh" content="3;url='.URL::url_path().'">');
        $page->display();
        exit;
    }
}
?>
