<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mock
 * 
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Mock {

    /**
     * @var object
     */
    protected $ci;
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();
        
        // Codeigniters unit testing library is required
        $this->ci->load->library('unit_test');
        
        // Use strict mode
        $this->ci->unit->use_strict(TRUE);
    }
    
    /**
     * Output the test result
     * 
     * @return void
     */
    public function result()
    {
        // Initialize the CLImate class
        $climate = new League\CLImate\CLImate;
        
        // Get the test result
        $result = $this->ci->unit->result();
        
        // We don't want to output everything
        for ($i = 0; $i < count($result); $i++)
        {
            unset($result[$i]['File Name']);
            unset($result[$i]['Line Number']);
            unset($result[$i]['Notes']);
        }

        // Output the result table
        $climate->table($result);
    }
    
}
