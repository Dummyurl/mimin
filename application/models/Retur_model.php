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

      function get_retur_by_id($id){
        $q = $this->db->query("Select * from retur
            where 1 and id_retur = '".$id."' limit 1");
            return $q->row();
      }

}
?>