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
        $searchapi = $esiapi->getApi('Search');
        try {
            $tempids = json_decode($searchapi->getSearch(array('character', 'corporation', 'alliance'), $_GET['q'], 'tranquility', 'en-us', 0), true);
            if (count($tempids)) {
                $result_ary = array();
                foreach($tempids as $cat => $_ids) {
                    try {
                        switch($cat) {
                            case 'alliance':
                                $allianceapi = $esiapi->getApi('Alliance');
                                foreach (array_chunk($_ids, 80) as $ids) {
                                    $promise[] = $allianceapi->getAlliancesNamesAsync($ids, 'tranquility');
                                }
                                break;
                            case 'corporation':
                                $corpapi = $esiapi->getApi('Corporation');
                                foreach (array_chunk($_ids, 80) as $ids) {
                                    $promise[] = $corpapi->getCorporationsNamesAsync($ids, 'tranquility');
                                }
                                break;
                            case 'character':
                                $charapi = $esiapi->getApi('Character');
                                foreach (array_chunk($_ids, 80) as $ids) {
                                    $promise[] = $charapi->getCharactersNamesAsync($ids, 'tranquility');
                                }
                                break;
                        }
                    } catch (Exception $e) {
                        $log = new LOG('log/esi.log');
                        $log->exception($e);
                        echo('{}');
                        die();
                    }
                }
                $responses = GuzzleHttp\Promise\settle($promise)->wait();
                foreach ($responses as $response) {
                    if ($response['state'] == 'fulfilled') {
                        foreach ($response['value'] as $r) {
                            switch(get_class($r)) {
                                case 'Swagger\Client\Model\GetAlliancesNames200Ok':
                                    $result_ary[] = array('category' => 'alliance', 'id' => $r->getAllianceId() , 'name' => $r->getAllianceName());
                                    break;
                                case 'Swagger\Client\Model\GetCorporationsNames200Ok':
                                    $result_ary[] = array('category' => 'corporation', 'id' => $r->getCorporationId() , 'name' => $r->getCorporationName());
                                    break;
                                case 'Swagger\Client\Model\GetCharactersNames200Ok':
                                    $result_ary[] = array('category' => 'character', 'id' => $r->getCharacterId() , 'name' => $r->getCharacterName());
                                    break;
                            }
                        }
                    } elseif ($response['state'] == 'rejected') {
                        if(!isset($log)) {
                            $log = new LOG('log/esi.log');
                        }
                        $log->exception($response['reason']);
                    }
                }
                if (!count($result_ary)) {
                    echo('{}');
                    die();
                }
                for($i=0; $i<count($result_ary); $i++) {
                    $temp_arr[levenshtein($_GET['q'], $result_ary[$i]['name'])] = $result_ary[$i];
                }
                ksort($temp_arr);
                $response = json_encode(array_values($temp_arr));
                header('Content-type: application/json');
                echo $response;
                if ($response != '{}') {
                    file_put_contents($cachefile, $response, LOCK_EX);
                }
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
