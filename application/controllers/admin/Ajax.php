<?php

/**
 * @property M_Dashboard $dm
 * @property ProductsModel $pm
 */
class Ajax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard', 'dm');
	}


	public function getLogs(){
		$this->dm->checkSession('admin');

		$orders = [
			0 => 'id',
			1 => 'message',
			3 => 'user_ip',
			4 => 'created_date'
		];

		$countAllResults = $this->db->from('logs')->count_all_results();

		$this->db->from('logs');
		$post = $this->input->post();
		if($post['search']['value']){
			$this->db->like('message', $post['search']['value'], 'both');
			$this->db->or_like('user_ip', $post['search']['value'], 'both');
		}

		if(isset($post['order'][0]['column'])) $this->db->order_by($orders[$post['order'][0]['column']], $post['order'][0]['dir']);
		if($post['start'] > 0) $this->db->offset($post['start']);
		if($post['length'] > 0) $this->db->limit($post['length']);

		$results = $this->db->get()->result();

		$exit = [];
		$exit['data'] = $results;
		$exit['draw'] = $post['draw'];
		$exit['recordsFiltered'] = $exit['recordsTotal'] = $countAllResults;
		exit(json_encode($exit, JSON_UNESCAPED_UNICODE));
	}



	public function testMail(){
		$this->dm->checkSession('admin');

		$post = $this->input->post();

		$this->load->library('email');
		$this->load->library('encryption');
		// Set the default email config and Initialize
		$config['protocol']  = 'smtp';
		$config['smtp_host'] = $post['setting']['smtp_host'];
		$config['smtp_user'] = $post['setting']['smtp_username'];
		if(strlen($this->encryption->decrypt($post['setting']['smtp_password'])) > 1){
			$config['smtp_pass'] = $this->encryption->decrypt($post['setting']['smtp_password']);
		}else{
			$config['smtp_pass'] = $post['setting']['smtp_password'];
		}

		$config['smtp_port'] = $post['setting']['smtp_port'];
		if($post['setting']['smtp_secure'] != 'none') $config['smtp_crypto'] = $post['setting']['smtp_secure'];
		$config['mailtype']  = 'html';
		;
		$this->email->initialize($config);

		$this->email->from($post['setting']['smtp_username'], 'Deneme Mail');
		$this->email->to($this->db->get("admins")->row()->mail);
		$this->email->subject('Deneme Mail - '.$post['setting']['company_name']);
		$this->email->message('Bu bir deneme mesajıdır. Lütfen dikkate almayınız.');

		$send = $this->email->send();
		if(!$send)
		{
			exit(json_encode(['status' => 'failed', 'message' => 'Mail gönderilemedi. Hata: '.htmlentities($this->email->print_debugger())]));
		}
		else
		{
			exit(json_encode(['status' => 'success', 'message' => 'Mail gönderimi başarılı']));
		}

	}

	public function createImage($type, $image){
		switch ($type){
			case 'getProductImage':
				$this->load->model('ProductsModel', 'pm');
				$image = $this->pm->getExactImage($image);
				break;
		}

		$explode = explode('.', $image);
		$ext = $explode[array_key_last($explode)];
		switch ($ext){
			case 'jpeg':
				header('Content-Type: image/jpeg');
				$img = imagecreatefromjpeg($image);
				imagejpeg($img);
				imagedestroy($img);
				break;
			case 'jpg';
				header('Content-Type: image/jpeg');
				$img = imagecreatefromjpeg($image);
				imagejpeg($img);
				imagedestroy($img);
				break;
			case 'png';
				header('Content-Type: image/png');
				$img = imagecreatefrompng($image);
				imagepng($img);
				imagedestroy($img);
				break;
			case 'webp';
				header('Content-Type: image/webp');
				$img = imagecreatefromwebp($image);
				imagewebp($img);
				imagedestroy($img);
				break;
		}
		exit;
	}
}
