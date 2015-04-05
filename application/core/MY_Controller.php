<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Core Controller Extension
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class MY_Controller extends CI_Controller {
    
    /**
     * @var array View data
     */
    protected $viewdata = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // Only CLI requests can access the controllers/cli directory
        if ($this->router->directory === 'cli/' && ! is_cli())
        {
            show_404();
        }
        
        // Some things we only want to initialize if this is not a cli request
        if ( ! is_cli())
        {
            // Load the form library
            $this->load->library('Form');
            
            // Load the auth library
            $this->load->library('users/Auth');
            
            // Load the template
            $this->load->library('templates/template', array(
                'template'  => 'admin',
                'layout'    => 'default'
            ));
            
            // If we are accessing the admin the user must be logged in
            if (admin() && ! $this->auth->logged_in())
            {
                // Set the correct login url
                $this->auth->set_login_url('admin/login');
                
                if ($this->router->module !== 'users' && $this->router->method !== 'login')
                {
                    $this->auth->request();
                }
            }
        }
    }

}
