<?php
/**
 * ServerTicketWonData
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
 * ServerTicketWonData Class Doc Comment
 *
 * @category    Class
 * @description This information is only availabe when ticket is resolved and status of ticket es WON. Return null when not available.
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ServerTicketWonData implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Server Ticket Won Data';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'won_amount' => 'double',
        'won_count' => 'int',
        'won_jackpot' => 'double',
        'won_bonus' => 'double',
        'won_taxes' => 'double',
        'won_taxes_percent' => 'double'
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
        'won_amount' => 'wonAmount',
        'won_count' => 'wonCount',
        'won_jackpot' => 'wonJackpot',
        'won_bonus' => 'wonBonus',
        'won_taxes' => 'wonTaxes',
        'won_taxes_percent' => 'wonTaxesPercent'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'won_amount' => 'setWonAmount',
        'won_count' => 'setWonCount',
        'won_jackpot' => 'setWonJackpot',
        'won_bonus' => 'setWonBonus',
        'won_taxes' => 'setWonTaxes',
        'won_taxes_percent' => 'setWonTaxesPercent'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'won_amount' => 'getWonAmount',
        'won_count' => 'getWonCount',
        'won_jackpot' => 'getWonJackpot',
        'won_bonus' => 'getWonBonus',
        'won_taxes' => 'getWonTaxes',
        'won_taxes_percent' => 'getWonTaxesPercent'
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
        $this->container['won_amount'] = isset($data['won_amount']) ? $data['won_amount'] : null;
        $this->container['won_count'] = isset($data['won_count']) ? $data['won_count'] : null;
        $this->container['won_jackpot'] = isset($data['won_jackpot']) ? $data['won_jackpot'] : null;
        $this->container['won_bonus'] = isset($data['won_bonus']) ? $data['won_bonus'] : null;
        $this->container['won_taxes'] = isset($data['won_taxes']) ? $data['won_taxes'] : null;
        $this->container['won_taxes_percent'] = isset($data['won_taxes_percent']) ? $data['won_taxes_percent'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

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

        return true;
    }


    /**
     * Gets won_amount
     * @return double
     */
    public function getWonAmount()
    {
        return $this->container['won_amount'];
    }

    /**
     * Sets won_amount
     * @param double $won_amount Amount won with the ticket.
     * @return $this
     */
    public function setWonAmount($won_amount)
    {
        $this->container['won_amount'] = $won_amount;

        return $this;
    }

    /**
     * Gets won_count
     * @return int
     */
    public function getWonCount()
    {
        return $this->container['won_count'];
    }

    /**
     * Sets won_count
     * @param int $won_count Won elements number . Only for WON and PAID tickets.
     * @return $this
     */
    public function setWonCount($won_count)
    {
        $this->container['won_count'] = $won_count;

        return $this;
    }

    /**
     * Gets won_jackpot
     * @return double
     */
    public function getWonJackpot()
    {
        return $this->container['won_jackpot'];
    }

    /**
     * Sets won_jackpot
     * @param double $won_jackpot Amount of jackpot won with the ticket.
     * @return $this
     */
    public function setWonJackpot($won_jackpot)
    {
        $this->container['won_jackpot'] = $won_jackpot;

        return $this;
    }

    /**
     * Gets won_bonus
     * @return double
     */
    public function getWonBonus()
    {
        return $this->container['won_bonus'];
    }

    /**
     * Sets won_bonus
     * @param double $won_bonus Amount of bonus won with the ticket.
     * @return $this
     */
    public function setWonBonus($won_bonus)
    {
        $this->container['won_bonus'] = $won_bonus;

        return $this;
    }

    /**
     * Gets won_taxes
     * @return double
     */
    public function getWonTaxes()
    {
        return $this->container['won_taxes'];
    }

    /**
     * Sets won_taxes
     * @param double $won_taxes Amount of taxes paid with the won of the ticket.
     * @return $this
     */
    public function setWonTaxes($won_taxes)
    {
        $this->container['won_taxes'] = $won_taxes;

        return $this;
    }

    /**
     * Gets won_taxes_percent
     * @return double
     */
    public function getWonTaxesPercent()
    {
        return $this->container['won_taxes_percent'];
    }

    /**
     * Sets won_taxes_percent
     * @param double $won_taxes_percent Percentage of taxes paid with the won of the ticket.
     * @return $this
     */
    public function setWonTaxesPercent($won_taxes_percent)
    {
        $this->container['won_taxes_percent'] = $won_taxes_percent;

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


