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
     * @var string
     */
    protected $login_url = 'login';
    
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
        $this->ci->load->helper(array('url', 'users/auth'));
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
     * Gets the login url
     * 
     * @return string
     */
    public function login_url()
    {
        return $this->login_url;
    }
    
    /**
     * Sets the login url
     * 
     * @param string $url
     * @return void
     */
    public function set_login_url($url)
    {
        $this->login_url = $url;
    }
    
    /**
     * Request login if the user is not already logged in
     * 
     * @return void
     */
    public function request()
    {
        if ( ! $this->logged_in())
        {
            redirect($this->login_url());
        }
    }

    /**
     * Attempt to login a user based on their credentials
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password)
    {
        // Find the user by their username, we need their unique salt to
        // validate the correct password
        $user = $this->ci->users_model->find_by_username($username);
        
        // If a user was found check that they are also active and not banned
        if ($user && $user->active === '1' && $user->banned !== '1')
        {
            // Encrypt the password
            $password = $this->encrypt_password($password, $user->salt);
            
            // Do we have a match?
            if ($user->password === $password)
            {
                // Valid! Authorize this user
                $this->authorize($user);
                
                // Success
                return TRUE;
            }
        }
        
        // User was not authorized
        return FALSE;
    }
    
    /**
     * Logout the user
     * 
     * @return void
     */
    public function logout()
    {
        return $this->unauthorize();
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
    
    /**
     * Generates a random password
     * 
     * @return string
     */
    public function random_password()
    {
        // Do we have a dictionary to load?
        if ($this->ci->load->config('users/dictionary', TRUE))
        {
            $words = $this->ci->config->config['dictionary']['dictionary'];
            
            return trim(ucwords($words[rand(0, (count($words) - 1))])).rand(10, 99);
        }
        // Otherwise use CodeIgniters random string function
        else
        {
            $this->ci->load->helper('string');
            
            return random_string('alnum', 8);
        }
    }
    
}
