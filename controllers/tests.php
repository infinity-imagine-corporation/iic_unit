<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends MX_Controller {
	
	// -------------------------------------------------------------------------
	// Constructor
	// -------------------------------------------------------------------------

	function __construct()
	{
		parent::__construct();
		
		$this->db = $this->load->database('test', TRUE);
		$this->load->library('unit_test');
	}

	// -------------------------------------------------------------------------
	
	public function run($module = NULL, $controller = NULL, $mode = 'html')
	{
		if (is_null($module))
		{
			echo 'Please enter module name';
			return;
		}
		elseif (is_null($controller))
		{
			$this->run_all($module);
		}
		elseif ($controller == 'cli')
		{
			$this->run_all($module, 'cli');
		}
		else
		{
			$data['title'] = $module.'/'.$controller;

			include './application/modules/'.$module.'/controllers/'.$controller.'.php';

			$_methods = get_class_methods($controller);

			// Unset unnecessary methods
			unset($_methods[array_search('__construct', $_methods)]);
			unset($_methods[array_search('__get', $_methods)]);
			unset($_methods[array_search('index', $_methods)]);

			// Rebase array key
			$_methods = array_values($_methods);

			foreach ($_methods as $key => $method_name) 
			{
				$this->benchmark->mark(strval($key));

				// Run test
				Modules::run($module.'/'.$controller.'/'.$method_name, 'integrated_test');

				// Process test result
				$_result = $this->calc_test_result($this->unit->result());

				// Clear test
				$this->unit->clear_result();

				$_result['title'] = $method_name;

				$data['module_result_list'][$method_name] = $_result;
			}

	 		$this->benchmark->mark(strval(count($_methods)));
			
			// Count passed and failed
			$data['passed'] = 0;
			$data['failed'] = 0;
			$data['elapsed_time'] = 0;
			
			$loop = 0;

			foreach ($data['module_result_list'] as $module => $module_data)
			{
				$data['module_result_list'][$module]['elapsed_time'] = $this->benchmark
																			->elapsed_time(
																				strval($loop), 
																				strval($loop+1));

				$data['elapsed_time'] += $data['module_result_list'][$module]['elapsed_time'];

				foreach ($module_data['result_list'] as $key => $value) 
				{
					if($value['Result'] == 'Passed')
					{
						$data['passed']++;
					}
					else 
					{
						$data['failed']++;
					}
				}

				$loop++;
			}
			
			$data['total_assertions'] = $data['passed'] + $data['failed'];
			
			// Report	
			if($mode == 'cli')	
			{
				$this->load->view('run_cli', $data);
			}
			elseif ($mode == 'html') 
			{
				$this->load->view('run_html', $data);
			}
			elseif ($mode == 'integrated_test') 
			{
				return $data;
			}
		}
	}

	// -------------------------------------------------------------------------
	
	public function run_all($module, $mode = 'html')
	{
		// Get all file that content $module

		$this->load->helper('directory');

		$_dir_map = directory_map('./application/modules/'.$module.'/controllers');

		//print_r($_dir_map);

		$_file_list = array();

		foreach ($_dir_map as $key => $value) 
		{
			if(is_int(strpos($value, 'test_')))
			{
				array_push($_file_list, $value);
			}
		}

		//print_r($_file_list);

		$_data = array();
		$_data['title'] = $module;
		$_data['total_test'] = 0;
		$_data['passed'] = 0;
		$_data['failed'] = 0;

		$this->benchmark->mark('start_integrate');

		foreach ($_file_list as $file_name) 
		{
			$_controller = explode('.', $file_name);

			$_data['module_result_list'][$file_name] = $this->run($module, $_controller[0], 'integrated_test');

			$_data['total_test'] += count($_data['module_result_list'][$file_name]['module_result_list']);
			$_data['passed'] += $_data['module_result_list'][$file_name]['passed'];
			$_data['failed'] += $_data['module_result_list'][$file_name]['failed'];
		}

		$this->benchmark->mark('end_integrate');
		
		$_data['total_assertions'] = $_data['passed'] + $_data['failed'];

		//print_r($_data);

		// Report	
		if($mode == 'cli')	
		{
			$this->load->view('run_all_cli', $_data);
		}
		elseif ($mode == 'html') 
		{
			$this->load->view('run_all_html', $_data);
		}
	}

	// -------------------------------------------------------------------------
	
	/**
	 * Calculate passed and failed case
	 * 
	 * @access	public
	 * @param	array
	 * @return	array
	 */
	
	public function calc_test_result($result_list)
	{
		$data['result_list'] = $result_list;
		$data['passed'] = 0;
		$data['failed_list'] = array();
		$data['total_assertions'] = count($data['result_list']);
		
		foreach ($data['result_list'] as $key => $value) 
		{
			if($value['Result'] == 'Passed') 
			{
				$data['passed']++;
			}
			else 
			{
				array_push($data['failed_list'], $value);
			}
		}
		
		$data['failed'] = count($data['failed_list']);
		
		return $data;
	}
	
	// -------------------------------------------------------------------------
}


/* End of file tests.php */
/* Location: application/modules/tests/controllers/tests.php */