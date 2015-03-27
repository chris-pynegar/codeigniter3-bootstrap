<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Users_model extends MY_Model {
    
    /**
     * @var string
     */
    protected $table = 'users';
    
    /**
     * @var array
     */
    protected $searchable = array(
        'username',
        'email',
        'firstname',
        'lastname'
    );

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
     * Find a user by their username
     * 
     * @param string $username
     * @return object
     */
    public function find_by_username($username)
    {
        return $this->find('first', array(
            'where' => array(
                array('username', $username)
            )
        ));
    }
    
    /**
     * Find a user by their email address
     * 
     * @param string $username
     * @return object
     */
    public function find_by_email($email)
    {
        return $this->find('first', array(
            'where' => array(
                array('email', $email)
            )
        ));
    }

    /**
     * Common select
     * 
     * @return array
     */
    protected function common_select()
    {
        return array(
            array('users.*'),
            array('roles.role')
        );
    }
    
    /**
     * Common join
     * 
     * @return array
     */
    protected function common_join()
    {
        return array(
            array('roles', 'roles.id = users.role_id')
        );
    }
    
}
