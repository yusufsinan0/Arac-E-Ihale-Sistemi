<?php

/**
 * @property M_Dashboard $dm
 * @property QuotesModel $quotesModel
 */

class Quotes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Dashboard', 'dm');
        $this->load->helper("client");

        $control = checkClientSession(false);
        if (!$control) {
            redirect(base_url("clientarea/auth/login"));
        }

        $this->load->model('QuotesModel', 'quotesModel');

        $settings = $this->dm->getSettings();

        checkClientSession();
        $this->client = $this->session->userdata("client");
    }

    public function index(){

        $quotes = $this->quotesModel->getByClientId($this->client->id, false);


        $data = [
            'pageTitle' => 'Tekliflerim',
            "contentTpl" => "quotes/index.tpl",
            "client" => $this->client,
            "quotes" => $quotes,
            "templateType" => "clientarea",
            'companyName' => $this->dm->getCompanyName(),
            'logoUrl' => $this->dm->getLogo(),
            "pageJavascript" =>  [
                [
                    "source" => "local",
                    "src" => "pages/dashboard-ecommerce.init.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://code.jquery.com/jquery-3.6.0.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"
                ],
                [
                    "source" => "out",
                    "src" => "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"
                ],
                [
                    "source" => "local",
                    "src" => "quotes.js",
                ]
            ],
            "pageStyles" => [
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css",
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css",
                ],
                [
                    "source" => "out",
                    "src" => "https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css",
                ]
            ]
        ];

        $this->smarty->view('clientarea/main.tpl', $data);
    }

    public function view($id)
    {

        header("Content-Type: application/json");
        try {

            $quote = $this->quotesModel->get($id);
            if(!$quote || $quote->clientid != $this->client->id) throw new Exception("Teklif bulunamadı");

            $provider = $quote->provider;

            if (!file_exists(__DIR__ . "/../../libraries/Auctions/{$provider}.php")) {
                throw new Exception("Invalid provider");
            }

            $this->load->library("Auctions/{$provider}.php");
            $api = new $provider($this->dm->getSettings());


            $apiDetails = $api->getAuction($quote->auctionid);
            if ($apiDetails["status"] == "failed") throw new Exception($apiDetails["message"]);

            exit(json_encode(["status" => "success", "data" => ["api" => $apiDetails, "local" => $quote]]));
        } catch (Exception $e) {
            exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
        }
    }

    public function addQuote()
    {

        $post = $this->input->post();
        if(!$post){
            exit(json_encode(["status" => "failed", "message" => "Geçersiz erişim."]));
        }
        try {
            $this->load->library("encryption");

            $auctionId = $post["auctionId"];
            $amount = $post["amount"];
            $provider = $this->encryption->decrypt($post["provider"]);

            if(!isset($auctionId)) throw new Exception("Auction ID required");
            if(!isset($amount) || empty($amount)) throw new Exception("Amount required");

            if (!file_exists(__DIR__ . "/../../libraries/Auctions/{$provider}.php")) {
                throw new Exception("Invalid provider");
            }

            $this->load->library("Auctions/{$provider}.php");
            $api = new $provider($this->dm->getSettings());


            $control = $api->getAuction($auctionId);
            if($control["status"] == "failed") throw new Exception("İlan bulunamadı");
            if($control["data"]["status"] != "Processing") throw new Exception("İlan müsait değil");
            if(strtotime($control["data"]["expire_at"]) < strtotime("NOW")) throw new Exception("İlanın süresi geçmiş");

            $addQuote = $api->addQuoteToAuction($auctionId, $amount);
            if ($addQuote["status"] == "failed") throw new Exception($addQuote["message"]);

            $this->load->model("QuotesModel", "quotesModel");

            $isExist = $this->quotesModel->getByAuctionAndClientId($auctionId, $this->client->id, $provider);
            if($isExist){
                $update = $this->quotesModel->update($isExist->id, [
                    "amount" => $amount,
                    "quoteid" => $addQuote["data"]["quote_id"]
                ]);
                if($update["status"] == "failed"){
                    throw new Exception($update["message"]);
                }
            }else{
                $insert = $this->quotesModel->insert([
                    "clientid" => $this->client->id,
                    "name" => $control["data"]["name"],
                    "plate" => $control["data"]["plate"],
                    "provider" => $provider,
                    "auctionid" => $auctionId,
                    "quoteid" => $addQuote["data"]["quote_id"],
                    "amount" => $amount,
                ]);
                if($insert["status"] == "failed"){
                    throw new Exception($insert["message"]);
                }
            }





            exit(json_encode(["status" => "success", "message" => "İşlem başarılı"]));
        } catch (Exception $e) {
            exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
        }
    }

    public function paid($id)
    {
        header("Content-Type: application/json");
        try {

            $quote = $this->quotesModel->get($id);
            if(!$quote || $quote->clientid != $this->client->id) throw new Exception("Teklif bulunamadı");
            if($quote->paid == 1) throw new Exception("Zaten ödendi olarak işaretlenmiş");

            $provider = $quote->provider;

            if (!file_exists(__DIR__ . "/../../libraries/Auctions/{$provider}.php")) {
                throw new Exception("Invalid provider");
            }

            $this->load->library("Auctions/{$provider}.php");
            $api = new $provider($this->dm->getSettings());


            $apiDetails = $api->getAuction($quote->auctionid);
            if ($apiDetails["status"] == "failed") throw new Exception($apiDetails["message"]);

            $update = $this->quotesModel->update($id, ["paid" => 1]);


            $this->session->set_flashdata("flash_message", ["type" => 'success', 'message' => "İşlem başarılı"]);
            redirect(base_url("clientarea/quotes"));
        } catch (Exception $e) {
            $this->session->set_flashdata("flash_message", ["type" => 'danger', 'message' => $e->getMessage()]);
            redirect(base_url("clientarea/quotes"));
        }
    }
}
