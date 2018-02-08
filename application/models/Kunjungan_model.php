<?php
class Kunjungan_model extends CI_Model{
        /* ========== Category========== */
        function data_sales()
        {
                $this->db->select("id_target_kunjungan,id_sales");
                $this->db->from("target_kunjungan");
                $query = $this->db->get();
                return $query->result();
        }
        public function get_kunjungan()
        {
            //$q = $this->db->query("select k.*,r.user_fullname from kunjungan k left join registers r on k.id_register=r.user_id" );
           $q = $this->db->query("select * from kunjungan ");
            return $q->result();
        }

         public function target_kunjungan()
        {
            $q = $this->db->query("select k.*,r.user_fullname,s.socity_name from target_kunjungan k left join registers r on k.id_sales=r.user_id join socity s on k.id_toko=s.socity_id" );
            //$q = $this->db->query("select * from target_kunjungan ");
            return $q->result();
        }

        public function target_kunjungan_byid($id)
        {
            $q = $this->db->query("select k.*,r.user_fullname,s.socity_name from target_kunjungan k left join registers r on k.id_sales=r.user_id join socity s on k.id_toko=s.socity_id
            where 1 and id_target_kunjungan = '".$id."'");
            return $q->row();
        }
        function filter_kunjungan($filter=""){
            $sql = "select * from kunjungan where 1 ".$filter."ORDER BY id_kunjungan ASC,DATE_FORMAT(waktu,'%Y-%m-%d')";
            $q = $this->db->query($sql);
            return $q->result();
         }

         public function task_kunjungan($filter="")
        {
            //$q = $this->db->query("select k.*,r.user_fullname from kunjungan k left join registers r on k.id_register=r.user_id" );
           $q = $this->db->query("select * from target_kunjungan where 1 ".$filter."ORDER BY id_target_kunjungan ASC");
            return $q->result();
        }

        public function hasil_kunjungan($filter="")
        {
            //$q = $this->db->query("select k.*,r.user_fullname from kunjungan k left join registers r on k.id_register=r.user_id" );
           $q = $this->db->query("select * from hasil_kunjungan where 1 ".$filter."ORDER BY id_hasil_kunjungan ASC");
            return $q->result();
        }
        public function get_kunjungan_filter($user_id,$tgl)
        {
            $return = array();
            $tgl = $tgl;
            $this->db->select("k.*,r.user_fullname,s.socity_name");
            $this->db->from("target_kunjungan k");
            $this->db->join("registers r","r.user_id= k.id_sales","left");
            $this->db->join("socity s","s.socity_id= k.id_toko","left");
            $this->db->where("k.id_sales",$user_id);
            $this->db->where("DATE_FORMAT(k.tanggal,'%Y-%m-%d')",$tgl);
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