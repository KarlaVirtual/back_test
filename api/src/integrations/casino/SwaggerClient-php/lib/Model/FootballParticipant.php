<?php
/**
 * FootballParticipant
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
 * FootballParticipant Class Doc Comment
 *
 * @category    Class
 * @description See FootballParticipant and RaceParticipant
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class FootballParticipant implements ArrayAccess
{
    const DISCRIMINATOR = 'classType';

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'FootballParticipant';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'class_type' => 'string',
        'id' => 'string',
        'name' => 'string',
        'short_desc' => 'string',
        'long_desc' => 'string',
        'fifa_code' => 'string'
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
        'class_type' => 'classType',
        'id' => 'id',
        'name' => 'name',
        'short_desc' => 'shortDesc',
        'long_desc' => 'longDesc',
        'fifa_code' => 'fifaCode'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'class_type' => 'setClassType',
        'id' => 'setId',
        'name' => 'setName',
        'short_desc' => 'setShortDesc',
        'long_desc' => 'setLongDesc',
        'fifa_code' => 'setFifaCode'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'class_type' => 'getClassType',
        'id' => 'getId',
        'name' => 'getName',
        'short_desc' => 'getShortDesc',
        'long_desc' => 'getLongDesc',
        'fifa_code' => 'getFifaCode'
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

    const CLASS_TYPE_FOOTBALL_PARTICIPANT = 'FootballParticipant';
    const CLASS_TYPE_RACE_PARTICIPANT = 'RaceParticipant';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getClassTypeAllowableValues()
    {
        return [
            self::CLASS_TYPE_FOOTBALL_PARTICIPANT,
            self::CLASS_TYPE_RACE_PARTICIPANT,
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
        $this->container['class_type'] = isset($data['class_type']) ? $data['class_type'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['short_desc'] = isset($data['short_desc']) ? $data['short_desc'] : null;
        $this->container['long_desc'] = isset($data['long_desc']) ? $data['long_desc'] : null;
        $this->container['fifa_code'] = isset($data['fifa_code']) ? $data['fifa_code'] : null;

        // Initialize discriminator property with the model name.
        $discriminator = array_search('classType', self::$attributeMap);
        $this->container[$discriminator] = static::$swaggerModelName;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        if ($this->container['class_type'] === null) {
            $invalid_properties[] = "'class_type' can't be null";
        }
        $allowed_values = ["FootballParticipant", "RaceParticipant"];
        if (!in_array($this->container['class_type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'class_type', must be one of 'FootballParticipant', 'RaceParticipant'.";
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

        if ($this->container['class_type'] === null) {
            return false;
        }
        $allowed_values = ["FootballParticipant", "RaceParticipant"];
        if (!in_array($this->container['class_type'], $allowed_values)) {
            return false;
        }
        return true;
    }


    /**
     * Gets class_type
     * @return string
     */
    public function getClassType()
    {
        return $this->container['class_type'];
    }

    /**
     * Sets class_type
     * @param string $class_type
     * @return $this
     */
    public function setClassType($class_type)
    {
        $allowed_values = array('FootballParticipant', 'RaceParticipant');
        if ((!in_array($class_type, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'class_type', must be one of 'FootballParticipant', 'RaceParticipant'");
        }
        $this->container['class_type'] = $class_type;

        return $this;
    }

    /**
     * Gets id
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name Name of participant.
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets short_desc
     * @return string
     */
    public function getShortDesc()
    {
        return $this->container['short_desc'];
    }

    /**
     * Sets short_desc
     * @param string $short_desc Participant short description information.
     * @return $this
     */
    public function setShortDesc($short_desc)
    {
        $this->container['short_desc'] = $short_desc;

        return $this;
    }

    /**
     * Gets long_desc
     * @return string
     */
    public function getLongDesc()
    {
        return $this->container['long_desc'];
    }

    /**
     * Sets long_desc
     * @param string $long_desc Participant extend description information.
     * @return $this
     */
    public function setLongDesc($long_desc)
    {
        $this->container['long_desc'] = $long_desc;

        return $this;
    }

    /**
     * Gets fifa_code
     * @return string
     */
    public function getFifaCode()
    {
        return $this->container['fifa_code'];
    }

    /**
     * Sets fifa_code
     * @param string $fifa_code FIFA code participant information.
     * @return $this
     */
    public function setFifaCode($fifa_code)
    {
        $this->container['fifa_code'] = $fifa_code;

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


