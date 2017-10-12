<?php
require_once('config.php');
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

use Swagger\Client\ApiClient;
use Swagger\Client\Configuration;
use Swagger\Client\ApiException;
use Swagger\Client\Api\CalendarApi;

require_once('classes/esi/autoload.php');
require_once('classes/class.esisso.php');

class ESICALENDAR extends ESISSO
{
        private $lastid;
        private $lasttime;

        public function __construct($characterID) {
            parent::__construct(null, $characterID);
            $this->lastid = 0;
            $this->lasttime = strtotime('now');
        }

        public function getCalendarApi() {
            if ($this->hasExpired()) {
                $this->verify();
            }
            $esiapi = new ESIAPI();
            $esiapi->setAccessToken($this->accessToken);
            $calendarapi = new CalendarApi($esiapi);
            return $calendarapi;
        }

        public function getEvents($lastid = 0, $maxpages = 0) {
            $calendarapi = $this-> getCalendarApi();
            $events = array();
            $pages = 0;
            try {
                do {
                    $pages +=1;
                    if ($lastid == -1) {
                        $calfetch = $calendarapi->getCharactersCharacterIdCalendar($this->characterID, 'tranquility');
                    } else {
                        $calfetch = $calendarapi->getCharactersCharacterIdCalendar($this->characterID, 'tranquility', $lastid);
                    }
                    if (count($calfetch)) {
                        foreach ($calfetch as $event) {
                            $events[] = json_decode($event, true);
                        }
                        $lastid = end($events)['event_id'];
                    } else {
                        break;
                    }
                    if ($maxpages > 0 && $pages >= $maxpages) {
                        break;
                    }
                } while (count($calfetch));
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not retrieve Calendar Events: '.$e->getMessage().PHP_EOL;
                $this->log->exception($e);
                return null;
            }
            return $events;
        }

        public function getEventsUntil() {
            $calendarapi = $this-> getCalendarApi();
            $events = array();
            try {
                do {
                    if ($lastid == -1) {
                        $calfetch = $calendarapi->getCharactersCharacterIdCalendar($this->characterID, 'tranquility');
                    } else {
                        $calfetch = $calendarapi->getCharactersCharacterIdCalendar($this->characterID, 'tranquility', $lastid);
                    }
                    if (count($calfetch)) {
                        foreach ($calfetch as $event) {
                            $events[] = json_decode($event, true);
                        }
                        $lastid = end($events)['event_id'];
                    } else {
                        break;
                    }
                } while (count($calfetch));
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not retrieve Calendar Events: '.$e->getMessage().PHP_EOL;
                $this->log->exception($e);
                return null;
            }
            return $events;
        }

        public function getEvent($eventID) {
            $calendarapi = $this-> getCalendarApi();
            $event = array();
            try {
                $event = json_decode($calendarapi->getCharactersCharacterIdCalendarEventId($this->characterID, $eventID, 'tranquility'), true);
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not retrieve Calendar Event: '.$e->getMessage().PHP_EOL;
                $this->log->exception($e);
                return null;
            }
            return $event;
        }

        public function rsvpEvent($eventID, $respond) {
            $calendarapi = $this-> getCalendarApi();
            try {
                $calendarapi->putCharactersCharacterIdCalendarEventId($this->characterID, $eventID, ['response' => $respond], 'tranquility');
            } catch (Exception $e) {
                $this->error = true;
                $this->message = 'Could not respond to Calendar Event: '.$e->getMessage().PHP_EOL;
                $this->log->exception($e);
                return null;
            }
            return $respond;
        }
}
