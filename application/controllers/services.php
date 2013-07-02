<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('referencemodel');
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
	}
	
	
	public function cache_info()
	{
		var_dump($this->cache->cache_info());
	}
	
	/**
	* open list of all countries in database
	* @return json encoded value of all countries in table
	*/
	public function list_of_countries()
	{
		
		if(! $countries = $this->cache->get('list-countries')){
			$countries = $this->referencemodel->get_country_codes(TRUE);
			$this->cache->save('list-countries', $countries, WEEK_IN_SECONDS);	
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($countries));
	}
	
	public function list_of_container_types($carrier_id)
	{
		$key = 'container-types-'.$carrier_id;
		if(! $container_types = $this->cache->get($key)){
			$container_types = $this->referencemodel->get_container_types($carrier_id, TRUE);
			$this->cache->save($key, $container_types, DAY_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($container_types));
	}
	
	public function list_of_currency_codes()
	{
		$key = 'currency_codes';
		if(! $currency_codes = $this->cache->get($key)){
			$currency_codes = $this->referencemodel->get_currency_codes(TRUE);
			$this->cache->save($key, $currency_codes, WEEK_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($currency_codes));
	}
	
	/**
	* pages through list of
	*
	*/
	public function list_of_cities()
	{
		//$this->output->enable_profiler(TRUE);
		
		$query = $this->input->get('query');
		$page = $this->input->get('page');
		$page_size = $this->input->get('page_size');
		
		$key = 'list_of_cities-'.$query.'-'.$page.'-'.$page_size;
		
		if(! $cities = $this->cache->get($key)){
			$cities = $this->referencemodel->search_cities($query, $page, $page_size, TRUE);
			$this->cache->save($key, $cities, WEEK_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($cities));	
		
		
	}
	
	/**
	* pages through list of
	*
	*/
	public function list_of_ports()
	{
		//$this->output->enable_profiler(TRUE);
		$query = $this->input->get('query');
		$page = $this->input->get('page');
		$page_size = $this->input->get('page_size');
		
		$key = 'list-of-ports-'.$query.'-'.$page.'-'.$page_size;
		
		if(! $ports = $this->cache->get($key)){
			$ports = $this->referencemodel->search_ports($query, $page, $page_size, TRUE);
			$this->cache->save($key, $ports, WEEK_IN_SECONDS);
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($ports));	
		
		
	}
	
	public function ports_type_ahead()
	{
		//$this->output->enable_profiler(TRUE);
		$query = $this->input->get('query');
		$page_size = $this->input->get('page_size');
		
		$key = 'ports-type-ahead-'.$query."-".$page_size;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->typeahead_ports($query, $page_size);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
		
	}
	
	public function port_groups($contract_id)
	{
		$key = 'port-groups-'.$contract_id;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->get_port_groups($contract_id);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
	}
	
	public function get_ports_for_group($group_name, $contract)
	{
		$key = 'get-ports-for-group'.$group_name."-".$contract;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->get_ports_for_group($group_name, $contract);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
	}
	
	public function list_of_charge_codes($carrier_id)
	{
		$key = 'list_of_charge_codes'.$carrier_id;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->get_charge_codes_for_carrier($carrier_id);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
	
	public function list_of_tariffs($carrier_id)
	{
		$key = 'list_of_tariffs'.$carrier_id;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->get_tarriffs_for_carrier($carrier_id);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
	
	public function list_of_carrier_services($carrier_id)
	{
		$key = 'list_of_carrier_services'.$carrier_id;
		if(! $result = $this->cache->get($key)){
			$result = $this->referencemodel->get_services_for_carrier($carrier_id);
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
}
