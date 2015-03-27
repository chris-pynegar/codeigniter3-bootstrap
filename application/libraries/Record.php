<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Record
 *
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Record {

    /**
     * @var object Model
     */
    protected $model;

    /**
     * Constructor
     *
     * @param object $record The record object
     * @param object $model The model object
     * @return void
     */
    public function __construct($record = NULL, $model = NULL)
    {
        if (is_object($record) OR is_array($record))
        {
            foreach ($record as $key => $value)
            {
                $this->$key = $value;
            }
        }

        $this->model = $model;
    }

    /**
     * Get record that this model belongs to
     *
     * @example
     * $user = $this->users_model->find(1);
     * $group = $user->belongs_to('group');
     * @param string $model Model name
     * @param array $options Additional find options
     * @return object
     */
    public function belongs_to($model, array $options = array())
    {
        return $this->get_single($model, $options, TRUE);
    }

    /**
     * Get the record that this model has one of
     *
     * @example
     * $user = $this->users_model->find(1);
     * $profile = $user->has_one('profile');
     * @param string $model Model name
     * @param array $options Additional find options
     * @return object
     */
    public function has_one($model, array $options = array())
    {
        return $this->get_single($model, $options);
    }

    /**
     * Get single record
     *
     * @param string $model Model name
     * @param array $options Additional find options
     * @param bool $belongs Record belongs to model
     * @return object
     */
    private function get_single($model, $options, $belongs = FALSE)
    {
        $relationships = $this->relationship(($belongs ? 'belongs_to' : 'has_one'));

        if ( ! empty($relationships) && isset($relationships[$model]))
        {
            $relationship   = $relationships[$model];
            $primary        = isset($relationship['primary']) ? $relationship['primary'] : NULL;
            $foreign        = isset($relationship['foreign']) ? $relationship['foreign'] : NULL;
            $model          = $this->format($model);

            if ( ! empty($primary) && ! empty($foreign))
            {
                // Prepend table to field
                if ($belongs)
                {
                    $foreign = $this->model->$model->table().'.'.$foreign;
                    $primary = $this->$primary;
                }
                else
                {
                    $primary = $this->model->$model->table().'.'.$primary;
                    $foreign = $this->$foreign;
                }

                // Merge where clause into options
                $options = array_merge_recursive($options, array(
                    'where' => array(
                        array($foreign, $primary)
                    )
                ));

                return $this->model->$model->find('first', $options);
            }
        }

        return NULL;
    }

    /**
     * Get model relationships
     *
     * @param string $type Relationship type
     * @return array
     */
    public function relationship($type)
    {
        // Get all relationships
        $relationships = $this->model->relationships();

        if (isset($relationships[$type]) && is_array($relationships[$type]))
        {
            return $relationships[$type];
        }
        else
        {
            return array();
        }
    }

    /**
     * Format model name
     *
     * @param string $model Model name to format
     * @return string
     */
    private function format($model)
    {
        return strtolower($model).'_model';
    }

}
