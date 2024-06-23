<?php

/**
 * @property M_Dashboard $dm
 * @property QuotesModel $quotesModel
 */

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Dashboard', 'dm');
        $this->load->model('QuotesModel', 'quotesModel');
        $this->load->helper("client");

        $control = checkClientSession(false);
        if(!$control){
            redirect(base_url("clientarea/auth/login"));
        }

        $settings = $this->dm->getSettings();

        $this->client = $this->session->userdata("client");
    }

    public function index(){

        $quotes = $this->quotesModel->getByClientId($this->client->id, 0);
        $paidQuotes = $this->quotesModel->getByClientId($this->client->id, 1);


        $data = [
            'pageTitle' => 'Genel Bakış',
            "contentTpl" => "dashboard/index.tpl",
            "client" => $this->client,
            "quotes" => $quotes,
            "paidQuotes" => $paidQuotes,
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
}
