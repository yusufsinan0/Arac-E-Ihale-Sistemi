<?php

/**
 * @property M_Dashboard $dm
 */
class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Dashboard', 'dm');
        $this->load->helper("client");

        if(checkClientSession(false)){
            $this->client = $this->session->userdata("client");
        };

    }

    public function index()
    {
        $this->load->library("encryption");

        $apis = glob(__DIR__."/../libraries/Auctions/*.php", GLOB_BRACE);
        $auctions = [];
        foreach ($apis as $api){
            if(basename($api) == "AuctionApi.php") continue;
            $this->load->library("Auctions/".basename($api));

            $apiName = str_replace(".php", "", basename($api));

            $object = new $apiName($this->dm->getSettings());

            $apiAuctionsOpen = $object->getAuctions("Processing", 1);
            if($apiAuctionsOpen["status"] != "success") continue;

            $auctions[$apiName] = [
                "encryptedName" => $this->encryption->encrypt($apiName),
                "auctions" => [
                    "open" => $apiAuctionsOpen["status"] == "success" ? $apiAuctionsOpen : null,
                ]
            ];
        }

        $data = [
            'pageTitle' => "Ana Sayfa",
            "client" => $this->client ?? null,
            "auctions" => $auctions,
            "templateType" => "clientarea",
            'companyName' => $this->dm->getCompanyName(),
            'logoUrl' => $this->dm->getLogo(),
        ];

        $this->smarty->view('home/index.tpl', $data);
    }

    public function controlAuction()
    {
        header("Content-Type: application/json");

        $post = $this->input->post();
        if(!$post){
            exit(json_encode(["status" => "failed", "message" => "Geçersiz erişim."]));
        }
        try {
            $this->load->library("encryption");

            $auctionId = $post["id"];
            $provider = $this->encryption->decrypt($post["provider"]);


            if (!file_exists(__DIR__ . "/../libraries/Auctions/{$provider}.php")) {
                throw new Exception("Invalid provider");
            }

            $this->load->library("Auctions/{$provider}.php");
            $api = new $provider($this->dm->getSettings());


            $quote = $api->getAuction($auctionId);
            if ($quote["status"] == "failed") throw new Exception($addQuote["message"]);


            exit(json_encode($quote));
        } catch (Exception $e) {
            exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
        }
    }
}