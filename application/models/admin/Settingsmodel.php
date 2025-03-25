<?php
class Settingsmodel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function update_table($tableId,$table_name,$store_table_name,$secret_code)
    {
        $this->db->set('store_table_name', $store_table_name);
        $this->db->set('secret_code', $secret_code);
        $this->db->where('table_id', $tableId);
        $this->db->update('store_table');
    }

    public function clearStoreStock($store_id){
        $this->db->select('*');
        $this->db->from('store_stock');
        $this->db->where('store_id', $store_id);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $row) {
            $history_data = [
                'store_id'      => $row['store_id'],
                'tr_date'    => $row['tr_date'],
                'order_id'      => $row['order_id'],
                'ttype'    => $row['ttype'],
                'product_id'      => $row['product_id'],
                'is_combo'    => $row['is_combo'],
                'pu_qty'      => $row['pu_qty'],
                'sl_qty'    => $row['sl_qty'], // Timestamp for reference
                'minqty'      => $row['minqty'],
                'remarks'    => $row['remarks'],
                'created_by'      => $row['created_by'],
                'created_date'    => $row['created_date'], // Timestamp for reference
                'modified_by'      => $row['modified_by'],
                'modified_date'    => $row['modified_date']
            ];
            $this->db->insert('store_stock_history', $history_data);
        }
    
        // Now, clear the stock for the given store
        $this->db->where('store_id', $store_id);
        $this->db->delete('store_stock');
    }

    public function ChangeOnlineOrderStatus($status,$store_id){
        $this->db->set('is_order_close', $status);
        $this->db->where('store_id', $store_id);
        $this->db->update('store');
    }
    public function update_kotprintenable($storeid,$is_kot_print_enable){
        $this->db->set('is_kot_print_enabled', $is_kot_print_enable);
        $this->db->where('store_id', $storeid);
        $this->db->update('store');
    }
    public function TableAssign($store_id,$user_id,$selectedTables,$isPickup,$isDelivery){
        // Delete existing records for the given store_id and user_id
        $this->db->where('user_id', $user_id);
        $this->db->where('store_id', $store_id);
        $this->db->delete('store_table_assign');
    
        // Insert new records from the selected table checkboxes 
        if (!empty($selectedTables) && is_array($selectedTables)) {
            $insertData = [];
            foreach ($selectedTables as $table_id) {
                $insertData[] = [
                    'table_id' => $table_id,
                    'user_id' => $user_id,
                    'store_id' => $store_id,
                    'is_pickup' => 0,
                    'is_delivery' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
    
            // Batch insert for better performance
            if (!empty($insertData)) {
                $this->db->insert_batch('store_table_assign', $insertData);
            }
        }

        // Insert pickup and delivery records separately
        $data = array(
            'table_id' => 0,
            'user_id' => $user_id,
            'store_id' => $store_id,
            'is_pickup' => $isPickup,
            'is_delivery' => $isDelivery,
            'date' => date('Y-m-d H:i:s'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('store_table_assign', $data); 
    }
    public function getAssignedTables($store_id, $user_id) {
        $this->db->select('table_id');
        $this->db->from('store_table_assign');
        $this->db->where('store_id', $store_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
    
        $result = $query->result_array();
        
        return !empty($result) ? array_column($result, 'table_id') : []; // Return an empty array if no tables are assigned
    }
    public function getEnableDelivery($store_id,$user_id){
        $this->db->select('is_delivery');
        $this->db->from('store_table_assign');
        $this->db->where('store_id', $store_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('table_id',0);
        $query = $this->db->get();
        $result = $query->row_array();
        return isset($result['is_delivery']) ? $result['is_delivery'] : 0;
    }
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Retrieves the pickup status for a specific store and user.
     *
     * @param int $store_id The ID of the store.
     * @param int $user_id The ID of the user.
     * @return int Returns 1 if pickup is enabled, otherwise 0.
     */

/******  243f03c8-1c42-4db0-9d20-bfdf6a147036  *******/
    public function getEnablePickup($store_id,$user_id){
        $this->db->select('is_pickup');
        $this->db->from('store_table_assign');
        $this->db->where('store_id', $store_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('table_id',0);
        $query = $this->db->get();
        $result = $query->row_array();
        return isset($result['is_pickup']) ? $result['is_pickup'] : 0;
    }
    
    
}

?>