<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ReferenceModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_country_codes($array = FALSE)
	{
		$this->db->select('id, name, code')->from('ref_country_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_carriers()
	{
		$this->db->select('id, name')->from('ref_carriers'); 
		$query = $this->db->get();
	    return $query->result();
	}
	
	
	function get_currency_codes($array = FALSE)
	{
		$this->db->select('id, description, code')->from('ref_currency_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_container_types($carrier_id, $array = FALSE)
	{
		$this->db->select('id, container_type, carrier, description')->from('ref_container_types')->where('carrier', $carrier_id); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function search_cities($search_term, $page=NULL, $page_size=NULL, $array=FALSE)
	{
		$this->db->select("id, city_name, state, country_name, country_code");
		$this->db->from('ref_cities_search');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		
		if(isset($page) && isset($page_size)){
			if($page == 1){
				// get the initial page
				$this->db->limit($page_size);
			}else{
				// get the next page
				$this->db->limit($page_size*$page, $page_size*($page-1));
			}
		}
		
		$query = $this->db->get();
		$data['results'] = $query->result_array();
		
		$this->db->select("count(*) as total");
		$this->db->from('ref_cities_search');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$query = $this->db->get();
		$data['total'] = $query->row()->total;
		
		return $data;
		
		
	}
	
	function search_ports($search_term, $page=NULL, $page_size=NULL, $array=FALSE)
	{
		$this->db->select("id, name, country_code, port_code, country_code, rail, road, airport, ocean, found");
		$this->db->from('ref_ports');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$this->db->order_by("found", "desc");
		
		if(isset($page) && isset($page_size)){
			if($page == 1){
				// get the initial page
				$this->db->limit($page_size);
			}else{
				// get the next page
				$this->db->limit($page_size*$page, $page_size*($page-1));
			}
		}
		
		$query = $this->db->get();
		$data['results'] = $query->result_array();
		
		$this->db->select("count(*) as total");
		$this->db->from('ref_ports');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$query = $this->db->get();
		$data['total'] = $query->row()->total;
		
		return $data;
		
		
	}
	
	
	
	/*
	* list of transport types:
	* container yard, rail, truck
	*/
	function get_transport_types()
	{
		$query = $this->db->get('ref_transport_types');
		return $query->result();
	}
	
	/*
	* list of leg types:
	* 1. origin, via, destination
	*/
	function get_leg_types()
	{
		$query = $this->db->get('ref_leg_types');
		return $query->result();
	}
	
	
	function get_cargo_types()
	{
		$query = $this->db->get("ref_cargo_types");
		return $query->result();
	}
	
	/*
	* utility function to replace white space with %
	* and strips all commas out
	*/
	function clean_search_term($search_term){
		// strip leading and ending whitespace and remove commas
		$string = str_replace(",", "", trim($search_term));
		//Clean multiple dashes or whitespaces
	    //$string = preg_replace("/[\s]+/", " ", $string);
	    //Convert whitespaces and underscore to %
	    //$string = preg_replace("/[\s]/", "%", $string);
	    return $string;
	}
	
	
}
/** end model **/