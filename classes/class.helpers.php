<?php
include_once('config.php');

use Swagger\Client\Api\UniverseApi;
use Swagger\Client\Api\CorporationApi;
use Swagger\Client\Api\AllianceApi;

class EVEHELPERS {

    public static function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    public static function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;

        return $out;
    }


    private static function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    public static function getInvNames($items) {
        $qry = DB::getConnection();
        $sql="SELECT typeID, typeName FROM invTypes WHERE typeID=".implode(" OR typeID=", self::flatten($items));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['typeID']] = utf8_encode($row['typeName']);
            }
            return $return;
        } else {
            return null;
        }
    }

    public static function getInvGroupNames($items) {
        $qry = DB::getConnection();
        $sql="SELECT invTypes.typeID, invGroups.groupName FROM invTypes LEFT JOIN invGroups ON invTypes.groupID = invGroups.groupID
              WHERE typeID=".implode(" OR typeID=", self::flatten($items));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['typeID']] = $row['groupName'];
            }
            return $return;
        } else {
            return null;
        }
    }

    public static function getSystemNames($items) {
        $qry = DB::getConnection();
        $sql="SELECT solarSystemID, solarSystemName FROM mapSolarSystems WHERE solarSystemID=".implode(" OR solarSystemID=", self::flatten($items));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['solarSystemID']] = $row['solarSystemName'];
            }
            return $return;
        } else {
            return null;
        }
    }

    public static function getStructureNames($items) {
        $qry = DB::getConnection();
        $sql="SELECT structureID, structureName FROM structures WHERE structureID=".implode(" OR structureID=", self::flatten($items));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['structureID']] = $row['structureName'];
            }
            return $return;
        } else {
            return null;
        }
    }

    public static function getStationNames($items) {
        $qry = DB::getConnection();
        $sql="SELECT itemID, itemName FROM mapDenormalize WHERE itemID=".implode(" OR itemID=", self::flatten($items));
        $result = $qry->query($sql);
        $return = array();
        if($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $return[$row['itemID']] = $row['itemName'];
            }
            return $return;
        } else {
            return null;
        }
    }

    public static function getTransactionTypes() {
        $esiapi = new ESIAPI();        
        $url = 'https://api.eveonline.com/eve/RefTypes.xml.aspx';
        $cachetime = 60*60*24;
        $cachefile = 'cache/'.md5('refTypes').'.xml.gz';
        if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
            $result = gzdecode(file_get_contents($cachefile));
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $esiapi->getConfig()->getUserAgent());
            curl_setopt($ch, CURLOPT_TIMEOUT, $esiapi->getConfig()->getCurlTimeout());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            $result = curl_exec($ch);
            if ($result === false) {
                curl_close($ch);
                return Array();
            }
            curl_close($ch);
            file_put_contents($cachefile, gzencode($result, 9), LOCK_EX);
        }
        $xml = simplexml_load_string($result);
        $array = json_decode(json_encode((array)$xml), TRUE);
        if (isset($array['result']['rowset']['row'])) {
            $dict = array();
            $rows = $array['result']['rowset']['row'];
            foreach ($rows as $row) {
                $dict[$row['@attributes']['refTypeID']] = $row['@attributes']['refTypeName'];
            }
            return $dict;
        } else {
            return Array();
        }
    }

    public static function esiIdsToNames($ids) {
        $lookup = array();
        foreach($ids as $key=>$val) {
            $lookup[$val] = true;
        }
        $lookup = array_keys($lookup);
        $esiapi = new ESIAPI();
        $universeapi = new UniverseApi($esiapi);
        try {
            $results = $universeapi->postUniverseNames($lookup, 'tranquility');
        } catch (Exception $e) {
            return null;
        }
        $dict = array();
        foreach($results as $r) {
            $dict[$r->getId()] = $r->getName();
        }
        return $dict;
    }

    public static function getCorpForChar($characterID) {
        $esiapi = new ESIAPI();
        $charapi = new CharacterApi($esiapi);
        try {
            $charinfo = json_decode($charapi->getCharactersCharacterId($characterID, 'tranquility'));
            $corpID = $charinfo->corporation_id;
        } catch (Exception $e) {
            $corpID = null;
        }
        return $corpID;
    }

    public static function getCorpInfo($corpID) {
        $esiapi = new ESIAPI();
        $corpapi = new CorporationApi($esiapi);
        try {
            $corpinfo = json_decode($corpapi->getCorporationsCorporationId($corpID, 'tranquility'));
        } catch (Exception $e) {
            $corpinfo = null;
        }
        return $corpinfo;
    }


    public static function getAllyForCorp($corpID) {
        $esiapi = new ESIAPI();
        $corpapi = new CorporationApi($esiapi);
        try {
            $corpinfo = json_decode($corpapi->getCorporationsCorporationId($corpID, 'tranquility'));
            if (isset($corpinfo->alliance_id)) {
                $allyID = $corpinfo->alliance_id;
            } else {
                $allyID = null;
            }
        } catch (Exception $e) {
            $allyID = null;
        }
        return $allyID;
    }

    public static function getAllyInfo($allyID) {
        $esiapi = new ESIAPI();
        $allyapi = new AllianceApi($esiapi);
        try {
            $allyinfo = json_decode($allyapi->getAlliancesAllianceId($allyID, 'tranquility'));
        } catch (Exception $e) {
            $allyinfo = null;
        }
        return $allyinfo;
    }

    public static function getAllyHistory($corpid) {
        $esiapi = new ESIAPI();
        $corpapi = new CorporationApi($esiapi);
        $allys = array();
        $lookup = array();
        try {
            $allyHist = ($corpapi->getCorporationsCorporationIdAlliancehistory($corpid, 'tranquility'));
            if (count($allyHist)) {
                foreach($allyHist as $a) {
                    $ally = $a->getAlliance();
                    $temp=array();
                    if ($ally) {
                        $temp['id'] = $ally->getAllianceId();
                        $lookup[$ally->getAllianceId()] = null;
                    }
                    $temp['joined'] = date_format($a->getStartDate(), 'Y-m-d h:i:s');
                    $allys[]=$temp;
                }
            }
            if (count($lookup)) {
                $allyapi = new AllianceApi($esiapi);
                $results = $allyapi->getAlliancesNames(array_keys($lookup), 'tranquility');
                foreach($results as $result) {
                    $lookup[$result->getAllianceId()] = $result->getAllianceName();
                }
                foreach($allys as $i => $ally) {
                    if (isset($ally['id'])) {
                        $allys[$i]['name'] = $lookup[$ally['id']];
                    }
                }
            }
        } catch (Exception $e) {
            $allys = null;
        }
        return $allys;
    }

}
?>
