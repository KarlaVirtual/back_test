<?php
/**
 * EntityType
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * GoldenRace External API
 *
 * No description provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: 0.1.3-SNAPSHOT
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;

/**
 * EntityType Class Doc Comment
 *
 * @category    Class
 * @description Entity types. For all types of entities supported
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class EntityType implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'EntityType';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'val' => 'string'
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'val' => 'val'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'val' => 'setVal'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'val' => 'getVal'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    const VAL_UNIT = 'UNIT';
    const VAL_CLIENT = 'CLIENT';
    const VAL_WALLET = 'WALLET';
    const VAL_STAFF = 'STAFF';
    const VAL_MANAGER = 'MANAGER';
    const VAL_API = 'API';
    const VAL_JACKPOT = 'JACKPOT';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getValAllowableValues()
    {
        return [
            self::VAL_UNIT,
            self::VAL_CLIENT,
            self::VAL_WALLET,
            self::VAL_STAFF,
            self::VAL_MANAGER,
            self::VAL_API,
            self::VAL_JACKPOT,
        ];
    }
    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['val'] = isset($data['val']) ? $data['val'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        $allowed_values = ["UNIT", "CLIENT", "WALLET", "STAFF", "MANAGER", "API", "JACKPOT"];
        if (!in_array($this->container['val'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'val', must be one of 'UNIT', 'CLIENT', 'WALLET', 'STAFF', 'MANAGER', 'API', 'JACKPOT'.";
        }

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        $allowed_values = ["UNIT", "CLIENT", "WALLET", "STAFF", "MANAGER", "API", "JACKPOT"];
        if (!in_array($this->container['val'], $allowed_values)) {
            return false;
        }
        return true;
    }


    /**
     * Gets val
     * @return string
     */
    public function getVal()
    {
        return $this->container['val'];
    }

    /**
     * Sets val
     * @param string $val
     * @return $this
     */
    public function setVal($val)
    {
        $allowed_values = array('UNIT', 'CLIENT', 'WALLET', 'STAFF', 'MANAGER', 'API', 'JACKPOT');
        if (!is_null($val) && (!in_array($val, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'val', must be one of 'UNIT', 'CLIENT', 'WALLET', 'STAFF', 'MANAGER', 'API', 'JACKPOT'");
        }
        $this->container['val'] = $val;

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


