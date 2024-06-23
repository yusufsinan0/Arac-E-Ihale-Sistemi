<?php

/**
 * @property M_Dashboard $dm
 */
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		if ($this->session->userdata('admin_id') && $this->session->userdata('is_admin')) redirect(base_url() . 'admin');

		$data = [
			'pageTitle' => 'Giriş Yap',
			'companyName' => $this->dm->getCompanyName(),
			'logoUrl' => $this->dm->getLogo()
		];

		$this->smarty->view('admin/login.tpl', $data);
	}

	public function trylogin()
	{
		$post = $this->input->post();
		if (!isset($post['username'])) {
			exit(json_encode(['status' => 'failed', 'failed_message' => 'Kullanıcı adı boş olamaz.', JSON_UNESCAPED_UNICODE]));
		} elseif (!isset($post['password'])) {
			exit(json_encode(['status' => 'failed', 'failed_message' => 'Parola boş olamaz.', JSON_UNESCAPED_UNICODE]));
		}
		$this->db->from('admins');
		$this->db->where('username', $post['username']);

		$results = $this->db->get()->row();
		if (is_null($results)){
			exit(json_encode(['status' => 'failed', 'failed_message' => 'Bu bilgiler ile giriş yapılamadı', JSON_UNESCAPED_UNICODE]));
		}

		if (isset($results)) {
			if (password_verify($post['password'], $results->password) !== true){
				exit(json_encode(['status' => 'failed', 'failed_message' => 'Bu bilgiler ile giriş yapılamadı', JSON_UNESCAPED_UNICODE]));
			}

			if(isset($post['rememberMe'])) {
				$this->input->set_cookie(array(
					'name' => 'RememberMe',
					'value' => $results->password,
					'expire' => '604800',
					'domain' => '',
					'path' => '/',
					'secure' => true,
					'httponly' => true,
				));
			}

			$this->session->set_userdata(['admin_id' => $results->id, 'user_name' => $results->name, 'is_admin' => 1, "usermail" => $results->mail]);
			$this->dm->addLog("Admin ({$results->username}) giriş yaptı.");

			$this->db->where('id', $results->id)->update('admins', ['lastLogin' => date('Y-m-d H:i:s'), 'ipAddress' => $this->input->ip_address()]);

			exit(json_encode(['status' => 'success', 'message' => "Hoşgeldiniz, {$results->name}, yönlendiriliyorsunuz.", JSON_UNESCAPED_UNICODE]));
		}
	}

	public function logout()
	{
		if (!$this->session->userdata('admin_id')) redirect('admin/login');
		$this->dm->addLog('Admin çıkış yaptı.');
		$this->session->sess_destroy();
		$this->load->helper('cookie');
		delete_cookie('RememberMe');

		redirect(base_url("admin/login"));
	}
}
