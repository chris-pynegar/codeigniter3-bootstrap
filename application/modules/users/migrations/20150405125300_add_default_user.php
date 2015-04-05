<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Migration_Add_default_user extends Module_migrator {
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load required models and libraries
        $this->ci->load->model('users/roles_model');
        $this->ci->load->model('users/users_model');
        
        $this->ci->load->library('users/Auth');
    }

    /**
     * Install
     *
     * @return void
     */
    public function up()
    {
        $salt       = $this->ci->auth->generate_salt();
        $password   = $this->ci->auth->encrypt_password('password', $salt);
        
        $this->ci->users_model->save(array(
            'role_id'   => $this->ci->roles_model->role_id('developer'),
            'username'  => 'admin',
            'password'  => $password,
            'email'     => 'admin@ci-bootstrap.com',
            'firstname' => 'Admin',
            'lastname'  => 'User',
            'salt'      => $salt
        ));
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        $this->ci->users_model->delete('first', array(
            'where' => array(
                array('username', 'admin')
            )
        ));
    }

}
