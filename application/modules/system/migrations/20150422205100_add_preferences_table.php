<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @subpackage System Module
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Migration_Add_preferences_table extends Module_migrator {

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
            'name' => array(
                'type'              => 'varchar',
                'constraint'        => 50
            ),
            'value' => array(
                'type'              => 'text'
            ),
            'form_options' => array(
                'type'              => 'text'
            ),
            'created' => array(
                'type'              => 'datetime'
            ),
            'modified' => array(
                'type'              => 'datetime'
            )
        ));
        $this->ci->dbforge->add_key('id');
        $this->ci->dbforge->add_key('name');
        $this->ci->dbforge->add_key('created');
        $this->ci->dbforge->add_key('modified');
        $this->ci->dbforge->create_table('preferences');
    }

    /**
     * Uninstall
     *
     * @return void
     */
    public function down()
    {
        $this->ci->dbforge->drop_table('preferences');
    }

}
