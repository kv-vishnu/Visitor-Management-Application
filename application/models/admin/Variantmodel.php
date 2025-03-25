<?php
class Variantmodel extends CI_Model {
    public function listvariants()
	{
		$this->db->select('*');
		$this->db->from('variants');
		
		//$this->db->where('is_active', 1);
		$this->db->order_by("variant_id", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function liststore_variants(){
		$this->db->select('*');
		$this->db->from('variants');
		//$this->db->where('is_active', 1);
		$this->db->order_by("variant_id", "desc");
		$query = $this->db->get();
		return $row = $query->result_array();
		//print_r($row);exit;
	}
	public function ownervariants($store_id)

    {
        $this->db->select('*');
        $this->db->from('variants');
        $this->db->where('store_id', $store_id);
        $this->db->or_where('store_id', 0);
        // $this->db->where('store_id', $store_id);
        // $this->db->where('store_id', $store_id);
        $this->db->where('is_active', 1);
        $this->db->order_by("variant_id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function adminvariant($store_id=0)

    {
        $this->db->select('');
        $this->db->from('variants');
     $this->db->where('store_id', $store_id);
        //$this->db->where('is_active', 1);
        $this->db->order_by("variant_id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
	public function insert($data){
	    $this->db->insert('variants',$data);
	}
	public function delete($id){
		$this->db->where('variant_id', $id);
        return $this->db->delete('variants');
	}
	public function get($id){
	    $this->db->select('*');
		$this->db->from('variants');
		$this->db->where('variant_id',$id );
		$query = $this->db->get();
		return $query->result_array();
	}
	public function update($id,$data){
	    $this->db->where('variant_id', $id);
        $this->db->update('variants', $data);
        return true;
	}
	public function check_countryname_exists($countryname)
	{
    	$this->db->where('variant_name', $countryname);
    	$query = $this->db->get('variants'); // Assuming 'users' is your table name
		if ($query->num_rows() > 0) {
        	return TRUE;  // Username exists
    	} else {
        	return FALSE;  // Username does not exist
    	}
	}
}
?>