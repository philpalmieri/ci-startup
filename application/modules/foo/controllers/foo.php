<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foo extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/foo
	 *	- or -  
	 * 		http://example.com/index.php/foo/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	/**
	 * Controller Construct
	 *
	 * @return void
	 * @author  phil palmieri
	 */
	function __construct()
	{
		parent::__construct();
		$this->view->set('header', 'section', 'HMVC Welcome'); //Passed to header.php in templates
		$this->view->set('header', 'header_nav', $this->load->view('header_nav', array(), TRUE)); //Loading View Partial to Templates
	}
	
	/* Default Welcome Index
	 * Nothing special to see here....
	 * */
	public function index()
	{
		$this->view->render();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */