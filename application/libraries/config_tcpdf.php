<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__file__).'/tcpdf/tcpdf.php';
class Config_tcpdf extends TCPDF
{
	public function __construct()
	{
        parent::__construct();
	}

	

}

/* End of file config_tcpdf.php */
/* Location: ./application/libraries/config_tcpdf.php */
