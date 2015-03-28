<?php

/**
 * Environments Declaration
 *
 * Declare what environment we are using based on the config
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */

// Load the environments config
$environments = require 'config.php';

// Do we have a custom environments file to load?
if (file_exists($path = dirname(__FILE__).'/custom.php'))
{
    $environments = array_merge_recursive($environments, require $path);
}

if ( ! empty($environments))
{
    foreach ($environments as $environment => $config)
    {
        if (isset($config['http_host']) && isset($_SERVER['HTTP_HOST']))
        {
            foreach ($config['http_host'] as $regex)
            {
                if (preg_match($regex, $_SERVER['HTTP_HOST']))
                {
                    $env = $environment;
                    break;
                }
            }
        }
        else if (isset($config['hostname']))
        {
            foreach ($config['hostname'] as $hostname)
            {
                if (preg_match($hostname, strtolower(gethostname())))
                {
                    $env = $environment;
                    break;
                }
            }
        }

        if (isset($env))
        {
            break;
        }
    }
}

define('ENVIRONMENT', isset($env) ? $env : 'production');
