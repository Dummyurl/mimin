<?php
class Sales_model extends CI_Model{
        /* ========== Category========== */
      function __construct()
        {
            parent::__construct();
        }
            function get_sales()
        {
            $return = array();

            $this->db->select("latitude,longitude,user_fullname,waktu");
            $this->db->from("registers");
            $query = $this->db->get();

            if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
         array_push($return, $row);
        }
        }
            return $return;
        }
        }

?>