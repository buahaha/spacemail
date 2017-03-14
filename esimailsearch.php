<?php
require_once('classes/class.esiapi.php');
use Swagger\Client\ApiException;
use Swagger\Client\Api\UniverseApi;
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
            $ids = array();
            foreach ($tempids as $ary) {
              $ids = array_merge($ids, $ary);
            }
            if (count($ids)) {
                $universeapi = new UniverseApi($esiapi);
                //$ids = new \Swagger\Client\Model\PostUniverseNamesIds(array('ids' => $charids['character']));
                $result_ary = array();
                try {
                    $results = $universeapi->postUniverseNames($ids, 'tranquility');
                    foreach ($results as $result) {
                        $result_ary[] = array('category' => $result->getCategory(), 'id' => $result->getId() , 'name' => $result->getName());
                    }
                    
                    for($i=0; $i<count($result_ary); $i++) {
                       $temp_arr[levenshtein($_GET['q'], $result_ary[$i]['name'])] = $result_ary[$i];
                    }
                    ksort($temp_arr);
                    $response = json_encode(array_values($temp_arr));
                } catch (Exception $e) {
                    print $e->getMessage();
                    echo('{}');
                    die();
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
