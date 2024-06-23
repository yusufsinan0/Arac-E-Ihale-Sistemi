<?php

/**
 * @property M_Dashboard $dm
 * @property ClientsModel $clientsModel
 */
class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard', 'dm');
		$this->load->model('ClientsModel', 'clientsModel');
		$this->dm->checkSession('admin');
	}

	public function index(){

        $countClients = count($this->clientsModel->getAll());

		$data = [
			"companyName" =>  $this->dm->getCompanyName(),
			"logoUrl" =>  $this->dm->getLogo(),
			"pageTitle" => "Ana Sayfa",
			"contentTpl" => "index.tpl",
            "clients" => $countClients,
			"currentPage" => "index",
			"templateType" => "admin",
		];

		$this->smarty->view("admin/main.tpl", $data);
	}

	public function profile(){
		$info = $this->db->from('admins')->where('id', $this->session->userdata('admin_id'))->get()->row();
		if(!$info){
			$this->session->sess_destroy();
			redirect('admin/login');
		}

		$data = [
			"companyName" =>  $this->dm->getCompanyName(),
			"logoUrl" =>  $this->dm->getLogo(),
			"pageTitle" => "Yönetici Profilim",
			"info" => $info,
			"contentTpl" => "admin/profile.tpl",
			"templateType" => "admin"
		];
		$this->smarty->view('admin/main.tpl', $data);
	}

	public function profileSave($id){
		$this->dm->checkSession('admin');
		$post = $this->input->post();



		try {
			$data = [
				'name' => $post['name'],
				'username' => $post['username'],
				'mail' => $post['mail'],
			];
			if(isset($post['password'])) $data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);

			$this->db->where('id', $id)->update('admins', $data);
			$db_error = $this->db->error();
			if(strlen($db_error['message']) > 1){
				throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
			}

			$this->session->set_userdata(['user_name' => $post['name']]);

			$this->session->set_flashdata('flash_message', ['type' => 'success', 'message' => 'Bilgiler başarıyla düzenlendi!']);
			redirect('admin/profile');
			return;
		}catch (Exception $e){
			$this->dm->addLog('Admin bilgileri düzenlenirken hata oluştu. Hata: '.$e->getMessage());
			$this->session->set_flashdata('flash_message', ['type' => 'danger', 'message' => 'Bir hata oluştu. Hata günlüğü kontrol ediniz.']);
			redirect('admin/profile');
			return;
		}
	}
}
