<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Users extends MY_Controller {
    
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
     * /admin/users
     * 
     * @return void
     */
    public function index()
    {
        // Get the search filters
        $page   = $this->input->get('page');
        $search = $this->input->get('search');
        $sort   = $this->input->get('sort') ? $this->input->get('sort') : 'created';
        $dir    = $this->input->get('dir') ? $this->input->get('dir') : 'desc';
        
        // Get the users and the pagination object
        $users      = $this->users_model->get_paginated($page, $search, $sort, $dir);
        $pagination = $this->users_model->pagination();
        
        // Output the template
        $this->template->view('users/users', compact('users', 'pagination', 'page', 'search', 'sort', 'dir'));
    }
    
    /**
     * /admin/users/create
     * 
     * @return void
     */
    public function create()
    {
        $this->edit();
    }
    
    /**
     * /admin/users/edit/$id
     * 
     * @param int $id
     * @return void
     */
    public function edit($id = NULL)
    {
        // If we have an ID, get the user
        if ($id !== NULL)
        {
            $user = $this->users_model->find($id);
        }
        else
        {
            $user = NULL;
        }
        
        // Get posted data
        $posted = $this->input->post();
        
        // Set the form
        $this->form->set($this->user_form($id !== NULL), ($posted ? $posted : $user));
        
        // If data has been posted, validate the posted data and save the user
        if ($posted)
        {
            if ($this->form->validate())
            {
                // If a password has been passed, encrypt it
                if (isset($posted['password']) && strlen($posted['password']) > 0)
                {
                    $posted['salt'] = $this->auth->generate_salt();
                    $posted['password'] = $this->auth->encrypt_password($posted['password'], $posted['salt']);
                }
                else
                {
                    unset($posted['password']);
                }
                
                // Save the user
                if ($this->users_model->save($posted, $id))
                {
                    $this->template->success('User successfully saved.');
                }
                else
                {
                    $this->template->error('Could not save user.');
                }
                
                // Redirect back to the user manager
                redirect('admin/users');
            }
        }
        
        // Build the form
        $form = $this->form->build();
        
        // Output the template
        $this->template->view('users/edit', compact('user', 'form'));
    }
    
    /**
     * /admin/users/delete/$id
     * 
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        // You cannot delete yourself
        if ($id === $this->auth->user()->id)
        {
            $this->template->error('You cannot delete yourself.');
        }
        // Attempt user deletion
        else
        {
            if ($this->users_model->delete($id))
            {
                $this->template->success('Successfully deleted user.');
            }
            else
            {
                $this->template->error('Unable to delete user.');
            }
        }
        
        // Return to the users view
        redirect('admin/users');
    }
    
    /**
     * User form
     * 
     * @param bool $edit
     * @return array
     */
    private function user_form($edit = FALSE)
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
                    'rules' => $edit === FALSE ? 'required' : ''
                ),
                array(
                    'label' => 'Email Address',
                    'name'  => 'email',
                    'type'  => 'text',
                    'rules' => 'required|valid_email'
                ),
                array(
                    'label' => 'First Name',
                    'name'  => 'firstname',
                    'type'  => 'text',
                    'rules' => 'required'
                ),
                array(
                    'label' => 'Last Name',
                    'name'  => 'lastname',
                    'type'  => 'text',
                    'rules' => 'required'
                ),
            )
        );
    }
    
}
