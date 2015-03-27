<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Auth {
    
    /**
     * @var object
     */
    protected $ci;
    
    /**
     * @var object
     */
    protected $user;
    
    /**
     * @var bool
     */
    protected $logged_in = FALSE;
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        // Get the CI instance
        $this->ci =& get_instance();
        
        // Ensure library dependencies are loaded
        $this->ci->load->helper('url');
        $this->ci->load->model('users/users_model');
        
        // Check to see if the user is already logged in
        if ($id = $this->ci->session->userdata('auth_user_id'))
        {
            if ($user = $this->ci->users_model->find($id))
            {
                // Authenticate the user
                $this->authorize($user);
            }
        }
    }
    
    /**
     * Get the user object
     * 
     * @return object
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Check if the user is logged in
     * 
     * @return bool
     */
    public function logged_in()
    {
        return $this->logged_in;
    }

    /**
     * Authorizes a user
     * 
     * @param object $user
     * @return void
     */
    public function authorize($user)
    {
        // Store the users ID in the session
        $this->ci->session->set_userdata('auth_user_id', $user->id);
        
        // Update class properties
        $this->logged_in    = TRUE;
        $this->user         = $user;
    }
    
    /**
     * Unauthorizes the currently logged in user
     * 
     * @return void
     */
    public function unauthorize()
    {
        // Check that the user is logged in
        if ($this->logged_in())
        {
            // Destroy the related session data
            $this->ci->session->unset_userdata('auth_user_id');
            
            // Reset class properties
            $this->logged_in    = FALSE;
            $this->user         = NULL;
        }
    }
    
    /**
     * Encrypts a password
     * 
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function encrypt_password($password, $salt)
    {
        return hash('sha256', $password.$salt);
    }
    
    /**
     * Generates a password salt
     * 
     * @return int
     */
    public function generate_salt()
    {
        return rand(10000000, 99999999);
    }
    
}
