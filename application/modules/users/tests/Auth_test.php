<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Auth_test extends Mock {
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // We need to load the Auth library in order to test it
        $this->ci->load->library('users/Auth');
    }
    
    /**
     * Test for checking if the user is logged in
     * 
     * @return void
     */
    public function logged_in_test()
    {
        $a = $this->ci->auth->logged_in();
        
        $this->ci->unit->run($a, 'is_bool', __METHOD__);
    }

    /**
     * Test for encypting a password
     * 
     * @return void
     */
    public function encrypt_password_test()
    {
        $a = 'password';
        $b = $this->ci->auth->generate_salt();
        $c = $this->ci->auth->encrypt_password($a, $b);
        
        $this->ci->unit->run($c, 'is_string', __METHOD__);
    }
    
    /**
     * Test for generating a salt
     * 
     * @return void
     */
    public function generate_salt_test()
    {
        $a = $this->ci->auth->generate_salt();
        
        $this->ci->unit->run($a, 'is_int', __METHOD__);
    }

    /**
     * Test for generating a random password
     * 
     * @return void
     */
    public function random_password_test()
    {
        $a = $this->ci->auth->random_password();
        
        $this->ci->unit->run($a, 'is_string', __METHOD__);
    }
    
}
