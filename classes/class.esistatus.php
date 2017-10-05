<?php
require_once('config.php');
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

use Swagger\Client\ApiClient;
use Swagger\Client\Configuration;
use Swagger\Client\ApiException;
use Swagger\Client\Api\StatusApi;

require_once('classes/esi/autoload.php');
require_once('classes/class.esisso.php');


class ESISTATUS extends ESIAPI
{
        protected $log;

        public function __construct() {
            $this->log = new LOG('log/esi.log');
            parent::__construct();
            $this->setMaxTries(1);
        }

        public function getServerStatus() {
            $statusapi = new StatusApi($this);
            try {
                $response = json_decode($statusapi->getStatus('tranquility'), true);
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not fetch Server status: '.$e->getMessage().PHP_EOL;
                $this->log->error($this->message);
                return false;
            }
            return $response;
        }
}
