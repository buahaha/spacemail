<?php
/**
 * CharacterscharacterIdchatChannelsAllowed
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * EVE Swagger Interface
 *
 * An OpenAPI for EVE Online
 *
 * OpenAPI spec version: 0.5.6
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;

/**
 * CharacterscharacterIdchatChannelsAllowed Class Doc Comment
 *
 * @category    Class */
 // @description allowed object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class CharacterscharacterIdchatChannelsAllowed implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'characterscharacter_idchat_channels_allowed';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'accessor_id' => 'int',
        'accessor_type' => 'string'
    );

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'accessor_id' => 'accessor_id',
        'accessor_type' => 'accessor_type'
    );

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'accessor_id' => 'setAccessorId',
        'accessor_type' => 'setAccessorType'
    );

    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'accessor_id' => 'getAccessorId',
        'accessor_type' => 'getAccessorType'
    );

    public static function getters()
    {
        return self::$getters;
    }

    const ACCESSOR_TYPE_CHARACTER = 'character';
    const ACCESSOR_TYPE_CORPORATION = 'corporation';
    const ACCESSOR_TYPE_ALLIANCE = 'alliance';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getAccessorTypeAllowableValues()
    {
        return [
            self::ACCESSOR_TYPE_CHARACTER,
            self::ACCESSOR_TYPE_CORPORATION,
            self::ACCESSOR_TYPE_ALLIANCE,
        ];
    }
    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = array();

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['accessor_id'] = isset($data['accessor_id']) ? $data['accessor_id'] : null;
        $this->container['accessor_type'] = isset($data['accessor_type']) ? $data['accessor_type'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['accessor_id'] === null) {
            $invalid_properties[] = "'accessor_id' can't be null";
        }
        if ($this->container['accessor_type'] === null) {
            $invalid_properties[] = "'accessor_type' can't be null";
        }
        $allowed_values = array("character", "corporation", "alliance");
        if (!in_array($this->container['accessor_type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'accessor_type', must be one of #{allowed_values}.";
        }

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properteis are valid
     */
    public function valid()
    {
        if ($this->container['accessor_id'] === null) {
            return false;
        }
        if ($this->container['accessor_type'] === null) {
            return false;
        }
        $allowed_values = array("character", "corporation", "alliance");
        if (!in_array($this->container['accessor_type'], $allowed_values)) {
            return false;
        }
        return true;
    }


    /**
     * Gets accessor_id
     * @return int
     */
    public function getAccessorId()
    {
        return $this->container['accessor_id'];
    }

    /**
     * Sets accessor_id
     * @param int $accessor_id ID of an allowed channel member
     * @return $this
     */
    public function setAccessorId($accessor_id)
    {
        $this->container['accessor_id'] = $accessor_id;

        return $this;
    }

    /**
     * Gets accessor_type
     * @return string
     */
    public function getAccessorType()
    {
        return $this->container['accessor_type'];
    }

    /**
     * Sets accessor_type
     * @param string $accessor_type accessor_type string
     * @return $this
     */
    public function setAccessorType($accessor_type)
    {
        $allowed_values = array('character', 'corporation', 'alliance');
        if (!in_array($accessor_type, $allowed_values)) {
            throw new \InvalidArgumentException("Invalid value for 'accessor_type', must be one of 'character', 'corporation', 'alliance'");
        }
        $this->container['accessor_type'] = $accessor_type;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
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
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


