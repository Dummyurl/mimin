<?php
class Marker_model extends CI_Model{
        /* ========== Category========== */
      function __construct()
        {
            parent::__construct();
        }
            function get_coordinates()
        {
            $return = array();

            $this->db->select("lat,lng,name");
            $this->db->from("marker");
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