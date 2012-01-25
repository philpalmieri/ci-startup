<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Helloworld extends CI_Controller {

    /**
     * Index Page for this controller.
     * Basic echo to prove HMVC working
     */
    public function index()
    {
        $this->load->view('helloworld');
    }

}

/* End of file welcome.php */
/* Location: ./application/modules/helloworld/controllers/helloworld.php */