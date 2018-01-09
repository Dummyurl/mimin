<?php
class Retur_model extends CI_Model{
        /* ========== Category========== */
        public function get_retur()
        {
            //$q = $this->db->query("select k.*,r.user_fullname from kunjungan k left join registers r on k.id_register=r.user_id" );
           $q = $this->db->query("select * from retur ");
            return $q->result();
        }

        function filter_retur($filter=""){

            $q = $this->db->query("select * from retur ");
            return $q->result();
      }

}
?>