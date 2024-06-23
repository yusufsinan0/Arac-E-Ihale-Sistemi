<?php
/**
 * @property CI_DB_query_builder $db
 * @property ClientsModel $cm
 *
 * @property M_Dashboard $dm
 */

class M_Dashboard extends CI_Model
{

	public function addLog($message){
		/*		if(!$this->session->userdata('is_admin')){
					$user_id = $this->session->userdata('user_id');
				}else{
					$user_id = 0;
				}*/
		$user_ip = $this->input->ip_address();
		$this->db->insert('logs', [
			'message' => $message,
			'user_id' => 0,
			'user_ip' => $user_ip,
			'created_date' => date('Y-m-d H:i:s')
		]);
	}

	public function createApiKey(){
		while (true){
			$apiKey = bin2hex(random_bytes(20));
			$check = $this->db->from('users')->where('apiKey', $apiKey)->count_all_results();
			if($check < 1) break;
		}
		return $apiKey;
	}

	public function checkSession($type = 'admin'){
		if($type == 'admin'){
			if(!$this->session->userdata('is_admin')) header("Location: ".base_url().'admin/login');
		}
		$this->load->helper('cookie');
		$user_id = ($type == 'admin') ? $this->session->userdata('admin_id') : $this->session->userdata('user_id');
		$table = ($type == 'admin') ? "admins" : 'users';
		$count = $this->db->from($table)->where('id', $user_id)->count_all_results();
		$location = ($type == 'admin') ? "admin/login" : 'login';
		if($count < 1 || !isset($user_id)){
			if(!is_null(get_cookie('RememberMe'))){
				$getPass = $this->db->from($table)->where('password', get_cookie('RememberMe'))->get()->row();
				if(!is_null($getPass)){
					$this->session->set_userdata(['user_id' => $getPass->id, 'user_name' => $getPass->name]);
					return true;
				}
			}
			header("Location: ".base_url().$location);
			exit;
		}
	}

	public function getSettings(){
		$settings = [];
		foreach($this->db->get('settings')->result() as $item){
			$settings[$item->setting] = $item->value;
		}
		return $settings;
	}

	public function getLogo(){
		$data = $this->db->from('settings')->where('setting', 'company_logo')->get()->row();
		return isset($data->value) ? $data->value : null;
	}

	public function getCompanyName($clientId = false){
		$data = $this->db->from('settings')->where('setting', 'company_name')->get()->row();
		return isset($data->value) ? $data->value : null;
	}

	public function formatByte($bytes, $is_kb = false){
		if($is_kb === true) $bytes = $bytes * 1024;
		$byte = [];
		if ($bytes >= 1099511627776) {
			$byte = number_format($bytes / 1099511627776, 2) . ' TB';
		}elseif ($bytes >= 1073741824) {
			$byte = number_format($bytes / 1073741824, 2) . ' GB';
		}elseif ($bytes >= 1048576) {
			$byte = number_format($bytes / 1048576, 2).' MB';
		}elseif ($bytes >= 1024) {
			$byte = number_format($bytes / 1024, 2).' kB';
		} elseif ($bytes > 1 || $bytes == 0) {
			$byte = number_format($bytes / 1024, 2) .' bytes';
		} else {
			$byte = '0 bytes';
		}

		return $byte;
	}

	public function sendEmail($receiver, $subject, $message, $link){

		$this->load->library('email');
		$this->load->library('encryption');
		// Set the default email config and Initialize
		$config['protocol']  = 'smtp';
		$config['smtp_host'] = $this->db->from('settings')->where('setting', 'smtp_host')->get()->row()->value;
		$config['smtp_user'] = $this->db->from('settings')->where('setting', 'smtp_username')->get()->row()->value;
		$config['smtp_pass'] = $this->encryption->decrypt($this->db->from('settings')->where('setting', 'smtp_password')->get()->row()->value);
		$config['smtp_port'] = $this->db->from('settings')->where('setting', 'smtp_port')->get()->row()->value;
		if($this->db->from('settings')->where('setting', 'smtp_secure')->get()->row()->value != 'none') $config['smtp_crypto'] = $this->db->from('settings')->where('setting', 'smtp_secure')->get()->row()->value;
		$config['mailtype']  = 'html';

		$ayar_renk = '#3195ff';

		$mailHeader = '
  <div align="center" style="width: 100%;height: auto;color: #ffffff;max-width: 760px;margin: 0 auto;margin-top: 30px;background-color: '.$ayar_renk.';border-top-left-radius: 15px;border-top-right-radius: 15px;"><div style="padding: 15px"><h1><font face="verdana">'.$subject.'</font></h1></div></div>
  <div style="width: 100%;max-width: 760px;margin: 0 auto;color: #000;background-color: #f1f1f1;">
  <p style="padding: 40px;margin: 0;word-wrap:break-word;">';

		$mailBody = '<font face="verdana">'.$message.'<br><a href="'.$link.'" style="margin-top: 20px;margin-bottom:20px;background: '.$ayar_renk.';border: none;color: #fff;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;border-radius: 25px;"> İncele</a><br>Buton çalışmıyorsa aşağıdaki linki tarayıcı adres kısmına kopyalayınız.<br><font face="verdana" size="2"> '.$link.'</font></font  </p></div>';

		$mailFooter = '  <div style="width: 100%;max-width: 760px;background-color: '.$ayar_renk.';margin: 0 auto;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;color: #fff;"><p style="padding: 10px;;margin: 0;word-wrap:break-word;" align="center"><font face="verdana" size="2">
  ©'.date('Y').' Tüm Hakları Saklıdır.
  </font></p></div>
  <div align="center" style="height: auto;max-width: 760px;text-align: center;margin: 0 auto;margin-top: 30px;color: #c5c5c5;text-align:justify;"><font face="verdana" size="1">
  YASAL UYARI!<br>
  Bu elektronik posta mesajı ve ekleri sadece gönderildiği kişiye özeldir ve gizli bilgiler içerebilir. Eğer bu mesajı yanlışlıkla aldıysanız lütfen bu durumu tarafımıza derhal bildiriniz ve mesajı sisteminizden siliniz. Eğer doğru kişiye ulaşmadığını düşünüyorsanız, bu mesajın gizlenmesi, yönlendirilmesi, kopyalanması veya herhangi bir şekilde kullanılması yasaktır. Internet iletişiminde güvenlik ve hatasız gönderim garanti edilemeyeceğinden, mesajın yerine ulaşmaması, geç ulaşması, içeriğinin bozulması ya da mesajın virüs taşıması gibi problemler oluşabilir. Gönderen kurum bu tip sorunlardan sorumlu tutulmaz.
  </font></div><font face="verdana" size="1">
  </font>
  ';
		$body = $mailHeader.$mailBody.$mailFooter;

		$this->email->initialize($config);

		$this->email->from($this->db->from('settings')->where('setting', 'smtp_username')->get()->row()->value, $this->getCompanyName());
		$this->email->to($receiver);
		$this->email->subject($subject);
		$this->email->message($body);
		$send = $this->email->send();
		if(!$send)
		{
			$this->addLog('Mail gönderilemedi. Hata: '.$this->email->print_debugger());
			return ["status" => "failed", "message" => "Mail gönderilemedi. Lütfen destek ekibi ile iletişime geçiniz."];
		}
	else
		{
			$this->addLog('Mail Gönderildi: '.$receiver.' - '.$subject);
			return ["status" => "success", "message" => "Mail başarıyla {$receiver} adlı emaile gönderildi."];
		}
	}

	public function getLang(){
		if(isset($_SESSION['language'])){
			return $this->db->from('languages')->where('id', $_SESSION['language'])->get()->row();
		}
		return $this->db->from('languages')->where('defaultLang', 1)->get()->row();
	}

	public function setLang($id){
		$check = $this->db->from('languages')->where('id', $id)->get()->row();
		if($check && $check->active == 1){
			$_SESSION['language'] = $id;
			return true;
		}else{
			$_SESSION['language'] = $this->db->from('languages')->where('defaultLang', 1)->get()->row()->id;
		}
	}

	public function getAdminInfo(){
		return $this->db->where("id", $this->session->userdata("admin_id"))->get("admins")->row();
	}

	public function countActiveServices(){
		return $this->db->where("status", "Active")->count_all_results("services");
	}

	public function countPendingOrders(){
		return $this->db->where("status", "Pending")->count_all_results("orders");
	}

	public function countPendingTickets(){
		return $this->db->where("status !=", "Closed")->count_all_results("tickets");
	}

	public function countTotalUsers(){
		return $this->db->count_all_results("clients");
	}

	public function checkClientSession(){
		if(!$this->session->userdata("client")->id) redirect(base_url("login"));

		$this->load->model("ClientsModel", "clientsmodel");

		$this->load->helper('cookie');
		$clientInfos = $this->session->userdata('client');
		$client = $this->clientsmodel->get($clientInfos->id);
		if(!$client){
			if(!is_null(get_cookie('clientRememberMe'))){
				$getPass = $this->db->from("clients")->where('password', get_cookie('RememberMe'))->get()->row();
				if(!is_null($getPass)){
					$this->session->set_userdata(['client' => $getPass]);
					return true;
				}
			}
			if(isset($clientInfos)){
				$this->session->unset_userdata("client");
			}
			header("Location: ".base_url().$location);
			exit;
		}

		if($clientInfos != $client){
			$this->session->set_userdata(["client" => $client]);
			return true;
		}

		return;
	}

    public function getAdmins(){
        return $this->db->where("active", 1)->get("admins")->result();
    }
}
