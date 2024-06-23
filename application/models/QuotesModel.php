<?php
/**
 * @property CI_DB_query_builder $db
 *
 * @property M_Dashboard $dm
 */

class QuotesModel extends CI_Model
{
	public function insert($data){
		try {
			$this->db->insert("quotes", $data);

			if(strlen($this->db->error()["message"]) > 0){
				throw new Exception($this->db->error()["message"]);
			}

			return ["status" => "success", "insertId" => $this->db->insert_id()];
		}catch (Exception $e){
			return ["status" => "failed", "message" => "İşlem yapılırken bir hata oluştu. Hata: {$e->getMessage()}"];
		}
	}

	public function update($id, $data){
		try {
			$this->db->where("id", $id)->update("quotes", $data);

			if(strlen($this->db->error()["message"]) > 0){
				throw new Exception($this->db->error()["message"]);
			}

			return ["status" => "success"];
		}catch (Exception $e){
			return ["status" => "failed", "message" => "İşlem yapılırken bir hata oluştu. Hata: {$e->getMessage()}"];
		}
	}

	public function delete($id){
		try {
			$this->db->where("id", $id)->delete("quotes");

			if(strlen($this->db->error()["message"]) > 0){
				throw new Exception($this->db->error()["message"]);
			}

			return ["status" => "success"];
		}catch (Exception $e){
			return ["status" => "failed", "message" => "İşlem yapılırken bir hata oluştu. Hata: {$e->getMessage()}"];
		}
	}

	public function get($id){
		return $this->db->where("id", $id)->get("quotes")->row();
	}

	public function getAll(){
		return $this->db->get("quotes")->result();
	}

	public function getByClientId($id, $paid = false){
        if($paid !== false) $this->db->where("paid", $paid);
		return $this->db->where("clientid", $id)->get("quotes")->result();
	}


    public function getByAuctionAndClientId($auctionId, $clientId, $provider){
        return $this->db->where("clientid", $clientId)->where("auctionid", $auctionId)->where("provider", $provider)->get("quotes")->row();
    }

}
