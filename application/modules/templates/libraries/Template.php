<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Templates Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Template {
    
    /**
     * @var object
     */
    protected $ci;
    
    /**
     * @var string Layout content
     */
    private $content = '';
    
    /**
     * @var string Active template
     */
    public $template = '';
    
    /**
     * @var string Active layout
     */
    public $layout = '';
    
    /**
     * @var array View data
     */
    public $data = array();

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct(array $config = array())
    {
        $this->ci =& get_instance();
        
        foreach ($config as $key => $value)
        {
            $this->$key = $value;
        }
        
        // Check for stored flash messages
        foreach (array('success', 'error') as $flash)
        {
            if ($message = $this->ci->session->flashdata('flash_'.$flash))
            {
                $this->data['flash_'.$flash] = $message;
            }
        }
    }
    
    /**
     * Return the template content
     * 
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Sets the template
     * 
     * @param string $template
     * @return void
     */
    public function set_template($template)
    {
        $this->template = $template;
    }
    
    /**
     * Sets the layout
     * 
     * @param string $layout
     * @return void
     */
    public function set_layout($layout)
    {
        $this->layout = $layout;
    }
    
    /**
     * Sets template data
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set_data($key, $value = NULL)
    {
        $this->data[$key] = $value;
    }
    
    /**
     * Loads a template view
     * 
     * @param string $view
     * @param array $data
     * @param bool $return
     * @return mixed
     */
    public function template($view, $data = array(), $return = FALSE)
    {
        return $this->ci->load->view($this->template.'/'.$view, $data, $return);
    }
    
    /**
     * Loads a template layout
     * 
     * @param string $view
     * @param array $data
     * @param bool $return
     * @return mixed
     */
    public function layout($view, $data = array(), $return = FALSE)
    {
        return $this->template('layouts/'.$view.'.layout.php', $data, $return);
    }
    
    /**
     * Loads a template component
     * 
     * @param string $view
     * @param array $data
     * @param bool $return
     * @return mixed
     */
    public function component($view, $data = array(), $return = FALSE)
    {
        return $this->template('components/'.$view.'.component.php', $data, $return);
    }
    
    /**
     * Loads a view
     * 
     * @param string $view
     * @param array $data
     * @param bool $return
     * @return mixed
     */
    public function view($view, $data = array(), $return = FALSE)
    {
        // Set the template data
        foreach ($data as $key => $value)
        {
            $this->set_data($key, $value);
        }
        
        // Set the correct path
        $path = $view.'.view.php';
        
        // Do we need to load the layout?
        if ($this->layout !== NULL)
        {
            // Get the layout content
            $this->content = $this->ci->load->view($path, $this->data, TRUE);
            
            // Output the layout
            return $this->layout($this->layout, $this->data, $return);
        }
        else
        {
            return $this->ci->load->view($path, $this->data, $return);
        }
    }
    
    /**
     * Sets a success message
     * 
     * @param string $message
     * @param bool $store
     * @return void
     */
    public function success($message, $store = TRUE)
    {
        return $this->set_message('flash_success', $message, $store);
    }
    
    /**
     * Sets an error message
     * 
     * @param string $message
     * @param bool $store
     * @return void
     */
    public function error($message, $store = TRUE)
    {
        return $this->set_message('flash_error', $message, $store);
    }

    /**
     * Sets a flash message
     * 
     * @param string $name
     * @param string $message
     * @param bool $store
     * @return void
     */
    private function set_message($name, $message, $store = TRUE)
    {
        if ($store === TRUE)
        {
            $this->ci->session->set_flashdata($name, $message);
        }
        
        $this->data[$name] = $message;
    }
    
}
