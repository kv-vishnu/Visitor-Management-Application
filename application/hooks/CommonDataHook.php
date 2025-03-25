<?php
class CommonDataHook {

	
    public function setCommonData() {
 
        $CI =& get_instance();
        $CI->load->model('admin/Commonmodel');
        $CI->load->library('session');
        $data['punchin'] = '';//$CI->Commonmodel->getPunchindata($CI->session->userdata('loginid'));
        $CI =& get_instance();
        $CI->load->vars($data);

    }
}