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
        // Get all preferences
        $preferences = $this->preferences_model->all();

        // Build the form field and data
        $fields = array();
        $data   = array();
        
        foreach ($preferences as $preference)
        {
            $field          = $preference->form_options;
            $field['label'] = $preference->label;
            $field['name']  = $preference->name;
            
            array_push($fields, $field);
            
            $data[$preference->name] = set_value($preference->name, $preference->value);
        }
        
        // Set the form
        $this->form->set(compact('fields'), $data);

        // Are we posting data?
        if ($posted = $this->input->post())
        {
            if ($this->form->validate())
            {
                foreach ($preferences as $preference)
                {
                    $this->preferences_model->save(array('value' => $posted[$preference->name]), $preference->id);
                }

                $this->template->success('System preferences updated.');
                redirect(current_url());
            }
        }
        
        // Build form
        $form = $this->form->build();
        
        // Output the template
        $this->template->view('preferences/preferences', compact('form'));
    }
    
}
