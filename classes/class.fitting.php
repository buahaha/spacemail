<?php
include_once('config.php');

class FITTING
{
    protected $fitting = array();
    protected $shipID = null;
    protected $highs = array();
    protected $meds = array();
    protected $lows = array();
    protected $rigs = array();
    protected $subsys = array();
    protected $drones = array();
    protected $error = false;
    protected $message = '';

    public function __construct($fit = null) {
      if ($fit != null) {
        $temp = array();
        $tempmods = array();
        $named = false;
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $fit) as $line){
            if (0 === strpos($line, '[') && !$named) {
                $temp[] = array(0 => preg_split('/[\[,]/', $line)[1]);
                $named = true;
            } else {
                if (trim($line) == '') {
                    $temp[] = $tempmods;
                    $tempmods = array();
                } else {
                    $tempmods[] = (preg_split('/[,]/', $line)[0]);
                }
            }
        }
        foreach ($temp as $i => $values) {
            if (count($values)) {
                $qry = DB::getConnection();
                $escapednames = array();
                foreach ($values as $value) {
                    $escapednames[] = $qry->real_escape_string($value);
                }
                $typenames = implode("' OR typeName='",$escapednames);
                $sql="SELECT typeID, typeName FROM invTypes WHERE typeName='".$typenames."'";
                $result = $qry->query($sql);
                while ($row = $result->fetch_row()) {
                    foreach ($values as $name) {
                        if ($row[1] == $name) {
                            switch ($i) {
                               case 0:
                                   $this->shipID = $row[0];
                                   break;
                               case 1:
                                   $this->lows[] = $row[0];
                                   break;
                               case 2:
                                   $this->meds[] = $row[0];
                                   break;
                               case 3:
                                   $this->highs[] = $row[0];
                                   break;
                               case 4:
                                   $this->rigs[] = $row[0];
                                   break;
                               case 5:
                                   $this->subsys[] = $row[0];
                                   break;
                               case 6:
                                   $this->drones[] = $row[0];
                                   break;
        
                            }
                        }
                    }
                }
            }
        }
        if ($this->shipID == null) {
            $this->error = true;
            $this->message = "Fitting could not be parsed";
        }
        $this->fitting['ship'] = $this->shipID;
        $this->fitting['lows'] = $this->lows;
        $this->fitting['meds'] = $this->meds;
        $this->fitting['highs'] = $this->highs;
        $this->fitting['rigs'] = $this->rigs;
        $this->fitting['subsys'] = $this->subsys;
        $this->fitting['drones'] = $this->drones;
      }
    }

    public static function getModGroups($fitting=null) {
        $gids = array();
        $f['ab'] = 0;
        $f['mwd'] = 0;
        $f['scram'] = 0;
        $f['disrupt'] = 0;
        $f['dis_field'] = 0;
        $f['web'] = 0;
        $f['grap'] = 0;
        $f['td'] = 0;
        $f['damp'] = 0;
        $f['paint'] = 0;
        $f['y_jam'] = 0;
        $f['r_jam'] = 0;
        $f['b_jam'] = 0;
        $f['g_jam'] = 0;
        $f['m_jam'] = 0;
        if ($fitting) {
            $qry = DB::getConnection();
            $mods = self::flatten($fitting);
            $sql="SELECT typeID, marketGroupID FROM invTypes WHERE typeID=".implode(" OR typeID=", $mods);
            $result = $qry->query($sql);
            if($result->num_rows) {
                while($row = $result->fetch_row()) {
                    $gid[$row[0]] = $row[1];
                }
                foreach ($mods as $mod) {
                    if (isset($gid[$mod])) {
                        switch($gid[$mod]) {
                            case 542:
                                $f['ab'] += 1;
                                break;
                            case 131:
                                $f['mwd'] += 1;
                                break;
                            case 1936:
                                $f['scram'] += 1;
                                break;
                            case 1935:
                                $f['disrupt'] += 1;
                                break;
                            case 1085:
                                $f['dis_field'] += 1;
                                break;
                            case 683:
                                $f['web'] += 1;
                                break;
                            case 2154:
                                $f['grap'] += 1;
                                break;
                            case 680:
                                $f['td'] += 1;
                                break;
                            case 679:
                                $f['damp'] += 1;
                                break;
                            case 757:
                                $f['paint'] += 1;
                                break;
                            case 718:
                                $f['y_jam'] += 1;
                                break;
                            case 716:
                                $f['r_jam'] += 1;
                                break;
                            case 717:
                                $f['b_yam'] += 1;
                                break;
                            case 715:
                                $f['g_jam'] += 1;
                                break;
                            case 719:
                                $f['m_jam'] += 1;
                                break;
                        }
                    }
                }
            }
        }
        return $f;
    }

    public static function getNames($fitting) {
        $qry = DB::getConnection();
        $sql="SELECT typeID, typeName FROM invTypes WHERE typeID=".implode(" OR typeID=", self::flatten($fitting));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['typeID']] = $row['typeName'];
            }
            return $return;
        } else {
            return null;
        }
    }

    private static function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    public function getShipTypeID() {
        return $this->shipID;
    }

    public function getError() {
        return $this->error;
    }

    public function getMessage() {
        return $this->message;
    }

}
?>
