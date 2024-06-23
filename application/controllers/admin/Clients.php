<?php

/**
 * @property M_Dashboard $dm
 * @property ClientsModel $clientsModel
 */
class Clients extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard', 'dm');
		$this->load->model('ClientsModel', 'clientsModel');

		$this->load->helper("client");

		$this->dm->checkSession();
	}

	public function index()
	{
		$clients = $this->clientsModel->getAll();
		$data = [
			'pageTitle' => 'Müşteriler',
			'contentTpl' => 'clients/index.tpl',
			'logoUrl' => $this->dm->getLogo(),
			'companyName' => $this->dm->getCompanyName(),
			'clients' => $clients,
			"templateType" => "admin"
		];
		$this->smarty->view('admin/main.tpl', $data);
	}

	public function add()
	{
		$data = [
			'pageTitle' => 'Müşteri Ekle',
			'contentTpl' => 'clients/add.tpl',
			'logoUrl' => $this->dm->getLogo(),
			'companyName' => $this->dm->getCompanyName(),
			"templateType" => "admin",
			"parentPage" => [
				"link" => base_url("admin/clients"),
				"name" => "Müşteriler"
			]
		];

		$this->smarty->view('admin/main.tpl', $data);
	}

	public function view($id)
	{
		$control = $this->clientsModel->get($id);
		if (!isset($control)) {
			$this->session->set_flashdata("flash_message", ["type" => "danger", 'message' => 'Müşteri bulunamadı.']);
			redirect(base_url("admin/clients"));
		}

		$data = [
			'pageTitle' => 'Müşteri Detayları',
			'contentTpl' => 'clients/view.tpl',
			'logoUrl' => $this->dm->getLogo(),
			'companyName' => $this->dm->getCompanyName(),
			"templateType" => "admin",
			"info" => $control,
			"parentPage" => [
				"link" => base_url("admin/clients"),
				"name" => "Müşteriler"
			]
		];
		$this->smarty->view('admin/main.tpl', $data);
	}

	public function save($type)
	{
		$post = $this->input->post();
		$this->load->helper("site");
		$this->load->helper("client");

		if (!$post) redirect(base_url("admin/client"));

		switch ($type) {
			case 'add':
				try {
					$email = $this->clientsModel->getByEmail($post["email"]);
					if ($email) {
						$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => 'Aynı e-postayı kullanan bir müşteri var!']);
						redirect(base_url("admin/clients/add"));
						break;
					}
					$username = $this->clientsModel->getByUsername($post["username"]);
					if ($username) {
						$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => 'Aynı e-postayı kullanan bir müşteri var!']);
						redirect(base_url("admin/clients/add"));
						break;
					}

					$data = [
						"companyname" => $post["companyname"],
						"staffname" => $post["staffname"],
						"email" => $post["email"],
						"password" => password_hash($post["password"], PASSWORD_DEFAULT),
						"phone" => getNumericsFromPhoneInput($post["phone"]),
						"taxoffice" => $post["taxoffice"],
						"taxnumber" => $post["taxnumber"],
					];
					$insert = $this->clientsModel->insert($data);
					if(!$insert["status"] == "failed"){
						throw new Exception($insert["message"]);
					}

					$this->session->set_flashdata("flash_message", ["type" => 'success', 'message' => "Müşteri başarıyla oluşturuldu."]);
					redirect(base_url("admin/clients/view/{$insert["insertId"]}"));
				} catch (Exception $e) {
					$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
					$this->session->set_flashdata("postdata", $post);
					redirect(base_url("admin/clients/add"));
				}
				break;
			case 'edit':
			try {
				$control = $this->clientsModel->get($post["id"]);
				if (!isset($control)) {
					$this->session->set_flashdata("flash_message", ["type" => "danger", 'message' => 'Kullanıcı bulunamadı.']);
					redirect(base_url("admin/clients"));
				}

				if ($control->email != $post["email"]) {
					$email = $this->clientsModel->getByEmail($post["email"]);
					if ($email) {
						throw new Exception("Aynı e-postayı kullanan bir müşteri var!");
					}
				}

				if ($control->username != $post["username"]) {
					$username = $this->clientsModel->getByUsername($post["username"]);
					if ($username) {
						throw new Exception("Aynı kullanıcı adını kullanan bir müşteri var!");
					}
				}

				$data = [
					"companyname" => $post["companyname"],
					"staffname" => $post["staffname"],
					"email" => $post["email"],
					"phone" => $post["phone"],
					"taxoffice" => $post["taxoffice"],
					"taxnumber" => $post["taxnumber"],
				];
				if (!empty($post["password"])) {
					$data["password"] = password_hash($post["password"], PASSWORD_DEFAULT);
				}

				$update = $this->clientsModel->update($post["id"], $data);
				if(!$update["status"] == "failed"){
					throw new Exception($update["message"]);
				}


				$this->session->set_flashdata("flash_message", ["type" => 'success', 'message' => "Müşteri başarıyla oluşturuldu."]);
				redirect(base_url("admin/clients/view/{$control->id}"));
			} catch (Exception $e) {
				$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
				redirect(base_url("admin/clients/view/{$control->id}"));
			}
		}
	}

	public function delete($id)
	{
		$control = $this->clientsModel->get($id);
		if (!isset($control)) {
			$this->session->set_flashdata("flash_message", ["type" => "danger", 'message' => 'Kullanıcı bulunamadı.']);
			redirect(base_url("admin/clients"));
		}

		$delete = $this->clientsModel->delete($id);

		$this->session->set_flashdata("flash_message", ["type" => $delete["status"] == "failed" ? 'danger' : 'success', 'message' => $delete["status"] == "failed" ? $delete["message"] : 'İşlem başarıyla tamamlandı.']);
		redirect(base_url("admin/clients"));
	}
    public function toggle($id){
		try {
			$control = $this->clientsModel->get($id, false);
			if(!$control){
				$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => "Hesap bulunamadı"]);
				redirect(base_url("admin/clients"));
			}

			$update = $this->clientsModel->update($control->id, [
				"active" => !$control->active
			]);
			if($update["status"] == "failed"){
				throw new Exception($update["message"]);
			}

			$this->session->set_flashdata("flash_message", ["type" => 'success', 'message' => "İşlem başarılı"]);
			redirect(base_url("admin/clients"));
		}catch (Exception $e){
			$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
			redirect(base_url("admin/clients"));
		}
	}

	public function login($id){
		try {
			$control = $this->clientsModel->get($id, false);
			if(!$control){
				$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => "Hesap bulunamadı"]);
				redirect(base_url("admin/clients"));
			}

			$this->session->set_userdata(['client' => $control]);
			$this->session->set_userdata(["loginasadmin" => 1]);
			$this->session->set_userdata(["verified" => 1]);
			redirect(base_url("clientarea"));
		}catch (Exception $e){
			$this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
			redirect(base_url("admin/clients"));
		}
	}
}
