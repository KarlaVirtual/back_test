<?php
/**
 * ServerTicketGameType
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
 * ServerTicketGameType Class Doc Comment
 *
 * @category    Class
 * @description Game types of all supported entities. Used for factories based on gameType.  Special game types   * DEFAULT - Represent all games. Used for default settings at GamesSettings * ME  - Multievent. Used for a multievent ticket  Specific game factory types * D6 - Dog * H6 - Horse * KN - Keno * RL - Roulette * PK - Poker * BJ - Black Jack * GG - Golden Goal (simple football) * SP - Speedway * GC - Golden Cup (Football cup)  * GL - Golden League (Football league) * LK - Live Keno * LL - Live Loto * FG - Real Fighting * MT - Motorbikes * KT - Karts * CH - Champions (Champions football league) * SN - Spin2win * SX - Perfect Six
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ServerTicketGameType implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Server Ticket_gameType';

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

    const VAL_NONE = 'NONE';
    const VAL_DEFAULT = 'DEFAULT';
    const VAL_ME = 'ME';
    const VAL_D6 = 'D6';
    const VAL_H6 = 'H6';
    const VAL_KN = 'KN';
    const VAL_RL = 'RL';
    const VAL_PK = 'PK';
    const VAL_BJ = 'BJ';
    const VAL_GG = 'GG';
    const VAL_SP = 'SP';
    const VAL_GC = 'GC';
    const VAL_GL = 'GL';
    const VAL_LK = 'LK';
    const VAL_LL = 'LL';
    const VAL_FG = 'FG';
    const VAL_MT = 'MT';
    const VAL_KT = 'KT';
    const VAL_CH = 'CH';
    const VAL_SN = 'SN';
    const VAL_SX = 'SX';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getValAllowableValues()
    {
        return [
            self::VAL_NONE,
            self::VAL_DEFAULT,
            self::VAL_ME,
            self::VAL_D6,
            self::VAL_H6,
            self::VAL_KN,
            self::VAL_RL,
            self::VAL_PK,
            self::VAL_BJ,
            self::VAL_GG,
            self::VAL_SP,
            self::VAL_GC,
            self::VAL_GL,
            self::VAL_LK,
            self::VAL_LL,
            self::VAL_FG,
            self::VAL_MT,
            self::VAL_KT,
            self::VAL_CH,
            self::VAL_SN,
            self::VAL_SX,
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

        $allowed_values = ["NONE", "DEFAULT", "ME", "D6", "H6", "KN", "RL", "PK", "BJ", "GG", "SP", "GC", "GL", "LK", "LL", "FG", "MT", "KT", "CH", "SN", "SX"];
        if (!in_array($this->container['val'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'val', must be one of 'NONE', 'DEFAULT', 'ME', 'D6', 'H6', 'KN', 'RL', 'PK', 'BJ', 'GG', 'SP', 'GC', 'GL', 'LK', 'LL', 'FG', 'MT', 'KT', 'CH', 'SN', 'SX'.";
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

        $allowed_values = ["NONE", "DEFAULT", "ME", "D6", "H6", "KN", "RL", "PK", "BJ", "GG", "SP", "GC", "GL", "LK", "LL", "FG", "MT", "KT", "CH", "SN", "SX"];
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
     * @param string $val GameType value selected.
     * @return $this
     */
    public function setVal($val)
    {
        $allowed_values = array('NONE', 'DEFAULT', 'ME', 'D6', 'H6', 'KN', 'RL', 'PK', 'BJ', 'GG', 'SP', 'GC', 'GL', 'LK', 'LL', 'FG', 'MT', 'KT', 'CH', 'SN', 'SX');
        if (!is_null($val) && (!in_array($val, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'val', must be one of 'NONE', 'DEFAULT', 'ME', 'D6', 'H6', 'KN', 'RL', 'PK', 'BJ', 'GG', 'SP', 'GC', 'GL', 'LK', 'LL', 'FG', 'MT', 'KT', 'CH', 'SN', 'SX'");
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


