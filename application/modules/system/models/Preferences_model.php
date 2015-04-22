<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage System Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Preferences_model extends MY_Model {
    
    /**
     * @var string
     */
    protected $table = 'preferences';
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
}
