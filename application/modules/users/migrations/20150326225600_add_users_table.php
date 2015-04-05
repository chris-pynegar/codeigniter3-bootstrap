<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage Users Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Migration_Add_users_table extends Module_migrator {

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
     * Install
     *
     * @return void
     */
    public function up()
    {
        $this->ci->dbforge->add_field(array(
            'id' => array(
                'type'              => 'int',
                'constraint'        => 11,
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ),
            'role_id' => array(
                'type'              => 'int',
                'constraint'        => 11
            ),
            'username' => array(
                'type'              => 'varchar',
                'constraint'        => 40
            ),
            'password' => array(
                'type'              => 'varchar',
                'constraint'        => 64
            ),
            'email' => array(
                'type'              => 'varchar',
                'constraint'        => 250
            ),
            'firstname' => array(
                'type'              => 'varchar',
                'constraint'        => 50
            ),
            'lastname' => array(
                'type'              => 'varchar',
                'constraint'        => 50
            ),
            'salt' => array(
                'type'              => 'varchar',
                'constraint'        => 10
            ),
            'active' => array(
                'type'              => 'tinyint',
                'constraint'        => 1,
                'default'           => 1
            ),
            'banned' => array(
                'type'              => 'tinyint',
                'constraint'        => 1,
                'default'           => 0
            ),
            'banned_message' => array(
                'type'              => 'varchar',
                'constraint'        => 250
            ),
            'created' => array(
                'type'              => 'datetime'
            ),
            'modified' => array(
                'type'              => 'datetime'
            )
        ));
        $this->ci->dbforge->add_key('id');
        $this->ci->dbforge->add_key('created');
        $this->ci->dbforge->add_key('modified');
        $this->ci->dbforge->create_table('users');
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        $this->ci->dbforge->drop_table('users');
    }

}
