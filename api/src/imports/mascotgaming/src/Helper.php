<?php
namespace mascotgaming;

class Helper
{
	public static function requiredParam($params, $key, $type, $callback = null)
	{
		if(!array_key_exists($key, $params))
		{
			throw new Exception("Required parameter \"".$key."\" is not specified");
		}

		self::checkType($params, $key, $type);

		if(is_callable($callback))
		{
			$callback($params, $key, $type);
		}
	}

	public static function optionalParam($params, $key, $type, $callback = null)
	{
		if(!array_key_exists($key, $params))
		{
			return;
		}

		self::checkType($params, $key, $type);

		if(is_callable($callback))
		{
			$callback($params, $key, $type);
		}
	}

	public static function strictValues($params, $key, $allowedValues)
	{
		if(!in_array($params[$key], $allowedValues, true))
		{
			throw new Exception("Specified parameter \"".$key."\" must take one of these values (".join(', ', $allowedValues).')');
		}
	}

	private static function checkType($params, $key, $expectedType)
	{
		switch($expectedType)
		{
			case ParamType::STRING:
				if(!is_string($params[$key]))
				{
					throw new Exception("Specified parameter \"".$key."\" must be a string");
				}
				break;

			case ParamType::INTEGER:
				if(!is_int($params[$key]))
				{
					throw new Exception("Specified parameter \"".$key."\" must be an integer");
				}
				break;

			case ParamType::T_ARRAY:
				if(!is_array($params[$key]))
				{
					throw new Exception("Specified parameter \"".$key."\" must be an array");
				}
		}
	}
}
