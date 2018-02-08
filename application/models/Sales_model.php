<?php
class Sales_model extends CI_Model{
        /* ========== Category========== */
      function __construct()
        {
            parent::__construct();
        }
            function data_sales()
        {
                $this->db->select("user_id,user_fullname");
                $this->db->from("registers");
                $query = $this->db->get();
                return $query->result();
        }
         function data_toko()
        {
                $this->db->select("socity_id,socity_name");
                $this->db->from("socity");
                $query = $this->db->get();
                return $query->result();
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
        function get_sales_filter($uid,$tgl)
        {
            $return = array();
            $tgl = $tgl;
            $this->db->select("l.latitude,l.longitude,r.user_fullname,l.waktu");
            $this->db->from("registers r");
            $this->db->join("lokasi l","l.uid= r.user_id","left");
            $this->db->where("r.user_id",$uid);
            $this->db->where("DATE_FORMAT(l.waktu,'%Y-%m-%d')",$tgl);
            $query = $this->db->get();

            if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
         array_push($return, $row);
        }
        }
            return $return;
        }

        public function tgl_sql($date){
            $exp = explode('-',$date);
            if(count($exp) == 3) {
                $date = $exp[2].'-'.$exp[1].'-'.$exp[0];
            }
            return $date;
        }
        }



?>