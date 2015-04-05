<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Account extends MY_Controller {
    
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
     * /admin/users/account/login
     * 
     * @return void
     */
    public function login()
    {
        // Set the form
        $this->form->set($this->login_form());
        
        // Are we posting data?
        if ($data = $this->input->post())
        {
            // Validate the form
            if ($this->form->validate())
            {
                // Attempt to authenticate the user
                if ($this->auth->login($data['username'], $data['password']))
                {
                    redirect('admin');
                }
                else
                {
                    $this->theme->error('Invalid login credentials.');
                }
            }
        }
        
        // Set the correct layout
        $this->template->set_layout('login');
        
        // Build the form
        $form = $this->form->build();
        
        // Load the view
        $this->template->view('account/login', compact('form'));
    }
    
    /**
     * Login form fields
     * 
     * @return array
     */
    private function login_form()
    {
        return array(
            'fields'    => array(
                array(
                    'label' => 'Username',
                    'name'  => 'username',
                    'type'  => 'text',
                    'rules' => 'required'
                ),
                array(
                    'label' => 'Password',
                    'name'  => 'password',
                    'type'  => 'password',
                    'rules' => 'required'
                )
            ),
            'button'    => 'Login'
        );
    }
    
}
