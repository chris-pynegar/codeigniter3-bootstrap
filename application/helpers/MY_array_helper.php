<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('object_to_array'))
{
    /**
     * Convert an object to an array
     *
     * @param object $object Object to convert
     * @param bool $recursive Convert recursively
     * @return array
     */
    function object_to_array($object, $recursive = FALSE)
    {
        $array = array();

        foreach ($object as $key => $value)
        {
            if (is_object($value) && $recursive)
            {
                $array[$key] = object_to_array($value);
            }
            else
            {
                $array[$key] = $value;
            }
        }

        return $array;
    }
}

if ( ! function_exists('array_to_object'))
{
    /**
     * Convert an array to an object
     *
     * @param object $array Array to convert
     * @param bool $recursive Convert recursively
     * @return object
     */
    function array_to_object($array, $recursive = FALSE)
    {
        $object = new stdClass;

        foreach ($array as $key => $value)
        {
            if (is_array($value) && $recursive)
            {
                $object->$key = array_to_object($value);
            }
            else
            {
                $object->$key = $value;
            }
        }

        return $object;
    }
}

if ( ! function_exists('array_keys_exist'))
{
    /**
     * Check if multiple array keys exist
     *
     * @param array $keys
     * @param array $data
     * @return bool
     */
    function array_keys_exist(array $keys, array $data)
    {
        return count(array_intersect_key(array_flip($keys), $data)) === count($keys);
    }
}
