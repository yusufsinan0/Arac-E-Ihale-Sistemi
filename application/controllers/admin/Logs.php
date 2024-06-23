<?php

/**
 * @property M_Dashboard $dm
 */
class Logs extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard', 'dm');
	}

	public function index(){
		$this->dm->checkSession('admin');

		$data = [
			'pageTitle' => 'İşlem Günlükleri',
			'contentTpl' => 'logs.tpl',
			'logoUrl' => $this->dm->getLogo(),
			'companyName' => $this->dm->getCompanyName(),
			"templateType" => "admin"
		];
		$this->smarty->view('admin/main.tpl', $data);
	}
}
