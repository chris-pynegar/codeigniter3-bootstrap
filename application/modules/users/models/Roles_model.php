<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Roles_model extends MY_Model {
    
    /**
     * @var string
     */
    protected $table = 'roles';
    
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
     * Get the ID for a specific role
     * 
     * @param string $role
     * @return int
     */
    public function role_id($role)
    {
        $record = $this->find('first', array(
            'where' => array(
                array('role', $role)
            )
        ));
        
        if ($record)
        {
            return $record->id;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * Get the roles for the form dropdown
     * 
     * @return array
     */
    public function dropdown()
    {
        $records = $this->find('all', array(
            'select'    => array(
                array('id, name')
            ),
            'order_by'  => array(
                array('id', 'asc')
            )
        ));
        
        $roles = array();
        
        foreach ($records as $role)
        {
            $roles[$role->id] = $role->name;
        }
        
        return $roles;
    }
    
}
