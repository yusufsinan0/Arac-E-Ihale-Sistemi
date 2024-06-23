<?php

/**
 * @property M_Dashboard $dm
 */
class Settings extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dashboard', 'dm');
	}

	public function index(){
		$this->dm->checkSession('admin');
		$this->load->helper('form');
		$this->load->library('encryption');

        $apis = glob(__DIR__."/../../libraries/Auctions/*.php", GLOB_BRACE);
        $loadedAPIs = [];
        foreach ($apis as $api){
            if(basename($api) == "AuctionApi.php") continue;
            $this->load->library("Auctions/".basename($api));

            $apiName = str_replace(".php", "", basename($api));

            $object = new $apiName();

            $loadedAPIs[$apiName] = [
                "name" => $object->name,
                "fields" => $object->getFields()
            ];
        }

		$getSettings = $this->db->from('settings')->get()->result();
		$settings = [];
		foreach ($getSettings as $setting){
			$settings[$setting->setting] = $setting->value;
		}

		$settings = (object)$settings;
		foreach ($settings as $key => $setting){
			if($key == 'smtp_password') $settings->$key = $this->encryption->decrypt($setting);
		}

		$data = [
			'pageTitle' => 'Genel Ayarlar',
			'contentTpl' => 'settings.tpl',
			'logoUrl' => $this->dm->getLogo(),
			'companyName' => $this->dm->getCompanyName(),
			'settings' => $settings,
			"templateType" => "admin",
            "apis" => $loadedAPIs
		];

		$this->smarty->view('admin/main.tpl', $data);
	}

	public function save(){
		$this->dm->checkSession('admin');
		$this->load->library('encryption');
		$post = $this->input->post();

		try {
			foreach($post['setting'] as $key => $item){
				if(empty($item)) continue;
				$isExist = $this->db->from('settings')->where('setting', $key)->get()->row();
				if($key == 'smtp_password') $item = $this->encryption->encrypt($item);
				if($isExist){
					$this->db->where('id', $isExist->id)->update('settings', [
						'value' => $item
					]);
					$db_error = $this->db->error();
					if(strlen($db_error['message']) > 1){
						throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
					}
				}else{
					$this->db->insert('settings', [
						'setting' => $key,
						'value' => $item
					]);
					$db_error = $this->db->error();
					if(strlen($db_error['message']) > 1){
						throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
					}
				}
				$item = false;
			}
			$this->session->set_flashdata('flash_message', ['type' => 'success', 'message' => 'Ayarlar başarıyla güncellendi!']);
			redirect('admin/settings');
			return;
		}catch (Exception $e){
			$this->dm->addLog('Ayarlar güncellenirken bir hata oluştu. Hata: '.$e->getMessage());
			$this->session->set_flashdata('flash_message', ['type' => 'danger', 'message' => 'Ayarlar güncellenirken bir hata oluştu. Lütfen sistem günlüğünü kontrol edin.!']);
			redirect('admin/settings');
			return;
		}
	}
}
