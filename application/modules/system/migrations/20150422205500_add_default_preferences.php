<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage System Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Migration_Add_default_preferences extends Module_migrator {
    
    /**
     * @var array
     */
    private $default_preferences = array(
        'website_name'  => array(
            'value'         => 'Bootstrap Website',
            'form_options'  => array(
                'type'  => 'text',
                'rules' => 'required'
            )
        )
    );

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load the roles model
        $this->ci->load->model('system/preferences_model');
    }

    /**
     * Install
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->default_preferences as $name => $preference)
        {
            $this->ci->preferences_model->save(array(
                'name'          => $name,
                'value'         => $preference['value'],
                'form_options'  => $preference['form_options'] 
            ));
        }
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        
    }

}
