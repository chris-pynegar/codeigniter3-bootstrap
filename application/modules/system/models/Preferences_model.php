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
    
    /**
     * Get all preferences
     * 
     * @return array
     */
    public function all()
    {
        $records        = $this->find('all', array(
            'order_by'  => array(
                array('name', 'asc')
            )
        ));
        $preferences    = array();
        
        foreach ($records as $record)
        {
            $record->form_options = $this->unserialize($record->form_options);
            
            array_push($preferences, $record);
        }
        
        return $preferences;
    }
    
}
