<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Module Migrator
 * 
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 * 
 * @todo Add functionality to migrate a single module
 * @todo Add functionality to rollback a module
 */
class Module_migrator {
    
    /**
     * @var array
     */
    protected $migrations = array();

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
    }
    
    /**
     * Run the module migrator
     * 
     * @return void
     */
    public function run()
    {
        $locations = array_keys($this->ci->config->config['modules_locations']);
        
        foreach ($locations as $location)
        {
            foreach (scandir($location) as $module)
            {
                $module_dir = $location.$module;
                
                // Ensure module is a folder and ignore . and .. files/directories
                if ( ! is_dir($module_dir) OR in_array($module, array('.', '..'), TRUE))
                {
                    continue;
                }
                
                if (is_dir($migrations_dir = $module_dir.DS.'migrations'))
                {
                    foreach (scandir($migrations_dir) as $migration)
                    {
                        if (is_file($path = $migrations_dir.DS.$migration))
                        {
                            $this->migrations[substr($migration, 0, 14)] = array(
                                'path'      => $path,
                                'file'      => $migration,
                                'module'    => $module
                            );
                        }
                    }
                }
            }
        }
        
        $this->execute();
    }
    
    /**
     * Execute migrations
     * 
     * @return void
     */
    private function execute()
    {
        echo 'Running module migrations'.NL;
        
        // DB Forge required
        $this->ci->load->dbforge();
        
        // Install the module migrations if it isn't already
        $this->install();
        
        // Order the migrations by their timestamp
        ksort($this->migrations);
        
        foreach ($this->migrations as $migration)
        {
            // Build the class name
            $prefix     = 'Migration';
            $name       = ucfirst(strtolower(substr(substr($migration['file'], 15), 0, -4)));
            $class_name = $prefix.'_'.$name;
            
            // Do nothing if its already installed
            if ($this->is_installed($name))
            {
                continue;
            }
            
            // Load file
            require $migration['path'];
            
            // Instantiate
            $class = new $class_name;
            
            // Run the 'up' method to install the migration
            if (method_exists($class, 'up'))
            {
                echo 'Running '.$migration['module'].' - '.$name.NL;
                
                $class->up();
                
                // Record the installed migration
                $this->ci->db->insert('module_migrator', array(
                    'module'    => $migration['module'],
                    'name'      => $name,
                    'installed' => date('Y-m-d H:i:s')
                ));
            }
        }
        
        echo 'Finished running module migrations'.NL;
    }
    
    /**
     * Check to see if a migration is installed
     * 
     * @param string $name
     * @return bool
     */
    private function is_installed($name)
    {
        return $this->ci->db->where('name', $name)->count_all_results('module_migrator') > 0;
    }
    
    /**
     * Install the module_migrator table
     * 
     * @return void
     */
    private function install()
    {
        $this->ci->dbforge->add_field(array(
            'id' => array(
                'type'              => 'int',
                'constraint'        => 11,
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'module' => array(
                'type'              => 'varchar',
                'constraint'        => 100
            ),
            'name' => array(
                'type'              => 'varchar',
                'constraint'        => 100
            ),
            'installed' => array(
                'type'              => 'datetime'
            )
        ));
        
        $this->ci->dbforge->add_key('id');
        $this->ci->dbforge->add_key('installed');
        $this->ci->dbforge->create_table('module_migrator', TRUE);
    }
    
}
