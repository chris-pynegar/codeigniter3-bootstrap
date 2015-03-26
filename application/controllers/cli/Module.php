<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CLI Module Controller
 * 
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Module extends MY_Controller {
    
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
     * Migrates a module
     * 
     * @todo Add functionality to migrate a single module
     * @return void
     */
    public function migrate()
    {
        // Load the module migrator library
        $this->load->library('Module_migrator');
        
        // Run the module migrator
        $this->module_migrator->run();
    }
    
}
