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
     * Runs a set of tests
     * 
     * @param string $module
     * @param string $test
     * @return void
     */
    public function index($module = NULL, $test = NULL, $type = 'library')
    {
        
        
        //$test = realpath(APPPATH).'/modules/users/tests/Auth_test.php';
        
        $this->run($module, $test, $type);
    }
    
    /**
     * Runs a test file
     * 
     * @param string $test
     * @return void
     */
    private function run($module, $name, $type = 'library')
    {
        // Set the current sub directory based on the type
        switch ($type)
        {
            case 'library':
                $sub_dir = 'libraries';
                break;
            default:
                return FALSE;
        }
        
        // Get the path to the module
        $module_path = realpath(APPPATH).'/modules/'.$module.'/';
        
        // Check the library exists
        if (is_file($module_path.$sub_dir.'/'.$name.'.php'))
        {
            // Yes, load it
            $this->load->$type($module.'/'.$name);
            
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
        
        // Load in the test if it exists
//        if (file_exists($test))
//        {
//            require $test;
//            
//            // Instantiate the test class
//            $instantiated = new Auth_test;
//            
//            // Get the public classes to test and run the test for each 
//            // method that has one
//            $reflector = new ReflectionClass($instantiated);
//            
//            foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
//            {
//                var_dump($method);
//            }
//        }
    }
    
}
