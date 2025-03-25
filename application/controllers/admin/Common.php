<?php
	$this->load->model('admin/Commonmodel');
    $this->load->model('admin/Rolemodel');
		if ($this->session->userdata('loginid')!=NULL)
	    {
		 $this->loginID=$this->session->userdata('loginid');	
		 $this->roleID=$this->session->userdata('roleid');
		 //$this->roleID=2;
           
            // Fetch top menus and leftmenus based on the role

            $this->topmenu=$this->Commonmodel->fetchtopmenu();
            $this->leftmenu=$this->Commonmodel->fetcleftmenu();
            $this->notifications=$this->Commonmodel->fetch_notifications($this->loginID);
           
            // Fetch login user's details
            $this->loginuser=$this->Commonmodel->fetchuserDetails($this->roleID,$this->loginID);
            //$this->moduleAccess=$this->Commonmodel->fetchaccessmodules($this->roleID);
            $this->permissions=$this->Rolemodel->fetchaccessmodule($this->roleID);
            $current_time = new DateTime();	
            $punch_in_date=$current_time->format('Y-m-d');
            
        }
        else
        {
            redirect('Login');
        }

        
	

