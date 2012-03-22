<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
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
		$this->view->set('header', 'section', 'Welcome'); //Passed to header.php in templates
		$this->view->set('header', 'header_nav', $this->load->view('header_nav', array(), TRUE)); //Loading View Partial to Templates
	}
	
	/* Default Welcome Index
	 * Nothing special to see here....
	 * */
	public function index()
	{
		//FirePHP..
		$this->firephp->log('Yo! FirePHP Installed!');
		$this->view->render();
	}


	/* Append and Defualt rendering...
	 *
	 * */
	public function subpage()
	{
		$this->view->append('header', 'section', ' > Subpage');
		$this->view->render();
	}
	
	
	/* Append and Defualt rendering...
	 *
	 * */
	public function override()
	{
		$this->view->set('header', 'section', 'Overriden Subpage');

		$test_data = array(
			'testvar'=>'Test Var Value',
			'testvar2'=>'Test Var Value 2',
			'testvar3'=> array(1,2,3,4,5)
		);
		
		$this->view->set('content', $test_data);
		
		$this->view->override('override');
		$this->view->render();
	}
	
	/* Ajax Calls...
	 * Return non-parsed content.. no doc, header, footer, etc..
	 * */
	public function ajax_request()
	{
		$this->view->set('header', 'section', 'AJAX CALL'); //This will not render!
		
		$test_data = array(
			'testvar'=>'Test Var Value',
			'testvar2'=>'Test Var Value 2',
			'testvar3'=> array(1,2,3,4,5)
		);
		
		$this->view->set('content', $test_data);
		
		$this->view->override('override');
		$this->view->render(TRUE);
	}
	
    public function repeat($inputString)
    {
        return $inputString;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */