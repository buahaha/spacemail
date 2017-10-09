<?php
require_once('classes/class.esiapi.php');
require_once('classes/class.log.php');

use Swagger\Client\ApiException;
use Swagger\Client\Api\AllianceApi;
use Swagger\Client\Api\CorporationApi;
use Swagger\Client\Api\CharacterApi;
use Swagger\Client\Api\SearchApi;

$cachetime = 600;

if (isset($_GET['q'])) {
    if (strlen($_GET['q']) > 3) {
        $cachefile = 'cache/tt-'.$_GET['q'].'.json';
        if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
            $response = file_get_contents($cachefile);
            header('Content-type: application/json');
            echo $response;
            die();
        }
        $esiapi = new ESIAPI();
        $searchapi = new SearchApi($esiapi);
        try {
            $tempids = json_decode($searchapi->getSearch(array('character', 'corporation', 'alliance'), $_GET['q'], 'tranquility', 'en-us', false), true);
            if (count($tempids)) {
                $result_ary = array();
                foreach($tempids as $cat => $ids) {
                    try {
                        switch($cat) {
                            case 'alliance':
                                $allianceapi = new AllianceApi($esiapi);
                                $results = $allianceapi->getAlliancesNames($ids, 'tranquility');
                                foreach($results as $result) {
                                    $result_ary[] = array('category' => $cat, 'id' => $result->getAllianceId() , 'name' => $result->getAllianceName());
                                }
                                break;
                            case 'corporation':
                                $corpapi = new CorporationApi($esiapi);
                                $results = $corpapi->getCorporationsNames($ids, 'tranquility');
                                foreach($results as $result) {
                                    $result_ary[] = array('category' => $cat, 'id' => $result->getCorporationId() , 'name' => $result->getCorporationName());
                                }
                                break;
                            case 'character':
                                $charapi = new CharacterApi($esiapi);
                                $results = $charapi->getCharactersNames($ids, 'tranquility');
                                foreach($results as $result) {
                                    $result_ary[] = array('category' => $cat, 'id' => $result->getCharacterId() , 'name' => $result->getCharacterName());
                                }
                                break;
                        }
                    } catch (Exception $e) {
                        $log = new LOG('log/esi.log');
                        $log->exception($e);
                        echo('{}');
                        die();
                    }
                    for($i=0; $i<count($result_ary); $i++) {
                        $temp_arr[levenshtein($_GET['q'], $result_ary[$i]['name'])] = $result_ary[$i];
                    }
                    ksort($temp_arr);
                    $response = json_encode(array_values($temp_arr));
                }
                header('Content-type: application/json');
                echo $response;
                if ($response != '{}') {
                    file_put_contents($cachefile, $response, LOCK_EX);
                }
                die();
            } else {
                echo('{}');
                die();
            }
        } catch (Exception $e) {
            echo('{}');
            die();
        }
    } else {
        echo('{}');
        die();
    }
} else {
    echo('{}');
    die();
}

?>
