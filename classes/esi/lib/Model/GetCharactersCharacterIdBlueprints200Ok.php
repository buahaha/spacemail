<?php
/**
 * GetCharactersCharacterIdBlueprints200Ok
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * EVE Swagger Interface
 *
 * An OpenAPI for EVE Online
 *
 * OpenAPI spec version: 0.8.3
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;
use \Swagger\Client\ObjectSerializer;

/**
 * GetCharactersCharacterIdBlueprints200Ok Class Doc Comment
 *
 * @category Class
 * @description 200 ok object
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class GetCharactersCharacterIdBlueprints200Ok implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'get_characters_character_id_blueprints_200_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'item_id' => 'int',
        'location_flag' => 'string',
        'location_id' => 'int',
        'material_efficiency' => 'int',
        'quantity' => 'int',
        'runs' => 'int',
        'time_efficiency' => 'int',
        'type_id' => 'int'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'item_id' => 'int64',
        'location_flag' => null,
        'location_id' => 'int64',
        'material_efficiency' => 'int32',
        'quantity' => 'int32',
        'runs' => 'int32',
        'time_efficiency' => 'int32',
        'type_id' => 'int32'
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'item_id' => 'item_id',
        'location_flag' => 'location_flag',
        'location_id' => 'location_id',
        'material_efficiency' => 'material_efficiency',
        'quantity' => 'quantity',
        'runs' => 'runs',
        'time_efficiency' => 'time_efficiency',
        'type_id' => 'type_id'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'item_id' => 'setItemId',
        'location_flag' => 'setLocationFlag',
        'location_id' => 'setLocationId',
        'material_efficiency' => 'setMaterialEfficiency',
        'quantity' => 'setQuantity',
        'runs' => 'setRuns',
        'time_efficiency' => 'setTimeEfficiency',
        'type_id' => 'setTypeId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'item_id' => 'getItemId',
        'location_flag' => 'getLocationFlag',
        'location_id' => 'getLocationId',
        'material_efficiency' => 'getMaterialEfficiency',
        'quantity' => 'getQuantity',
        'runs' => 'getRuns',
        'time_efficiency' => 'getTimeEfficiency',
        'type_id' => 'getTypeId'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    const LOCATION_FLAG_AUTO_FIT = 'AutoFit';
    const LOCATION_FLAG_CARGO = 'Cargo';
    const LOCATION_FLAG_CORPSE_BAY = 'CorpseBay';
    const LOCATION_FLAG_DRONE_BAY = 'DroneBay';
    const LOCATION_FLAG_FLEET_HANGAR = 'FleetHangar';
    const LOCATION_FLAG_DELIVERIES = 'Deliveries';
    const LOCATION_FLAG_HIDDEN_MODIFIERS = 'HiddenModifiers';
    const LOCATION_FLAG_HANGAR = 'Hangar';
    const LOCATION_FLAG_HANGAR_ALL = 'HangarAll';
    const LOCATION_FLAG_LO_SLOT0 = 'LoSlot0';
    const LOCATION_FLAG_LO_SLOT1 = 'LoSlot1';
    const LOCATION_FLAG_LO_SLOT2 = 'LoSlot2';
    const LOCATION_FLAG_LO_SLOT3 = 'LoSlot3';
    const LOCATION_FLAG_LO_SLOT4 = 'LoSlot4';
    const LOCATION_FLAG_LO_SLOT5 = 'LoSlot5';
    const LOCATION_FLAG_LO_SLOT6 = 'LoSlot6';
    const LOCATION_FLAG_LO_SLOT7 = 'LoSlot7';
    const LOCATION_FLAG_MED_SLOT0 = 'MedSlot0';
    const LOCATION_FLAG_MED_SLOT1 = 'MedSlot1';
    const LOCATION_FLAG_MED_SLOT2 = 'MedSlot2';
    const LOCATION_FLAG_MED_SLOT3 = 'MedSlot3';
    const LOCATION_FLAG_MED_SLOT4 = 'MedSlot4';
    const LOCATION_FLAG_MED_SLOT5 = 'MedSlot5';
    const LOCATION_FLAG_MED_SLOT6 = 'MedSlot6';
    const LOCATION_FLAG_MED_SLOT7 = 'MedSlot7';
    const LOCATION_FLAG_HI_SLOT0 = 'HiSlot0';
    const LOCATION_FLAG_HI_SLOT1 = 'HiSlot1';
    const LOCATION_FLAG_HI_SLOT2 = 'HiSlot2';
    const LOCATION_FLAG_HI_SLOT3 = 'HiSlot3';
    const LOCATION_FLAG_HI_SLOT4 = 'HiSlot4';
    const LOCATION_FLAG_HI_SLOT5 = 'HiSlot5';
    const LOCATION_FLAG_HI_SLOT6 = 'HiSlot6';
    const LOCATION_FLAG_HI_SLOT7 = 'HiSlot7';
    const LOCATION_FLAG_ASSET_SAFETY = 'AssetSafety';
    const LOCATION_FLAG_LOCKED = 'Locked';
    const LOCATION_FLAG_UNLOCKED = 'Unlocked';
    const LOCATION_FLAG_IMPLANT = 'Implant';
    const LOCATION_FLAG_QUAFE_BAY = 'QuafeBay';
    const LOCATION_FLAG_RIG_SLOT0 = 'RigSlot0';
    const LOCATION_FLAG_RIG_SLOT1 = 'RigSlot1';
    const LOCATION_FLAG_RIG_SLOT2 = 'RigSlot2';
    const LOCATION_FLAG_RIG_SLOT3 = 'RigSlot3';
    const LOCATION_FLAG_RIG_SLOT4 = 'RigSlot4';
    const LOCATION_FLAG_RIG_SLOT5 = 'RigSlot5';
    const LOCATION_FLAG_RIG_SLOT6 = 'RigSlot6';
    const LOCATION_FLAG_RIG_SLOT7 = 'RigSlot7';
    const LOCATION_FLAG_SHIP_HANGAR = 'ShipHangar';
    const LOCATION_FLAG_SPECIALIZED_FUEL_BAY = 'SpecializedFuelBay';
    const LOCATION_FLAG_SPECIALIZED_ORE_HOLD = 'SpecializedOreHold';
    const LOCATION_FLAG_SPECIALIZED_GAS_HOLD = 'SpecializedGasHold';
    const LOCATION_FLAG_SPECIALIZED_MINERAL_HOLD = 'SpecializedMineralHold';
    const LOCATION_FLAG_SPECIALIZED_SALVAGE_HOLD = 'SpecializedSalvageHold';
    const LOCATION_FLAG_SPECIALIZED_SHIP_HOLD = 'SpecializedShipHold';
    const LOCATION_FLAG_SPECIALIZED_SMALL_SHIP_HOLD = 'SpecializedSmallShipHold';
    const LOCATION_FLAG_SPECIALIZED_MEDIUM_SHIP_HOLD = 'SpecializedMediumShipHold';
    const LOCATION_FLAG_SPECIALIZED_LARGE_SHIP_HOLD = 'SpecializedLargeShipHold';
    const LOCATION_FLAG_SPECIALIZED_INDUSTRIAL_SHIP_HOLD = 'SpecializedIndustrialShipHold';
    const LOCATION_FLAG_SPECIALIZED_AMMO_HOLD = 'SpecializedAmmoHold';
    const LOCATION_FLAG_SPECIALIZED_COMMAND_CENTER_HOLD = 'SpecializedCommandCenterHold';
    const LOCATION_FLAG_SPECIALIZED_PLANETARY_COMMODITIES_HOLD = 'SpecializedPlanetaryCommoditiesHold';
    const LOCATION_FLAG_SPECIALIZED_MATERIAL_BAY = 'SpecializedMaterialBay';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT0 = 'SubSystemSlot0';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT1 = 'SubSystemSlot1';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT2 = 'SubSystemSlot2';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT3 = 'SubSystemSlot3';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT4 = 'SubSystemSlot4';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT5 = 'SubSystemSlot5';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT6 = 'SubSystemSlot6';
    const LOCATION_FLAG_SUB_SYSTEM_SLOT7 = 'SubSystemSlot7';
    const LOCATION_FLAG_FIGHTER_BAY = 'FighterBay';
    const LOCATION_FLAG_FIGHTER_TUBE0 = 'FighterTube0';
    const LOCATION_FLAG_FIGHTER_TUBE1 = 'FighterTube1';
    const LOCATION_FLAG_FIGHTER_TUBE2 = 'FighterTube2';
    const LOCATION_FLAG_FIGHTER_TUBE3 = 'FighterTube3';
    const LOCATION_FLAG_FIGHTER_TUBE4 = 'FighterTube4';
    const LOCATION_FLAG_MODULE = 'Module';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getLocationFlagAllowableValues()
    {
        return [
            self::LOCATION_FLAG_AUTO_FIT,
            self::LOCATION_FLAG_CARGO,
            self::LOCATION_FLAG_CORPSE_BAY,
            self::LOCATION_FLAG_DRONE_BAY,
            self::LOCATION_FLAG_FLEET_HANGAR,
            self::LOCATION_FLAG_DELIVERIES,
            self::LOCATION_FLAG_HIDDEN_MODIFIERS,
            self::LOCATION_FLAG_HANGAR,
            self::LOCATION_FLAG_HANGAR_ALL,
            self::LOCATION_FLAG_LO_SLOT0,
            self::LOCATION_FLAG_LO_SLOT1,
            self::LOCATION_FLAG_LO_SLOT2,
            self::LOCATION_FLAG_LO_SLOT3,
            self::LOCATION_FLAG_LO_SLOT4,
            self::LOCATION_FLAG_LO_SLOT5,
            self::LOCATION_FLAG_LO_SLOT6,
            self::LOCATION_FLAG_LO_SLOT7,
            self::LOCATION_FLAG_MED_SLOT0,
            self::LOCATION_FLAG_MED_SLOT1,
            self::LOCATION_FLAG_MED_SLOT2,
            self::LOCATION_FLAG_MED_SLOT3,
            self::LOCATION_FLAG_MED_SLOT4,
            self::LOCATION_FLAG_MED_SLOT5,
            self::LOCATION_FLAG_MED_SLOT6,
            self::LOCATION_FLAG_MED_SLOT7,
            self::LOCATION_FLAG_HI_SLOT0,
            self::LOCATION_FLAG_HI_SLOT1,
            self::LOCATION_FLAG_HI_SLOT2,
            self::LOCATION_FLAG_HI_SLOT3,
            self::LOCATION_FLAG_HI_SLOT4,
            self::LOCATION_FLAG_HI_SLOT5,
            self::LOCATION_FLAG_HI_SLOT6,
            self::LOCATION_FLAG_HI_SLOT7,
            self::LOCATION_FLAG_ASSET_SAFETY,
            self::LOCATION_FLAG_LOCKED,
            self::LOCATION_FLAG_UNLOCKED,
            self::LOCATION_FLAG_IMPLANT,
            self::LOCATION_FLAG_QUAFE_BAY,
            self::LOCATION_FLAG_RIG_SLOT0,
            self::LOCATION_FLAG_RIG_SLOT1,
            self::LOCATION_FLAG_RIG_SLOT2,
            self::LOCATION_FLAG_RIG_SLOT3,
            self::LOCATION_FLAG_RIG_SLOT4,
            self::LOCATION_FLAG_RIG_SLOT5,
            self::LOCATION_FLAG_RIG_SLOT6,
            self::LOCATION_FLAG_RIG_SLOT7,
            self::LOCATION_FLAG_SHIP_HANGAR,
            self::LOCATION_FLAG_SPECIALIZED_FUEL_BAY,
            self::LOCATION_FLAG_SPECIALIZED_ORE_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_GAS_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_MINERAL_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_SALVAGE_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_SHIP_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_SMALL_SHIP_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_MEDIUM_SHIP_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_LARGE_SHIP_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_INDUSTRIAL_SHIP_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_AMMO_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_COMMAND_CENTER_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_PLANETARY_COMMODITIES_HOLD,
            self::LOCATION_FLAG_SPECIALIZED_MATERIAL_BAY,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT0,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT1,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT2,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT3,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT4,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT5,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT6,
            self::LOCATION_FLAG_SUB_SYSTEM_SLOT7,
            self::LOCATION_FLAG_FIGHTER_BAY,
            self::LOCATION_FLAG_FIGHTER_TUBE0,
            self::LOCATION_FLAG_FIGHTER_TUBE1,
            self::LOCATION_FLAG_FIGHTER_TUBE2,
            self::LOCATION_FLAG_FIGHTER_TUBE3,
            self::LOCATION_FLAG_FIGHTER_TUBE4,
            self::LOCATION_FLAG_MODULE,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['item_id'] = isset($data['item_id']) ? $data['item_id'] : null;
        $this->container['location_flag'] = isset($data['location_flag']) ? $data['location_flag'] : null;
        $this->container['location_id'] = isset($data['location_id']) ? $data['location_id'] : null;
        $this->container['material_efficiency'] = isset($data['material_efficiency']) ? $data['material_efficiency'] : null;
        $this->container['quantity'] = isset($data['quantity']) ? $data['quantity'] : null;
        $this->container['runs'] = isset($data['runs']) ? $data['runs'] : null;
        $this->container['time_efficiency'] = isset($data['time_efficiency']) ? $data['time_efficiency'] : null;
        $this->container['type_id'] = isset($data['type_id']) ? $data['type_id'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['item_id'] === null) {
            $invalidProperties[] = "'item_id' can't be null";
        }
        if ($this->container['location_flag'] === null) {
            $invalidProperties[] = "'location_flag' can't be null";
        }
        $allowedValues = $this->getLocationFlagAllowableValues();
        if (!in_array($this->container['location_flag'], $allowedValues)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'location_flag', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['location_id'] === null) {
            $invalidProperties[] = "'location_id' can't be null";
        }
        if ($this->container['material_efficiency'] === null) {
            $invalidProperties[] = "'material_efficiency' can't be null";
        }
        if (($this->container['material_efficiency'] > 25)) {
            $invalidProperties[] = "invalid value for 'material_efficiency', must be smaller than or equal to 25.";
        }

        if (($this->container['material_efficiency'] < 0)) {
            $invalidProperties[] = "invalid value for 'material_efficiency', must be bigger than or equal to 0.";
        }

        if ($this->container['quantity'] === null) {
            $invalidProperties[] = "'quantity' can't be null";
        }
        if (($this->container['quantity'] < -2)) {
            $invalidProperties[] = "invalid value for 'quantity', must be bigger than or equal to -2.";
        }

        if ($this->container['runs'] === null) {
            $invalidProperties[] = "'runs' can't be null";
        }
        if (($this->container['runs'] < -1)) {
            $invalidProperties[] = "invalid value for 'runs', must be bigger than or equal to -1.";
        }

        if ($this->container['time_efficiency'] === null) {
            $invalidProperties[] = "'time_efficiency' can't be null";
        }
        if (($this->container['time_efficiency'] > 20)) {
            $invalidProperties[] = "invalid value for 'time_efficiency', must be smaller than or equal to 20.";
        }

        if (($this->container['time_efficiency'] < 0)) {
            $invalidProperties[] = "invalid value for 'time_efficiency', must be bigger than or equal to 0.";
        }

        if ($this->container['type_id'] === null) {
            $invalidProperties[] = "'type_id' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        if ($this->container['item_id'] === null) {
            return false;
        }
        if ($this->container['location_flag'] === null) {
            return false;
        }
        $allowedValues = $this->getLocationFlagAllowableValues();
        if (!in_array($this->container['location_flag'], $allowedValues)) {
            return false;
        }
        if ($this->container['location_id'] === null) {
            return false;
        }
        if ($this->container['material_efficiency'] === null) {
            return false;
        }
        if ($this->container['material_efficiency'] > 25) {
            return false;
        }
        if ($this->container['material_efficiency'] < 0) {
            return false;
        }
        if ($this->container['quantity'] === null) {
            return false;
        }
        if ($this->container['quantity'] < -2) {
            return false;
        }
        if ($this->container['runs'] === null) {
            return false;
        }
        if ($this->container['runs'] < -1) {
            return false;
        }
        if ($this->container['time_efficiency'] === null) {
            return false;
        }
        if ($this->container['time_efficiency'] > 20) {
            return false;
        }
        if ($this->container['time_efficiency'] < 0) {
            return false;
        }
        if ($this->container['type_id'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets item_id
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->container['item_id'];
    }

    /**
     * Sets item_id
     *
     * @param int $item_id Unique ID for this item.
     *
     * @return $this
     */
    public function setItemId($item_id)
    {
        $this->container['item_id'] = $item_id;

        return $this;
    }

    /**
     * Gets location_flag
     *
     * @return string
     */
    public function getLocationFlag()
    {
        return $this->container['location_flag'];
    }

    /**
     * Sets location_flag
     *
     * @param string $location_flag Type of the location_id
     *
     * @return $this
     */
    public function setLocationFlag($location_flag)
    {
        $allowedValues = $this->getLocationFlagAllowableValues();
        if (!in_array($location_flag, $allowedValues)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'location_flag', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['location_flag'] = $location_flag;

        return $this;
    }

    /**
     * Gets location_id
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->container['location_id'];
    }

    /**
     * Sets location_id
     *
     * @param int $location_id References a solar system, station or item_id if this blueprint is located within a container. If the return value is an item_id, then the Character AssetList API must be queried to find the container using the given item_id to determine the correct location of the Blueprint.
     *
     * @return $this
     */
    public function setLocationId($location_id)
    {
        $this->container['location_id'] = $location_id;

        return $this;
    }

    /**
     * Gets material_efficiency
     *
     * @return int
     */
    public function getMaterialEfficiency()
    {
        return $this->container['material_efficiency'];
    }

    /**
     * Sets material_efficiency
     *
     * @param int $material_efficiency Material Efficiency Level of the blueprint.
     *
     * @return $this
     */
    public function setMaterialEfficiency($material_efficiency)
    {

        if (($material_efficiency > 25)) {
            throw new \InvalidArgumentException('invalid value for $material_efficiency when calling GetCharactersCharacterIdBlueprints200Ok., must be smaller than or equal to 25.');
        }
        if (($material_efficiency < 0)) {
            throw new \InvalidArgumentException('invalid value for $material_efficiency when calling GetCharactersCharacterIdBlueprints200Ok., must be bigger than or equal to 0.');
        }

        $this->container['material_efficiency'] = $material_efficiency;

        return $this;
    }

    /**
     * Gets quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->container['quantity'];
    }

    /**
     * Sets quantity
     *
     * @param int $quantity A range of numbers with a minimum of -2 and no maximum value where -1 is an original and -2 is a copy. It can be a positive integer if it is a stack of blueprint originals fresh from the market (e.g. no activities performed on them yet).
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {

        if (($quantity < -2)) {
            throw new \InvalidArgumentException('invalid value for $quantity when calling GetCharactersCharacterIdBlueprints200Ok., must be bigger than or equal to -2.');
        }

        $this->container['quantity'] = $quantity;

        return $this;
    }

    /**
     * Gets runs
     *
     * @return int
     */
    public function getRuns()
    {
        return $this->container['runs'];
    }

    /**
     * Sets runs
     *
     * @param int $runs Number of runs remaining if the blueprint is a copy, -1 if it is an original.
     *
     * @return $this
     */
    public function setRuns($runs)
    {

        if (($runs < -1)) {
            throw new \InvalidArgumentException('invalid value for $runs when calling GetCharactersCharacterIdBlueprints200Ok., must be bigger than or equal to -1.');
        }

        $this->container['runs'] = $runs;

        return $this;
    }

    /**
     * Gets time_efficiency
     *
     * @return int
     */
    public function getTimeEfficiency()
    {
        return $this->container['time_efficiency'];
    }

    /**
     * Sets time_efficiency
     *
     * @param int $time_efficiency Time Efficiency Level of the blueprint.
     *
     * @return $this
     */
    public function setTimeEfficiency($time_efficiency)
    {

        if (($time_efficiency > 20)) {
            throw new \InvalidArgumentException('invalid value for $time_efficiency when calling GetCharactersCharacterIdBlueprints200Ok., must be smaller than or equal to 20.');
        }
        if (($time_efficiency < 0)) {
            throw new \InvalidArgumentException('invalid value for $time_efficiency when calling GetCharactersCharacterIdBlueprints200Ok., must be bigger than or equal to 0.');
        }

        $this->container['time_efficiency'] = $time_efficiency;

        return $this;
    }

    /**
     * Gets type_id
     *
     * @return int
     */
    public function getTypeId()
    {
        return $this->container['type_id'];
    }

    /**
     * Sets type_id
     *
     * @param int $type_id type_id integer
     *
     * @return $this
     */
    public function setTypeId($type_id)
    {
        $this->container['type_id'] = $type_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


