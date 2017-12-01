<?php
require_once('config.php');

use Swagger\Client\Configuration;
use Swagger\Client\ApiException;
use Swagger\Client\Api\AllianceApi;
use Swagger\Client\Api\CorporationApi;
use Swagger\Client\Api\CharacterApi;

require_once('classes/esi/autoload.php');
require_once('classes/class.esisso.php');

class ESINOTIFICATIONS extends ESISSO
{

        public function __construct($characterID) {
            parent::__construct(null, $characterID);
        }
         
        public function getNotificationApi() {
            if ($this->hasExpired()) {
                $this->verify();
            }
            $esiapi = new ESIAPI();
            $esiapi->setAccessToken($this->accessToken);
            $notificationapi = $esiapi->getApi('Character');
            return $notificationapi;
        }

        public function getNotifications() {
            $notificationsapi = $this->getNotificationApi();
            $notifications = array();
            $i = 0;
            try {
                $fetch = $notificationsapi->getCharactersCharacterIdNotifications($this->characterID, 'tranquility');
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not retrieve Notifications: '.$e->getMessage().PHP_EOL;
                $this->log->exception($e);
                return null;
            }
            if (!count($fetch)) {
                return null;
            }
            foreach ($fetch as $n) {
                $notifications[] = json_decode($n, true);
            }
            return $notifications;
        }
}
