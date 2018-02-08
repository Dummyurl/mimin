<?php
class Common_model extends CI_Model{
    function data_insert($table,$insert_array){
        $this->db->insert($table,$insert_array);
        return $this->db->insert_id();
    }

    function data_update($table,$set_array,$condition){
        $this->db->update($table,$set_array,$condition);
        return $this->db->affected_rows();
    }
    function data_remove($table,$condition){
        $this->db->delete($table,$condition);
    }
    function data_sales($tanggal,$uid){
            $query = $this->db->query('SELECT s.*,tk.* FROM `target_kunjungan` tk LEFT JOIN socity s ON s.socity_id = tk.id_toko WHERE DATE_FORMAT(tk.tanggal,"%Y-%m-%d") = DATE_FORMAT("'.$tanggal.'","%Y-%m-%d") AND tk.id_sales = "'.$uid.'"');
            return $query->result();
    }
    function jarakhitung($currentLat, $currentLong, $destLat, $destLon){
        $derajatlokasi = $this->hitungJarak($currentLat,$currentLong, $destLat, $destLon);
        $jarak = $derajatlokasi*1.609344*1000;
        return $jarak;
    }
    function hitungJarak($lokasi1_lat, $lokasi1_long, $lokasi2_lat, $lokasi2_long, $unit = 'km', $desimal = 2) {
        // Menghitung jarak dalam derajat
        $derajat = rad2deg(acos((sin(deg2rad($lokasi1_lat))*sin(deg2rad($lokasi2_lat))) + (cos(deg2rad($lokasi1_lat))*cos(deg2rad($lokasi2_lat))*cos(deg2rad($lokasi1_long-$lokasi2_long)))));

        // Mengkonversi derajat kedalam unit yang dipilih (kilometer, mil atau mil laut)
        switch($unit) {
        case 'km':
        $jarak = $derajat * 111.13384; // 1 derajat = 111.13384 km, berdasarkan diameter rata-rata bumi (12,735 km)
        break;
        case 'mi':
        $jarak = $derajat * 69.05482; // 1 derajat = 69.05482 miles(mil), berdasarkan diameter rata-rata bumi (7,913.1 miles)
        break;
        case 'nmi':
        $jarak =  $derajat * 59.97662; // 1 derajat = 59.97662 nautic miles(mil laut), berdasarkan diameter rata-rata bumi (6,876.3 nautical miles)
        }
        return round($jarak, $desimal);
    }


}
?>