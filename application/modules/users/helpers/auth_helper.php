<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
if ( !function_exists('logged_in'))
{
    /**
     * Checks if the user is logged in
     * 
     * @return bool
     */
    function logged_in()
    {
        return get_instance()->auth->logged_in();
    }
}

if ( ! function_exists('authenticated'))
{
    /**
     * Gets the authenticated user object
     * 
     * @return object
     */
    function authenticated()
    {
        return get_instance()->auth->user();
    }
}
