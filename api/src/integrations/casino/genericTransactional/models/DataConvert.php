<?php

namespace Backend\integrations\casino\genericTransactional\models;

use Exception;
use SimpleXMLElement;
use Throwable;

/**
 * Performs all format type conversions between arrays, objects, JSON, and XML.
 * 
 * @category API
 * @package casino\cenericTransactional\models\
 * @author Esteban Arévalo
 * @version 1.0
 * @since 3/3/2025
 */
class DataConvert {
    public static $nameMethod = "";

    /**
     * Converts an array or object to a JSON string.
     * @param $data Represents the data to be converted.
     * @return string The JSON representation of the data.
     * @throws Exception If the data conversion fails.
     */
    public static function toJson($data){
        try {
            return json_encode($data);
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts an XML string to an object.
     * @param string $data Represents the XML string to be converted.
     * @return object The object representation of the XML string.
     * @throws Exception If the data conversion fails.
     */
    public static function fromXMLToObject($data){
        try {
            return self::fromJsonToObject(self::toJson($data));
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts a JSON string to an associative array.
     * @param string $data Represents the JSON string to be converted.
     * @return array The associative array representation of the JSON string.
     * @throws Exception If the data conversion fails.
     */
    public static function fromJsonToAssociativeArray($data){
        try { 
            return json_decode($data, true);
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts a JSON string to an object.
     * @param string $data Represents the JSON string to be converted.
     * @return object The object representation of the JSON string.
     * @throws Exception If the data conversion fails.
     */
    public static function fromJsonToObject($data){
        try {
            return json_decode($data);
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts an associative array to an object.
     * @param array $data Represents the associative array to be converted.
     * @return object The object representation of the associative array.
     * @throws Exception If the data conversion fails.
     */
    public static function fromAssociativeArrayToObject(array $data){
        try {
            return self::fromJsonToObject(self::toJson($data));
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts an array to an XML string.
     * @param array $arrayData Represents the array to be converted.
     * @return string The XML representation of the array.
     * @throws Exception If the data conversion fails.
     */
    public static function toXML(array &$arrayData){
        try {
            $rootElement = array_key_first($arrayData);

            // extract attributes if they are defined
            $rootAttributes = [];
            if (isset($arrayData[$rootElement]['@attributes']) && is_array($arrayData[$rootElement]['@attributes'])) {
                $rootAttributes = $arrayData[$rootElement]['@attributes'];

                // remove attributes so that they are not processed as children
                unset($arrayData[$rootElement]['@attributes']);     
            }

            $xml = new SimpleXMLElement("<$rootElement></$rootElement>");

            // add attribues if they exist
            foreach ($rootAttributes as $attributeName => $attributeValue){
                $xml->addAttribute($attributeName, $attributeValue);
            }

            self::arrayToXml($arrayData[$rootElement], $xml);
            return $xml->asXML();
            
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }

    /**
     * Converts an associative array to an XML string.
     * @param array $associativeArray Represents the associative array to be converted.
     * @return string The XML representation of the associative array.
     * @throws Exception If the data conversion fails.
     */
    private static function arrayToXml(&$associativeArray, &$xml){
        try {
            foreach ($associativeArray as $key => $value){
                if (is_numeric($key)){
                    $key = 'item' .  $key;
                }
    
                if (is_array($value)) {
                    $subnode = $xml->addChild($key);
                    self::arrayToXml($value, $subnode);
                } else {
                    $xml->addChild($key, htmlspecialchars($value));
                }
            }
        } catch (Throwable $th) {
            throw new Exception("An error occurred during data conversion.", 300091);
        }
    }
}