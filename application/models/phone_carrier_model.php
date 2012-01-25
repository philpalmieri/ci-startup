<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phone_carrier_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function getCarriers(array $attributes)
	{
		foreach ($attributes as $field)
		{
			$this->db->select($field)->from('phone_carrier');
			$query = $this->db->get();
			foreach ($query->result_array() as $row)
			{
				$data[] = array($field, $row[$field]);
			}
		}
		
		return $data;
	}
	
}

/* End of file phone_carrier_model.php */
/* Location: ./application/models/phone_carrier_model.php */