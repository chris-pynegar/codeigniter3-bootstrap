<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Dashboard Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Dashboard extends MY_Controller {
    
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
     * /admin/dashboard
     * 
     * @return void
     */
    public function index()
    {
        $this->template->view('dashboard/dashboard');
    }
    
}
