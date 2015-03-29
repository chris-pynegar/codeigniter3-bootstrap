<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CLI Test Controller
 * 
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Test extends MY_Controller {
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // We have a mock class which initializes common properties in the
        // test classes
        $this->load->library('Mock');
    }
    
    /**
     * Runs all tests
     * 
     * @return void
     */
    public function all()
    {
        $path = realpath(APPPATH).'/modules/';
        
        foreach (scandir($path) as $module)
        {
            if ( ! is_dir($path.$module) OR in_array($module, array('.', '..'), TRUE))
            {
                continue;
            }
            
            $this->module($module);
        }
    }

    /**
     * Runs module tests
     * 
     * @param string $module
     * @param string $test
     * @return void
     */
    public function module($module, $test = NULL)
    {
        // Are we running a particular test?
        if ($test !== NULL)
        {
            $this->run($module, $test);
        }
        // Otherwise run all tests for a module
        else
        {
            $path = realpath(APPPATH).'/modules/'.$module.'/tests';
            
            if (is_dir($path))
            {
                foreach (scandir($path) as $file)
                {
                    // Check file is a test
                    if (substr($file, -9) === '_test.php')
                    {
                        $test = substr($file, 0, -9);
                        
                        // Run the test
                        $this->run($module, $test);
                    }
                }
            }
        }
    }
    
    /**
     * Runs a test file
     * 
     * @param string $module
     * @param string $test
     * @return void
     */
    private function run($module, $name)
    {
        // Get the path to the module
        $module_path = realpath(APPPATH).'/modules/'.$module.'/';
        
        // Check the library exists
        if (is_file($module_path.'libraries/'.$name.'.php'))
        {
            // Yes, load it
            $this->load->library($module.'/'.$name);
            
            // We need to load the test if it exists
            if (is_file($test = $module_path.'tests/'.$name.'_test.php'))
            {
                require $test;
                
                // Initialize the test class
                $test_class = $name.'_test';
                $class      = new $test_class;
                
                // Get the methods to test
                $reflector = new ReflectionClass($name);

                foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
                {
                    $method_name = $method->name.'_test';
                    
                    // If we have a test method for this in the test class, run it
                    if (method_exists($class, $method_name))
                    {
                        $class->$method_name();
                    }
                }
                
                // Output the results
                $class->result();
            }
        }
    }
    
}
