<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage System Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Preferences extends MY_Controller {
    
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
     * /admin/system/preferences
     * 
     * @return void
     */
    public function index()
    {
        // Output the template
        $this->template->view('preferences/preferences');
    }
    
}
