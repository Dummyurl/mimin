<?php
class Kunjungan_model extends CI_Model{
        /* ========== Category========== */
        public function get_kunjungan()
        {
            //$q = $this->db->query("select k.*,r.user_fullname from kunjungan k left join registers r on k.id_register=r.user_id" );
           $q = $this->db->query("select * from kunjungan ");
            return $q->result();
        }

        function filter_kunjungan($filter=""){

            $q = $this->db->query("select * from kunjungan ");
            return $q->result();
      }

}
?>