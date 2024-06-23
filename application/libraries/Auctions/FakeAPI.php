<?php

require_once "AuctionApi.php";

spl_autoload_register(function ($class_name) {
    if (strpos($class_name, "Gleox") !== false) {
        $file = __DIR__ . '/../' . str_replace('\\', '/', $class_name) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }

    }
});


class FakeAPI implements AuctionApi
{
    public $request;
    public $name = "FakeAPI";
    public $fields = [
        [
            "DisplayName" => "API URL",
            "Name" => "host",
            "Type" => "text",
            "Default" => "https://ihale.burakaritas.net.tr/api/",
            "ValueKey" =>  "FakeAPI_host"
        ],
        [
            "DisplayName" => "API Kullanıcı Adı",
            "Name" => "username",
            "Type" => "text",
            "ValueKey" =>  "FakeAPI_username"
        ],
        [
            "DisplayName" => "API Parola",
            "Name" => "password",
            "Type" => "password",
            "ValueKey" =>  "FakeAPI_password"
        ],
    ];

    function __construct($params = [])
    {
        $this->request = new \Gleox\Http\Client();
        foreach ($this->fields as $field) {
            if (isset($params[$field["ValueKey"]])){
                $this->{$field["Name"]} = $params[$field["ValueKey"]];
            }
        }
    }

    public function getAuctions($status = false, $type = false)
    {
        try {
            $request = $this->request->get($this->host . "/auctions.php", ["query" => ["status" => $status ?? null, "type" => $type ?? null]]);
            $code = $request->getStatusCode();
            $data = $request->getJson();
            if ($code != 200) {
                throw new Exception($data["message"], $code);
            }
            if ($data["status"] == "failed") {
                throw new \Exception($data["message"], 401);
            }

            return ["status" => "success", "data" => $data["data"]];
        } catch (\Exception $e) {
            return ["status" => "failed", "code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    public function getAuction($id)
    {
        try {
            $request = $this->request->get($this->host . "/get-auctions.php", ["query" => ["id" => $id]]);
            $code = $request->getStatusCode();
            $data = $request->getJson();
            if ($code != 200) {
                throw new Exception($data["message"], $code);
            }
            if ($data["status"] == "failed") {
                throw new \Exception($data["message"], 401);
            }

            return ["status" => "success", "data" => $data["data"]];
        } catch (\Exception $e) {
            return ["status" => "failed", "code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    public function getQuotesByAuctionId($id)
    {
        try {
            $request = $this->request->get($this->host . "/get-quotes", ["query" => ["auctionId" => $id]]);
            $code = $request->getStatusCode();
            $data = $request->getJson();
            if ($code != 200) {
                throw new Exception($data["message"], $code);
            }
            if ($data["status"] == "failed") {
                throw new \Exception($data["message"], 401);
            }

            return ["status" => "success", "data" => $data["data"]];
        } catch (\Exception $e) {
            return ["status" => "failed", "code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    public function getQuote($id)
    {

    }

    public function addQuoteToAuction($id, $amount)
    {
        try {
            $request = $this->request->post($this->host . "/add-quotes.php", ["form_params" => ["auctionId" => $id, "amount" => $amount]]);
            $code = $request->getStatusCode();
            $data = $request->getJson();

            if ($code != 200) {
                throw new Exception(isset($data["message"]) ? $data["message"] : "İşlem sırasında bir hata oluştu.", $code);
            }
            if ($data["status"] == "failed") {
                throw new \Exception($data["message"], 401);
            }

            return ["status" => "success", "data" => $data["data"]];
        } catch (\Exception $e) {
            return ["status" => "failed", "code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }

    public function removeQuote($id)
    {
        // TODO: Implement removeQuote() method.
    }

    public function getFields()
    {
        return $this->fields;
    }
}