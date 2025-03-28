<?php
class Loginmodel extends CI_Model {
    public function checkLogin()
    {
        //print_r($_POST);
        $password = $_POST['login']['password'];
        $encrypt_password = md5($password);
        //echo $encrypt_password;exit;
        $query="SELECT * FROM users WHERE (userName='".$_POST['username']."' OR userEmail='".$_POST['username']."') AND is_active='1'";
       //exit;
        $query = $this->db->query($query);
        //echo $this->db->last_query();exit;
        $rows = $this->db->affected_rows();
        $row=$query->row();
        if($encrypt_password == $row->userPassword)
	    {
			$this->db->insert('user_login_logout', [
				'user_id' => $row->userid,
				'company_id' => $row->company_id,
				'date' => date('Y-m-d'),
				'login_time' => date('Y-m-d H:i:s'),
				'logout_time' => '',
				'created_at' => date('Y-m-d H:i:s')
			]);
			return $query->result_array();
		}
		else
		{
			return;
		}
    }

    public function emailValidate($email)
    {
        $query=$this->db->query("SELECT * from users where userEmail='".$email."'");
        if($query->num_rows() == 1)
        {
          return $query->row();
        }
        else
        {
          return false;
        }
    }

    public function updatePassword($data,$user_id)
    {
        $this->db->where('userid',$user_id);
        $this->db->update('users',$data);
    }

     // Password auto generation
	public function passwordGenerate()
	{
		// Random password generation
		$len=8;
		if($len < 8)
		$len = 8;

		//define character libraries - remove ambiguous characters like iIl|1 0oO
		$sets = array();
		$sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		$sets[] = '0123456789';
		//$sets[]  = '@#$%&?';
		$password = '';    
		//append a character from each set - gets first 4 characters
		foreach ($sets as $set) {
		$password .= $set[array_rand(str_split($set))];
		}
		//use all characters to fill up to $len
		while(strlen($password) < $len) {
			//get a random set
			$randomSet = $sets[array_rand($sets)];				
			//add a random char from the random set
			$password .= $randomSet[array_rand(str_split($randomSet))]; 
		}			
		//shuffle the password string before returning!
		return  $pswd= str_shuffle($password);
	}
	public function get_notification_count($login_user){
		$this->db->select('id');
		$this->db->from('notification');
		$this->db->where('reciever',$login_user );
		$this->db->where('status',0 );
		$query = $this->db->get();
		return $query->num_rows();
	}
}
?>