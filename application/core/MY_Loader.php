<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Load the MX_Loader
require APPPATH.'third_party/MX/Loader.php';

class MY_Loader extends MX_Loader {
    
    /**
     * Loads a view
     * 
     * @param string $view
     * @param array $vars
     * @param bool $return
     * @return string
     */
    public function view($view, $vars = array(), $return = FALSE) 
	{
		list($path, $_view) = $this->find_in_module($view, $this->_module, 'views/');
        
        if ($path != FALSE) 
		{
			$this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
			$view = $_view;
		}
        
		return $this->_ci_load(array(
            '_ci_view'      => $view,
            '_ci_vars'      => $this->_ci_object_to_array($vars),
            '_ci_return'    => $return
        ));
	}
    
    /** 
	* Finds a file within a module
	**/
	private function find_in_module($file, $module, $base) 
	{
        $segments = explode('/', $file);

		//$file = array_pop($segments);
		$file_ext = (pathinfo($file, PATHINFO_EXTENSION)) ? $file : $file.EXT;
        
		//$path = ltrim(implode('/', $segments).'/', '/');	
        $path = '/';
		$module ? $modules[$module] = $path : $modules = array();
		
		//if ( ! empty($segments)) 
		//{
		//	$modules[array_shift($segments)] = ltrim(implode('/', $segments).'/','/');
		//}
        
//        var_dump($module);
//        var_dump($file);

		foreach (Modules::$locations as $location => $offset) 
		{					
			foreach($modules as $module => $subpath) 
			{			
				$fullpath = $location.$module.'/'.$base.$subpath;
				
				if ($base == 'libraries/' OR $base == 'models/')
				{
					if(is_file($fullpath.ucfirst($file_ext))) return array($fullpath, ucfirst($file));
				}
				else
				/* load non-class files */
				if (is_file($fullpath.$file_ext)) return array($fullpath, $file);
			}
		}
		
		return array(FALSE, $file);	
	}
    
}
