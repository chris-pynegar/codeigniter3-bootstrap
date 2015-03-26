<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_default_roles extends Module_migrator {
    
    /**
     * @var array
     */
    private $default_roles = array(
        'developer' => 'Developer',
        'admin'     => 'Administrator',
        'user'      => 'Registered User'
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
        $this->ci->load->model('users/roles_model');
    }

    /**
     * Install
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->default_roles as $role => $name)
        {
            $this->ci->roles_model->save(array(
                'name'  => $name,
                'role'  => $role
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
        $this->ci->roles_model->delete('all', array(
            'where_in' => array(
                array('role', array_keys($this->roles))
            )
        ));
    }

}
