<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form
 * 
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Form {
    
    /**
     * @var object
     */
    protected $ci;
    
    /**
     * @var array
     */
    protected $data = array();
    
    /**
     * @var array
     */
    protected $input = array();

    /**
     * @var array
     */
    public $fields = array();
    
    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();
        
        // Ensure the form helper is loaded
        $this->ci->load->helper('form');
    }
    
    /**
     * Sets the form
     * 
     * @param array $data
     * @param array $input
     * @return void
     */
    public function set(array $data, $input = array()) 
    {
        // If input is an object, convert it to an array
        if (is_object($input))
        {
            // Ensure the array helper has been loaded
            $this->ci->load->helper('array');
            
            // Convert object
            $input = object_to_array($input);
        }
        
        // Store input
        $this->input = $input;
        
        // Get the form fields
        if (isset($data['fields']))
        {
            $fields = array();
            
            foreach ($data['fields'] as $field)
            {
                // Set some defaults
                $type       = isset($field['type']) ? strtolower($field['type']) : 'text';
                $name       = isset($field['name']) ? $field['name'] : '';
                $rules      = isset($field['rules']) ? $field['rules'] : '';
                $attributes = isset($field['attributes']) ? $field['attributes'] : array();
                $options    = isset($field['options']) ? $field['options'] : array();
                $data       = isset($input[$name]) ? $input[$name] : NULL;
                
                $field = array_merge($field, compact('type', 'name', 'data', 'attributes', 'rules', 'options'));
                
                array_push($fields, $field);
            }
            
            $this->fields = $fields;
        }
    }

    /**
     * Build a form based on an array of data
     * 
     * @param array $data
     * @return string
     */
    public function build()
    {
        // Return the generated form
        return $this->ci->template->component('form', array(), TRUE);
    }
    
    /**
     * Validates the form
     * 
     * @return bool
     */
    public function validate()
    {
        // Ensure the form validation class is loaded
        $this->ci->load->library('form_validation');
        
        // Build the validation rules
        foreach ($this->fields as $field)
        {
            if ((string)$field['rules'] !== '')
            {
                $label  = isset($field['label']) ? $field['label'] : $field['name'];
                $name   = $field['name'];
                $rules  = $field['rules'];
                
                $this->ci->form_validation->set_rules($name, $label, $rules);
            }
        }
        
        // Set the data
        $this->ci->form_validation->set_data($this->input);
        
        // Run the validation
        if ($this->ci->form_validation->run())
        {
            return TRUE;
        }
        else
        {
            $errors = $this->ci->form_validation->error_array();
            
            // Get each field error
            for ($i = 0; $i < count($this->fields); $i++)
            {
                if (isset($errors[$this->fields[$i]['name']]))
                {
                    $this->fields[$i]['error'] = $errors[$this->fields[$i]['name']];
                }
            }
            
            return FALSE;
        }
    }
    
    /**
     * Opens a form
     * 
     * @param string $action
     * @param array $attributes
     * @return string
     */
    public function open($action = '', array $attributes = array())
    {
        return form_open($action, $attributes);
    }
    
    /**
     * Closes a form
     * 
     * @return string
     */
    public function close()
    {
        return form_close();
    }
    
    /**
     * Outputs a label
     * 
     * @param string $label
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public function label($label = '', $name = '', array $attributes = array())
    {
        return form_label($label, $name, $attributes);
    }
    
    /**
     * Outputs an input
     * 
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public function input($type = 'text', $name = '', $value = '', array $attributes = array())
    {
        // Set the value
        $value = set_value($name, $value);
        
        // Return generated input
        return form_input(array_merge($attributes, compact('type', 'name', 'value')), $value);
    }
    
    /**
     * Outputs a text input
     * 
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public function text($name = '', $value = '', array $attributes = array())
    {
        return $this->input('text', $name, $value, $attributes);
    }
    
    /**
     * Outputs a password input
     * 
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public function password($name = '', array $attributes = array())
    {
        return $this->input('password', $name, '', $attributes);
    }

    /**
     * Outputs a file input
     * 
     * @return string
     */
    public function upload()
    {
        
    }
    
    /**
     * Outputs a hidden input
     * 
     * @param string $name
     * @param string $value
     * @return string
     */
    public function hidden($name = '', $value = '')
    {
        return form_hidden($name, $value);
    }

    /**
     * Outputs a textarea
     * 
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public function textarea($name = '', $value = '', array $attributes = array())
    {
        return form_textarea(array_merge($attributes, compact('name')), set_value($name, $value));
    }
    
    /**
     * Outputs a select menu
     *
     * @param string $name
     * @param array $options
     * @param string $selected
     * @param array $attributes
     * @return string
     */
    public function dropdown($name = '', array $options = array(), $selected = '', array $attributes = array())
    {
        return form_dropdown($name, $options, set_value($name, $selected), $attributes);
    }
    
    /**
     * Outputs a checkbox
     * 
     * @param string $name
     * @param string $value
     * @param string $data
     * @param bool $checked
     * @param array $attributes
     * @return string
     */
    public function checkbox($name = '', $value = '', $data = '', $checked = FALSE, $attributes = array())
    {
        return form_checkbox($name, $value, ($checked || $value === $data));
    }
    
    /**
     * Outputs a radio input
     * 
     * @return string
     */
    public function radio()
    {
        
    }
    
    /**
     * Outputs a button
     * 
     * @param string $text
     * @param array  $attributes
     * @return string
     */
    public function button($text = 'Submit', array $attributes = array())
    {
        return form_button($attributes, $text);
    }

    /**
     * Formats a style attributes
     *
     * @param array $styles
     * @return string
     */
    private function format_style_attribute(array $styles = array())
    {
        $tag = ' style="';

        foreach ($styles as $attribute => $value)
        {
            $tag .= $attribute.':'.$value.';';
        }

        $tag .= '"';

        return $tag;
    }
    
}
