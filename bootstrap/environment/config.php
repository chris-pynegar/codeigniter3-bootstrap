<?php

/**
 * Environments Config
 *
 * Define what environment we are in depending on the request we are making
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
return array(

    /**
     * Development Environments
     */
    'development'	=> array(

        // Based on the URI
        'http_host'	=> array(
            '#\\.dev$#',				// anything.dev
            '#\\.local$#',				// anything.local
            '#localhost$#',				// localhost
            '#localhost\:[0-9]{4}+$#'	// localhost with port number
        ),

        // Based on the system name
        'hostname'	=> array(
            '#\\.local$#'
        )

    ),

    /**
     * Staging Environments
     */
    'staging'		=> array(

        // Based on the URI
        'http_host'	=> array(
            '#^staging\\.#'				// staging.anything.com
        )

    ),

    /**
     * Production Environments
     */
    'production'	=> array()

);
