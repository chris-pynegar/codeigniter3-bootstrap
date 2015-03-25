<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Core Model Extension
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class MY_Model extends CI_Model {

	/**
	 * @var string Table name
	 */
	protected $table = '';

	/**
	 * @var string Table alias
	 */
	protected $alias = '';

	/**
	 * @var string Primary key
	 */
	protected $primary = 'id';

	/**
	 * @var string Created date field
	 */
	protected $created = 'created';

	/**
	 * @var string Created date format
	 */
	protected $created_format = 'Y-m-d H:i:s';

	/**
	 * @var string Modified date field
	 */
	protected $modified = 'modified';

	/**
	 * @var string Created date format
	 */
	protected $modified_format = 'Y-m-d H:i:s';

	/**
	 * @var array Table fields
	 */
	protected $fields = array();

	/**
	 * @var array Table relationships
	 */
	protected $relationships = array();

	/**
	 * @var array Fields to search when using the search filter
	 */
	protected $searchable = array();

	/**
	 * @var bool Strip stop words out of searches
	 */
	protected $strip_stopwords = TRUE;

	/**
	 * @var bool Auto find model fields (Better performance to define them manually)
	 */
	protected $auto_fields = FALSE;

	/**
	 * @var bool Cache fields found when using auto fields
	 */
	protected $auto_field_cache = TRUE;

	/**
	 * @var int Result count
	 */
	protected $count = 0;

	/**
	 * @var int Results to display per page
	 */
	protected $per_page = 20;

	/**
	 * @var int Current page
	 */
	protected $page = 1;

	/**
	 * @var object Result pagination
	 */
	protected $pagination;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
        
        // Automatically find fields if the fields array is empty
        if (empty($this->fields))
        {
            $this->auto_fields = TRUE;
        }

		// Are we auto finding the fields
		if ($this->auto_fields === TRUE && $this->table !== '')
		{
			if ($this->auto_field_cache && $fields = $this->cache->get($this->table().'_model_fields'))
			{
				$this->fields = $fields;
			}
			else
			{
				$fields = $this->db->field_data($this->table());

				if (is_array($fields))
				{
					foreach ($fields as $field)
					{
						$this->fields[$field->name] = array();
					}

					// Store cache for 60 minutes if we are not in a development environment
					if ($this->auto_field_cache && ENVIRONMENT !== 'development')
					{
						$this->cache->save($this->table().'_model_fields', $this->fields, (60 * 60));
					}
				}
			}
		}
	}

	/**
	 * Get the table name
	 *
	 * @return string
	 */
	public function table()
	{
		return $this->table;
	}

	/**
	 * Get the table primary key
	 *
	 * @return string
	 */
	public function primary()
	{
		return $this->primary;
	}

	/**
	 * Get the table fields
	 *
	 * @return array
	 */
	public function fields()
	{
		return $this->fields;
	}

	/**
	 * Get the table relationships
	 *
	 * @return array
	 */
	public function relationships()
	{
		return $this->relationships;
	}

	/**
	 * Get the searchable fields
	 *
	 * @return array
	 */
	public function searchable()
	{
		return $this->searchable;
	}

	/**
	 * Get the pagination object
	 *
	 * @return object
	 */
	public function pagination()
	{
		return $this->pagination;
	}

	/**
	 * Saves a record
	 *
	 * @param array $data Data to save
	 * @param int $id Record id to update
	 * @param array $options Additional query options
	 * @return int
	 */
	public function save(array $data, $id = NULL, array $options = array())
	{
		return ($id != NULL OR ! empty($options)) ? $this->update($data, $id, $options) : $this->create($data);
	}

	/**
	 * Creates a record
	 *
	 * @param array $data Data to save
	 * @return int
	 */
	public function create(array $data)
	{
		// Do common pre save tasks
		$data = $this->pre_save($data, TRUE);

		// Fire before update and before save event
		$data = $this->trigger('before_create', $data);
		$data = $this->trigger('before_save', $data);

		// Insert the record
		$this->db->insert($this->table(), $data);

		// Get the records id if successful
		$data['id'] = $this->db->affected_rows() > 0 ? $this->db->insert_id() : NULL;

		if ($data['id'])
		{
			$this->trigger('after_create', $data);
			$this->trigger('after_save', $data);
		}

		return $data['id'];
	}

	/**
	 * Updates a record
	 *
	 * @param array $data Data to save
	 * @param int $id Record id to update
	 * @param array $options Additional query options
	 * @return int
	 */
	public function update(array $data, $id = NULL, array $options = array())
	{
		// Do common pre save tasks
		$data = $this->pre_save($data);

		// Ensure we have a where option
		if ( ! isset($options['where']) )
		{
			$options['where'] = array();
		}

		// Set the id if its not NULL
		if ($id !== NULL)
		{
			array_push($options['where'], array($this->primary(), $id));

			$data['id'] = $id;
		}

		// Fire before update and before save event
		$data = $this->trigger('before_update', $data);
		$data = $this->trigger('before_save', $data);

		// Build the query
		$query = $this->build_query($options);

		// Update the record
		$query->update($this->table(), $data);

		// For consistency we return the id if save is successful
		$data['id'] = $this->db->affected_rows() > 0 ? $id : NULL;

		if ($data['id'])
		{
			$this->trigger('after_update', $data);
			$this->trigger('after_save', $data);
		}

		return $data['id'];
	}

	/**
	 * Update the records created/modified date
	 *
	 * @param array $data Record data
	 * @param bool $created Update created
	 * @return array
	 */
	private function update_dates(array $data, $created = FALSE)
	{
		// Set modified field
		if ((string)$this->modified !== '')
		{
			$data[$this->modified] = date($this->modified_format);
		}

		// Update created if enabled
		if ($created && (string)$this->created !== '')
		{
			$data[$this->created] = date($this->created_format);
		}

		// Return the data back
		return $data;
	}

	/**
	 * Common tasks to do before we save a record
	 *
	 * @param array $data Record data
	 * @param bool $new Is it a new record
	 * @return array
	 */
	private function pre_save(array $data, $new = FALSE)
	{
		// Filter the data to be saved
		$data = $this->filter_fields($data);

		// We need to update the timestamps
		$data = $this->update_dates($data, $new);

		// Automatically serialize any arrays
		foreach ($data as $field => $value)
		{
			if (is_array($data[$field]))
			{
				$data[$field] = $this->serialize($value);
			}
		}

		// Retun updated data
		return $data;
	}

	/**
	 * Filter out fields that are not in the fields array
	 *
	 * @param array $data Data to be filtered
	 * @return array
	 */
	public function filter_fields(array $data = array())
	{
		$filtered = array();

		foreach ($data as $field => $data)
		{
			if (is_int($field) && is_string($data))
			{
				$field = $data;
			}

			// Add it to the filter array if the key exists
			if (array_key_exists($field, $this->fields))
			{
				$filtered[$field] = $data;
			}
		}

		// Return filtered data
		return $filtered;
	}

	/**
	 * Find record(s)
	 *
	 * @param mixed $type Type of find we are doing
	 * @param array $options Find options
	 * @return array|object
	 */
	public function find($type = 'all', array $options = array())
	{
		// Merge our options with the default ones
		$options = $this->merge_default_find_options($options);

		// Have we set an alias?
		$alias = isset($options['alias']) ? ' '.$options['alias'] : '';

		// If none in options is there one attached to the model?
		if ($alias === '')
		{
			$alias = trim((string)$this->alias) !== '' ? ' '.$this->alias : '';
		}

		// If type is an ID move it into the where clause and set type as first
		if (is_numeric($type))
		{
			$options['where'][] = array(($alias !== '' ? $alias : $this->table).'.id', $type);
			$type = 'first';
		}

		// Setup pagination
		if ($options['paginate'] === TRUE)
		{
			// We need to remove some options for the count
			$count_options = $options;

			// Ensure we don't paginate a count
			$count_options['paginate'] = FALSE;

			// Ignore common elements
			$count_options['ignore_common'] = TRUE;

			// Don't set an order
			if (isset($count_options['order_by']))
			{
				unset($count_options['order_by']);
			}

			// Get the the results count
			$this->count = $this->find('count', $count_options);

			// Assign the page number if its set
			if (is_numeric($options['page']))
			{
				$this->page = $options['page'];
			}

			// Determine page numbers for pagination
			$this->set_pagination();

			// Set the limit for pagination
			if (is_object($this->pagination))
			{
				$options['limit'][] = array($this->pagination->per_page, $this->pagination->first_page - 1);
			}
		}

		// Do we need to set the search?
		if (isset($options['search']))
		{
			$search_query = $this->set_search($options['search']);

			if ( ! empty($search_query))
			{
				$options['where'][] = $search_query;
			}
		}

		// Fire before find event
		$this->trigger('before_find', $options);

		// Build the query
		$query = $this->build_query($options);

		// Ensure the return_as is lower case
		$options['return_as'] = strtolower($options['return_as']);

		// Fully formatted FROM clause
		$from = $this->table.$alias;

		// Find data based on set type
		switch($type)
		{
			case 'all':
				$result = $query->get($from)->result();
				break;
			case 'first':
				$result = $query->get($from)->row();
				break;
			case 'count':
				return $query->count_all_results($from);
			default:
				$result = NULL;
				break;
		}

		// Process result(s)
		$result = $this->process($result);

		// Return the data
		switch (strtolower($options['return_as']))
		{
			case 'json':
				return json_encode($result);
			default:
				return $result;
		}
	}

	/**
	 * Process the records relational object
	 *
	 * @param array|object $records The record(s)
	 * @return array|object
	 */
	private function process($records)
	{
		// Remember if we need to return the record as an object
		$return_as_object = is_object($records);

		// Nothing to do here if the record is null
		if ($records === NULL)
		{
			return NULL;
		}

		// Ensure that we are working with an array
		if ( ! is_array($records))
		{
			$records = array($records);
		}

		// Ensure that the record class is loaded
		$this->load->library('Record');

		$rows = array();
		foreach ($records as $record)
		{
			// Instantiate the record
			$record = new Record($record, $this);

			// Trigger the after find event
			$record = $this->trigger('after_find', $record);

			// Add record to found rows
			$rows[] = $record;
		}

		return $return_as_object ? array_shift($rows) : $rows;
	}

	/**
	 * Merge default find options with the current ones
	 *
	 * @param array $options Find options
	 * @return array
	 */
	private function merge_default_find_options(array $options)
	{
		// Merge defaults
		$options = array_merge($this->default_find_options(), $options);

		// Check to see if we have common options to merge
		if ( ! isset($options['ignore_common']) OR (bool)$options['ignore_common'] === FALSE)
		{
			foreach (array_keys($options) as $option)
			{
				$method = 'common_'.$option;

				// Check that the common method exists and it has been enabled
				if (isset($options[$method]) && $options[$method] === TRUE && method_exists($this, $method))
				{
					// Get the common data
					$data = $this->$method();

					// If we have data, merge it
					if (is_array($data) && ! empty($data))
					{
						$options[$option] = array_merge($options[$option], $data);
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Default find options
	 *
	 * @return array
	 */
	private function default_find_options()
	{
		return array(
			'common_select'		=> TRUE,
			'common_join'		=> TRUE,
			'common_where'		=> TRUE,
			'ignore_common'		=> FALSE,
			'paginate'			=> FALSE,
			'distinct'			=> FALSE,
			'page'				=> $this->page,
			'per_page'			=> $this->per_page,
			'return_as'			=> 'object',
			'select'			=> array(),
			'select_max'		=> array(),
			'select_min'		=> array(),
			'select_avg'		=> array(),
			'select_sum'		=> array(),
			'join'				=> array(),
			'where'				=> array(),
			'or_where'			=> array(),
			'where_in'			=> array(),
			'or_where_in'		=> array(),
			'where_not_in'		=> array(),
			'or_where_not_in'	=> array(),
			'like'				=> array(),
			'or_like'			=> array(),
			'not_like'			=> array(),
			'or_not_like'		=> array(),
			'group_by'			=> array(),
			'having'			=> array(),
			'or_having'			=> array(),
			'order_by'			=> array(),
			'limit'				=> array()
		);
	}

	/**
	 * Compiles a database query
	 *
	 * @param array $options Query options
	 * @return array
	 */
	private function build_query(array $options)
	{
		foreach ($options as $method => $option)
		{
			// Force the method to be lower case
			$method = strtolower($method);

			// Check the method exists in the db class and that the value is an array
			if (method_exists($this->db, $method) && is_array($option) && ! empty($option))
			{
				foreach ($option as $parameters)
				{
                    // Call the method
					call_user_func_array(array($this->db, $method), (array)$parameters);
				}
			}
		}

		// Set the distinct clause if we have enabled it
		if (isset($options['distinct']) && $options['distinct'] === TRUE)
		{
			$this->db->distinct();
		}

		return $this->db;
	}

	/**
	 * Set the query search keywords
	 *
	 * @param string $keywords Search keywords
	 * @return array
	 */
	private function set_search($keywords)
	{
		// Are we stripping out stopwords?
		if ($this->strip_stopwords && function_exists('strip_words'))
		{
			$keywords = strip_words((string)$keywords, $this->config->item('stopwords'));
		}

		if (empty($this->searchable) OR (string)$keywords === '')
		{
			return array();
		}

		$i = 0;
		$where = '';
		foreach ($this->searchable as $field)
		{
			if ($i === 0)
			{
				$where .= '(';
			}

			$where .= $field.' LIKE '.$this->db->escape('%'.$keywords.'%');

			if ($i === (count($this->searchable) - 1))
			{
				$where .= ')';
			}
			else
			{
				$where .= ' OR ';
			}

			$i++;
		}

		return $where !== '' ? array($where) : array();
	}

	/**
	 * Sets up the pagination
	 *
	 * @return array
	 */
	private function set_pagination()
	{
		// A result count is required
		if ( ! $this->count) {
			return NULL;
		}

		// If page is not set lets try to get it from the query string
		if ( ! $this->page)
		{
			$this->page = $this->input->get('page') ? $this->input->get('page') : 1;
		}

		// Create the pagination object
		$this->pagination = new stdClass;
		$this->pagination->current_page	= (int)$this->page;
		$this->pagination->per_page		= $this->per_page;
		$this->pagination->first_page	= (($this->page - 1) * $this->per_page) + 1;
		$this->pagination->last_page	= min((($this->page) * $this->per_page), $this->count);
		$this->pagination->total_pages	= ceil($this->count / $this->per_page);
		$this->pagination->total_items	= $this->count;

		return $this->pagination;
	}

	/**
	 * Delete a record
	 *
	 * @see $this->find()
	 * @param mixed $type Find type
	 * @param array $options Find options
	 */
	public function delete($type = 'all', array $options = array())
	{
		// Search for records to delete
		$records = $this->find($type, $options + array('return_as' => 'object'));

		// If a singular is returned make sure its in a loopable array
		if (is_object($records))
		{
			$records = array($records);
		}

		// We need to know what our primary field is for the id
		$primary = $this->primary();

		// Get ids of records to be deleted
		$ids = array();
		if ($records !== NULL)
		{
			foreach ($records as $record)
			{
				array_push($ids, $record->$primary);

				// Trigger the before delete event
				$this->trigger('before_delete', $record);
			}
		}

		if ( ! empty($ids))
		{
			// Do delete process
			$this->db->where_in($primary, $ids)->delete($this->table);
			$deleted = $this->db->affected_rows() > 0;

			// Trigger the after delete if successful
			if ($deleted)
			{
				foreach ($records as $record)
				{
					$this->trigger('after_delete', $record);
				}
			}
		}

		return isset($deleted) ? $deleted : FALSE;
	}

	/**
	 * Serializes an array of data
	 *
	 * @param array $data
	 * @return string
	 */
	public function serialize($data)
	{
		return base64_encode(serialize($data));
	}

	/**
	 * Unserializes data
	 *
	 * @param string $data
	 * @return array
	 */
	public function unserialize($data)
	{
		return unserialize(base64_decode($data));
	}

	/**
	 * Trigger an event
	 *
	 * @param string $event The event to trigger
	 * @param array|object $data Data to pass to the event
	 * @return array|object $data
	 */
	private function trigger($event, $data = array())
	{
		if (method_exists($this, $event) === TRUE)
		{
			// Ensure we return the data in the correct format
			if (is_array($data))
			{
				$method = 'is_array';
			}
			else if (is_object($data))
			{
				$method = 'is_object';
			}

			// Call the event
			$called = $this->$event($data);

			// Merge into data if its a valid
			if ($method($called))
			{
				$data = $called;
			}
		}

		return $data;
	}

	/**
	 * Common SELECT that will be used for finding records
	 *
	 * @return array
	 */
	protected function common_select()
	{
		return array();
	}

	/**
	 * Common JOIN that will be used for finding records
	 *
	 * @return array
	 */
	protected function common_join()
	{
		return array();
	}

	/**
	 * Common WHERE that will be used for finding records
	 *
	 * @return array
	 */
	protected function common_where()
	{
		return array();
	}

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */