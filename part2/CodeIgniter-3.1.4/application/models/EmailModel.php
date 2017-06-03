<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class EmailModel extends CI_Model{
    function __construct() {
        $this->userTbl = 'USERS';
    }

    function getRows(){
        $this->db->select('EMAIL');
        $this->db->from($this->userTbl);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

}