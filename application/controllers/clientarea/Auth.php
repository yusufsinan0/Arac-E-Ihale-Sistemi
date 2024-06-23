<?php

/**
 * @property M_Dashboard $dm
 * @property ClientsModel $clientsModel
 */

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Dashboard', 'dm');
        $this->load->model('ClientsModel', 'clientsModel');
        $this->load->helper("client");
    }

    public function process($type)
    {
        $settings = $this->dm->getSettings();
        checkClientSession(false, true);
        switch ($type){
            case "login":
                try {
                    $post = $this->input->post();

                    $errors = [];
                    if(!isset($post["email"]) || empty($post["email"]) || !filter_var($post["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "E-posta eksik.";
                    if(!isset($post["password"]) || empty($post["password"])) $errors[] = "Parola eksik.";
                    if(count($errors) > 0){
                        throw new Exception(implode("<br>", $errors));
                    }
                    

                    $client = $this->clientsModel->getByEmail($post["email"]);
                    if(!$client){
                        throw new Exception("E-posta veya parola hatalı.");
                    }

                    if(!password_verify($post["password"], $client->password)){
                        throw new Exception("E-posta veya parola hatalı.");
                    }

                    $this->session->set_userdata(["client" => $client]);

                    redirect(base_url("clientarea/dashboard"));
                }catch (Exception $e){
                    $this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
                    redirect(base_url("clientarea/auth/login"));
                }
                break;
            case "register":
                try {
                    $post = $this->input->post();

                    $client = $post["client"];

                    $errors = [];

                    if(!isset($client["companyname"]) || empty($client["companyname"])) $errors[] = "Firma İsmi eksik.";
                    if(!isset($client["staffname"]) || empty($client["staffname"])) $errors[] = "Yetkili ismi eksik.";
                    if(!isset($client["email"]) || empty($client["email"]) || !filter_var($client["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "E-posta eksik.";
                    if(!isset($client["phone"]) || empty($client["phone"])) $errors[] = "Telefon numarası eksik.";
                    if(!isset($client["password"]) || empty($client["password"])) $errors[] = "Parola eksik.";

                    if(count($errors) > 0){
                        throw new Exception(implode("<br>", $errors));
                    }

                    $client["password"] = password_hash($client["password"], PASSWORD_DEFAULT);

                    if($this->clientsModel->getByEmail($client["email"])){
                        throw new Exception("Bu e-posta zaten kayıtlı");
                    }

                    $register = $this->clientsModel->insert($client);
                    if($register["status"] == "failed"){
                        throw new Exception($register["message"]);
                    }

                    $this->session->set_userdata(["client" => $this->clientsModel->get($register["insertId"])]);

                    redirect(base_url("clientarea/dashboard"));
                }catch (Exception $e){
                    if(isset($client)) $this->session->set_flashdata("postdata", $client);
                    $this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
                    redirect(base_url("clientarea/auth/register"));
                }
                break;
        }

    }

    public function login(){
        $control = checkClientSession(false, true);


        $data = [
            'pageTitle' => 'Giriş Yap',
            'companyName' => $this->dm->getCompanyName(),
            'logoUrl' => $this->dm->getLogo(),
        ];

        $this->smarty->view('clientarea/auth/login.tpl', $data);
    }

    public function register()
    {
        $control = checkClientSession(false, true);

        $settings = $this->dm->getSettings();

        $countries = json_decode(file_get_contents(__DIR__."/../../views/templates/clientarea/assets/json/country-list.json"), true);

        $data = [
            'pageTitle' => 'Kayıt Ol',
            'companyName' => $this->dm->getCompanyName(),
            'logoUrl' => $this->dm->getLogo(),
            "countries" => $countries,
        ];

        $this->smarty->view('clientarea/auth/register.tpl', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata("client");
        redirect(base_url());
    }
}
