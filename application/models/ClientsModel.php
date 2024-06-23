<?php
/**
 * @property CI_DB_query_builder $db
 *
 * @property M_Dashboard $dm
 */

class ClientsModel extends CI_Model
{
	public function insert($data){
		try {
			$this->db->insert("clients", $data);

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
			$this->db->where("id", $id)->update("clients", $data);

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
			$this->db->where("id", $id)->delete("clients");

			if(strlen($this->db->error()["message"]) > 0){
				throw new Exception($this->db->error()["message"]);
			}

			return ["status" => "success"];
		}catch (Exception $e){
			return ["status" => "failed", "message" => "İşlem yapılırken bir hata oluştu. Hata: {$e->getMessage()}"];
		}
	}

	public function get($id){
		return $this->db->where("id", $id)->get("clients")->row();
	}

	public function getAll(){
		return $this->db->get("clients")->result();
	}

	public function getByEmail($email){
		return $this->db->where("email", $email)->get("clients")->row();
	}

	public function getByUsername($username){
		return $this->db->where("username", $username)->get("clients")->row();
	}


	public function getByHash($hash){
		return $this->db->where("accesstoken", $hash)->get("clients")->row();
	}
}
