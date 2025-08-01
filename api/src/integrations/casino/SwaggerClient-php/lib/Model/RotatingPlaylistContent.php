<?php
/**
 * RotatingPlaylistContent
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
 * RotatingPlaylistContent Class Doc Comment
 *
 * @category    Class
 * @description See  ChannelContent, PlaylistContent and RotatingPlaylistContent
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class RotatingPlaylistContent implements ArrayAccess
{
    const DISCRIMINATOR = 'classType';

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'RotatingPlaylistContent';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'class_type' => 'string',
        'playlist_ids' => 'int[]',
        'rotating_countdown' => 'int',
        'rotating_offset' => 'int'
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
        'playlist_ids' => 'playlistIds',
        'rotating_countdown' => 'rotatingCountdown',
        'rotating_offset' => 'rotatingOffset'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'class_type' => 'setClassType',
        'playlist_ids' => 'setPlaylistIds',
        'rotating_countdown' => 'setRotatingCountdown',
        'rotating_offset' => 'setRotatingOffset'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'class_type' => 'getClassType',
        'playlist_ids' => 'getPlaylistIds',
        'rotating_countdown' => 'getRotatingCountdown',
        'rotating_offset' => 'getRotatingOffset'
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

    const CLASS_TYPE_CHANNEL_CONTENT = 'ChannelContent';
    const CLASS_TYPE_PLAYLIST_CONTENT = 'PlaylistContent';
    const CLASS_TYPE_ROTATING_PLAYLIST_CONTENT = 'RotatingPlaylistContent';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getClassTypeAllowableValues()
    {
        return [
            self::CLASS_TYPE_CHANNEL_CONTENT,
            self::CLASS_TYPE_PLAYLIST_CONTENT,
            self::CLASS_TYPE_ROTATING_PLAYLIST_CONTENT,
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
        $this->container['playlist_ids'] = isset($data['playlist_ids']) ? $data['playlist_ids'] : null;
        $this->container['rotating_countdown'] = isset($data['rotating_countdown']) ? $data['rotating_countdown'] : null;
        $this->container['rotating_offset'] = isset($data['rotating_offset']) ? $data['rotating_offset'] : null;

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
        $allowed_values = ["ChannelContent", "PlaylistContent", "RotatingPlaylistContent"];
        if (!in_array($this->container['class_type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'class_type', must be one of 'ChannelContent', 'PlaylistContent', 'RotatingPlaylistContent'.";
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
        $allowed_values = ["ChannelContent", "PlaylistContent", "RotatingPlaylistContent"];
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
        $allowed_values = array('ChannelContent', 'PlaylistContent', 'RotatingPlaylistContent');
        if ((!in_array($class_type, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'class_type', must be one of 'ChannelContent', 'PlaylistContent', 'RotatingPlaylistContent'");
        }
        $this->container['class_type'] = $class_type;

        return $this;
    }

    /**
     * Gets playlist_ids
     * @return int[]
     */
    public function getPlaylistIds()
    {
        return $this->container['playlist_ids'];
    }

    /**
     * Sets playlist_ids
     * @param int[] $playlist_ids Array of playlist ids.
     * @return $this
     */
    public function setPlaylistIds($playlist_ids)
    {
        $this->container['playlist_ids'] = $playlist_ids;

        return $this;
    }

    /**
     * Gets rotating_countdown
     * @return int
     */
    public function getRotatingCountdown()
    {
        return $this->container['rotating_countdown'];
    }

    /**
     * Sets rotating_countdown
     * @param int $rotating_countdown The time of countdown between one event and the next.
     * @return $this
     */
    public function setRotatingCountdown($rotating_countdown)
    {
        $this->container['rotating_countdown'] = $rotating_countdown;

        return $this;
    }

    /**
     * Gets rotating_offset
     * @return int
     */
    public function getRotatingOffset()
    {
        return $this->container['rotating_offset'];
    }

    /**
     * Sets rotating_offset
     * @param int $rotating_offset The period of rotation time.
     * @return $this
     */
    public function setRotatingOffset($rotating_offset)
    {
        $this->container['rotating_offset'] = $rotating_offset;

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


