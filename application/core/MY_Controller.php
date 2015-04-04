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
        else
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
        }
    }

}
