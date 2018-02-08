<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper');
                $this->load->helper('sms_helper');
    }
    function signout(){
        $this->session->sess_destroy();
        redirect("admin");
    }
	public function index()
	{
		if(_is_user_login($this)){
            redirect(_get_user_redirect($this));
        }else{

            $data = array("error"=>"");
            if(isset($_POST))
            {

                $this->load->library('form_validation');

                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    }
        		}else
                {

                    $q = $this->db->query("Select * from `users` where (`user_email`='".$this->input->post("email")."') and user_password='".md5($this->input->post("password"))."'  Limit 1");

                   // print_r($q) ;
                    if ($q->num_rows() > 0)
                    {
                        $row = $q->row();
                        if($row->user_status == "0")
                        {
                            $data["error"] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Your account currently inactive.</div>';
                        }
                        else
                        {
                            $newdata = array(
                                                   'user_name'  => $row->user_fullname,
                                                   'user_email'     => $row->user_email,
                                                   'logged_in' => TRUE,
                                                   'user_id'=>$row->user_id,
                                                   'user_type_id'=>$row->user_type_id
                                                  );
                            $this->session->set_userdata($newdata);
                            redirect(_get_user_redirect($this));

                        }
                    }
                    else
                    {
                        $data["error"] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Invalid User and password. </div>';
                    }


                }
            }
            $data["active"] = "login";

            $this->load->view("admin/login",$data);
        }
	}
    public function dashboard(){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("product_model");
            $date = date("Y-m-d");
            $data["today_orders"] = $this->product_model->get_sale_orders(" and sale.on_date = '".$date."' ");
             $nexday = date('Y-m-d', strtotime(' +1 day'));
            $data["nextday_orders"] = $this->product_model->get_sale_orders(" and sale.on_date = '".$nexday."' ");
            $this->load->view("admin/dashboard",$data);
        }
        else
        {
            redirect('admin');
        }
    }

    public function monitoring(){
         if(_is_user_login($this))
         {
                $this->load->library('googlemaps');
                $this->load->model('Marker_model', '', TRUE);
                $this->load->model('Sales_model', '', TRUE);
                $config['center'] = '-3.318847, 114.593266';
                $config['zoom'] = '13';
                $this->googlemaps->initialize($config);

                $marker = $this->Marker_model->get_coordinates();

                foreach ($marker as $coordinate) {
                $marker = array();
                $marker['position'] = $coordinate->latitude.','.$coordinate->longitude;
                $marker['infowindow_content'] = $coordinate->socity_name;
                $marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=T|9999FF|000000';
                $this->googlemaps->add_marker($marker);
                }

                $registers = $this->Sales_model->get_sales();
                $data['id_sales'] = "";
                $data['datepicker'] = "";
                if (!empty($this->input->post('datepicker')) && !empty($this->input->post('id_sales')) )
                {
                    $tgl = $this->Sales_model->tgl_sql($this->input->post('datepicker'));
                    $registers = $this->Sales_model->get_sales_filter($this->input->post('id_sales'),$tgl);
                    $data['id_sales'] = $this->input->post('id_sales');
                    $data['datepicker'] = $this->input->post('datepicker');
                }

                foreach ($registers as $coordinate) {
                $marker = array();
                $marker['position'] = $coordinate->latitude.','.$coordinate->longitude;
                $marker['infowindow_content'] = $coordinate->user_fullname. ' | ' .$coordinate->waktu;
                $this->googlemaps->add_marker($marker);
                }


                $data['namas'] = $this->Sales_model->data_sales();

                $data['map'] = $this->googlemaps->create_map();
                $this->load->view("admin/monitoring", $data);

        }

          else
        {
            redirect('admin');
        }
    }



    public function laporan(){
         if(_is_user_login($this)){
            $this->load->model("product_model");
            $data['tahun'] = "";
            $data['bulan'] = "";
            $data['users'] = $this->product_model->get_all_users(null,null);
            $data['thn'] = $this->product_model->data_tahun();
            if (!empty($this->input->post('tahun'))){
                $data['tahun'] = $this->input->post('tahun');
                $data['users'] = $this->product_model->get_all_users($data['tahun'],null);
                if(!empty($this->input->post('bulan')) ){
                    $data['bulan'] = $this->input->post('bulan');
                    $data['users'] = $this->product_model->get_all_users($data['tahun'],$data['bulan']);
                }
            }
            $this->load->view("admin/laporan/laporan",$data);
        }
         else
        {
            redirect('admin');
        }
    }

     public function laporan_kunjungan(){
         if(_is_user_login($this)){

            $this->load->model("product_model");
            $data['tahun'] = "";
            $data['bulan'] = "";
            $data['users'] = $this->product_model->get_all_kunjungan(null,null);
            $data['thn'] = $this->product_model->data_tahun();
            if (!empty($this->input->post('tahun'))){
                $data['tahun'] = $this->input->post('tahun');
                $data['users'] = $this->product_model->get_all_kunjungan($data['tahun'],null);
                if(!empty($this->input->post('bulan')) ){
                    $data['bulan'] = $this->input->post('bulan');
                    $data['users'] = $this->product_model->get_all_kunjungan($data['tahun'],$data['bulan']);
                }
            }
            $this->load->view("admin/laporan/laporan_kunjungan",$data);
        }
         else
        {
            redirect('admin');
        }
    }




    public function orders(){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("product_model");
            $fromdate = date("Y-m-d");
            $todate = date("Y-m-d");
            $data['date_range_lable'] = $this->input->post('date_range_lable');

             $filter = "";
            if($this->input->post("date_range")!=""){
				$filter = $this->input->post("date_range");
			    $dates = explode(",",$filter);
                $fromdate =  date("Y-m-d", strtotime($dates[0]));
                $todate =  date("Y-m-d", strtotime($dates[1]));
                $filter = " and sale.on_date >= '".$fromdate."' and sale.on_date <= '".$todate."' ";
            }
            $data["today_orders"] = $this->product_model->get_sale_orders($filter);

            $this->load->view("admin/orders/orderslist",$data);
        }
         else
        {
            redirect('admin');
        }
    }
    public function confirm_order($order_id){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $order = $this->product_model->get_sale_order_by_id($order_id);
            if(!empty($order)){
                $this->db->query("update sale set status = 1 where sale_id = '".$order_id."'");
                 $q = $this->db->query("Select * from registers where user_id = '".$order->user_id."'");
                $user = $q->row();

                                $message["title"] = "Confirmed  Order";
                                $message["message"] = "Your order Number '".$order->sale_id."' confirmed successfully";
                                $message["image"] = "";
                                $message["created_at"] = date("Y-m-d h:i:s");
                                $message["obj"] = "";

                            $this->load->helper('gcm_helper');
                            $gcm = new GCM();
                            if($user->user_gcm_code != ""){
                            $result = $gcm->send_notification(array($user->user_gcm_code),$message ,"android");
                            }
                             if($user->user_ios_token != ""){
                            $result = $gcm->send_notification(array($user->user_ios_token),$message ,"ios");
                             }
                $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Sukses!</strong> Pesanan dikonfirmasi. </div>');
            }
            redirect("admin/orders");
        }
         else
        {
            redirect('admin');
        }
    }

    public function delivered_order($order_id){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $order = $this->product_model->get_sale_order_by_id($order_id);
            if(!empty($order)){
                $this->db->query("update sale set status = 2 where sale_id = '".$order_id."'");

                 $q = $this->db->query("Select * from registers where user_id = '".$order->user_id."'");
                $user = $q->row();

                               $message["title"] = "Delivered  Order";
                                $message["message"] = "Your order Number '".$order->sale_id."' Delivered successfully. Thank you for being with us";
                                $message["image"] = "";
                                $message["created_at"] = date("Y-m-d h:i:s");
                                $message["obj"] = "";

                            $this->load->helper('gcm_helper');
                            $gcm = new GCM();
                            if($user->user_gcm_code != ""){
                            $result = $gcm->send_notification(array($user->user_gcm_code),$message ,"android");
                            }
                             if($user->user_ios_token != ""){
                            $result = $gcm->send_notification(array($user->user_ios_token),$message ,"ios");
                             }

                $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Sukses!</strong> Pesanan terkirim. </div>');
            }
            redirect("admin/orders");
        }
         else
        {
            redirect('admin');
        }
    }
    public function cancle_order($order_id){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $order = $this->product_model->get_sale_order_by_id($order_id);
            if(!empty($order)){
                $this->db->query("update sale set status = 3 where sale_id = '".$order_id."'");

                 $q = $this->db->query("Select * from users where user_id = '".$order->user_id."'");
                 $user = $q->row();
                                $message["title"] = "Cancel  Order";
                                $message["message"] = "Your order Number '".$order->sale_id."' cancel successfully";
                                $message["image"] = "";
                                $message["created_at"] = date("Y-m-d h:i:s");
                                $message["obj"] = "";

                            $this->load->helper('gcm_helper');
                            $gcm = new GCM();
                           if($user->user_gcm_code != ""){
                            $result = $gcm->send_notification(array($user->user_gcm_code),$message ,"android");
                            }
                             if($user->user_ios_token != ""){
                            $result = $gcm->send_notification(array($user->user_ios_token),$message ,"ios");
                             }

                $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Sukses!</strong> Pesanan dibatalkan. </div>');
            }
            redirect("admin/orders");
        }
         else
        {
            redirect('admin');
        }
    }

    public function delete_order($order_id){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $order = $this->product_model->get_sale_order_by_id($order_id);
            if(!empty($order)){
                $this->db->query("delete from sale where sale_id = '".$order_id."'");
                $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Sukses!</strong> Pesanan dihapus. </div>');
            }
            redirect("admin/orders");
        }
         else
        {
            redirect('admin');
        }
    }
    public function orderdetails($order_id){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("product_model");
            $data["order"] = $this->product_model->get_sale_order_by_id($order_id);
            $data["order_items"] = $this->product_model->get_sale_order_items($order_id);
            $this->load->view("admin/orders/orderdetails",$data);
        }
         else
        {
            redirect('admin');
        }
    }
    public function change_status(){
        $table = $this->input->post("table");
        $id = $this->input->post("id");
        $on_off = $this->input->post("on_off");
        $id_field = $this->input->post("id_field");
        $status = $this->input->post("status");

        $this->db->update($table,array("$status"=>$on_off),array("$id_field"=>$id));
    }
/*=========USER MANAGEMENT==============*/
    public function user_types(){
        $data = array();
        if(isset($_POST))
            {

                $this->load->library('form_validation');

                $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    }
        		}else
                {
                        $user_type = $this->input->post("user_type");

                            $this->load->model("common_model");
                            $this->common_model->data_insert("user_types",array("user_type_title"=>$user_type));
                            $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                </div>') ;
                             redirect("admin/user_types/");

                }
            }

        $this->load->model("users_model");
        $data["user_types"] = $this->users_model->get_user_type();
        $this->load->view("admin/user_types",$data);
    }
    function user_type_delete($type_id){
        $data = array();
            $this->load->model("users_model");
            $usertype  = $this->users_model->get_user_type_id($type_id);
           if($usertype){
                $this->db->query("Delete from user_types where user_type_id = '".$usertype->user_type_id."'");
                redirect("admin/user_types");
           }
    }
    public function user_access($user_type_id){
        if($_POST){
           //print_r($_POST);
                $this->load->library('form_validation');

                $this->form_validation->set_rules('user_type_id', 'User Type', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        		      	$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                 }
      		    }else{
      		        //$user_type_id = $this->input->post("user_type_id");
      		        $this->load->model("common_model");
                    $this->common_model->data_remove("user_type_access",array("user_type_id"=>$user_type_id));

                    $sql = "Insert into user_type_access(user_type_id,class,method,access) values";
                    $sql_insert = array();
                    foreach(array_keys($_POST["permission"]) as $controller){
                        foreach($_POST["permission"][$controller] as $key=>$methods){
                            if($key=="all"){
                                $key = "*";
                            }
                            $sql_insert[] = "($user_type_id,'$controller','$key',1)";
                        }
                    }
                    $sql .= implode(',',$sql_insert)." ON DUPLICATE KEY UPDATE access=1";
                    $this->db->query($sql);
      		    }
        }
        $data['user_type_id'] = $user_type_id;
        $data["controllers"] = $this->config->item("controllers");
        $this->load->model("users_model");
        $data["user_access"] = $this->users_model->get_user_type_access($user_type_id);

        //$data["user_types"] = $this->users_model->get_user_type();
        $this->load->view("admin/user_access",$data);
    }
/*============USRE MANAGEMENT===============*/

/*============RETUR===============*/
public function listretur()
    {
        if(_is_user_login($this)){

            $data = array();
            $this->load->model("Retur_model");
            $fromdate = date("Y-m-d");
            $todate = date("Y-m-d");
            $data['date_range_lable'] = $this->input->post('date_range_lable');

             $filter = "";
            if($this->input->post("date_range")!=""){
				$filter = $this->input->post("date_range");
			    $dates = explode(",",$filter);
                $fromdate =  date("Y-m-d", strtotime($dates[0]));
                $todate =  date("Y-m-d", strtotime($dates[1]));
                $filter = " and sale.on_date >= '".$fromdate."' and sale.on_date <= '".$todate."' ";
            }
            $data["today_retur"] = $this->Retur_model->filter_retur($filter);
           //
           $this->load->view('admin/retur/retur',$data);

        }
        else
        {
            redirect('admin');
        }
    }

    public function addretur()
    {
        if(_is_user_login($this)){
         if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'trim|required');
                $this->form_validation->set_rules('nama_sales', 'Nama Sales', 'trim|required');
                $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'trim|required');
                $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
                $this->form_validation->set_rules('total_harga', 'Total Harga', 'trim|required');
                $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "nama_produk"=>$this->input->post("nama_produk"),
                    "nama_sales"=>$this->input->post("nama_sales"),
                    "nama_toko"=>$this->input->post("nama_toko"),
                    "jumlah"=>$this->input->post("jumlah"),
                    "total_harga"=>$this->input->post("total_harga"),
                    "keterangan"=>$this->input->post("keterangan")

                    );
                    $this->common_model->data_insert("retur",$array);

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect('admin/listretur');
              	}
            }
	            $this->load->model("retur_model");
                $data["retur"]  = $this->retur_model->get_retur();
                $this->load->view("admin/retur/addretur",$data);
        }
        else
        {
            redirect('admin');
        }
    }
    public function editretur($id)
    {
    if(_is_user_login($this)){
         if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'trim|required');
                $this->form_validation->set_rules('nama_sales', 'Nama Sales', 'trim|required');
                $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'trim|required');
                $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
                $this->form_validation->set_rules('total_harga', 'Total Harga', 'trim|required');
                $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "nama_produk"=>$this->input->post("nama_produk"),
                    "nama_sales"=>$this->input->post("nama_sales"),
                    "nama_toko"=>$this->input->post("nama_toko"),
                    "jumlah"=>$this->input->post("jumlah"),
                    "total_harga"=>$this->input->post("total_harga"),
                    "keterangan"=>$this->input->post("keterangan")

                    );
                    $this->common_model->data_update("retur",$array,array("id_retur"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/listretur");
                }
                    $this->load->model("retur_model");
                    $data["retur"] = $this->retur_model->get_retur_by_id($id);
                    $this->load->view("admin/retur/editretur",$data);

            }
        }
         else
        {
            redirect('admin');
        }

    }

    public function deleteretur($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("retur",array("id_retur"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Barang anda berhasil dihapus...
                                    </div>');
            redirect('admin/listretur');
        }
        else
        {
            redirect('admin');
        }
    }
/*============END RETUR===============*/

/*============KUNJUNGAN===============*/

    public function listkunjungan()
    {
        if(_is_user_login($this)){

            $data = array();
            $this->load->model("Kunjungan_model");
            $fromdate = date("Y-m-d");
            $todate = date("Y-m-d");
            $data['date_range_lable'] = $this->input->post('date_range_lable');

             $filter = "";
            if($this->input->post("date_range")!=""){
				$filter = $this->input->post("date_range");
			    $dates = explode(",",$filter);
                $fromdate =  date("Y-m-d", strtotime($dates[0]));
                $todate =  date("Y-m-d", strtotime($dates[1]));
                $filter = " and kunjungan.waktu >= '".$fromdate."' and kunjungan.waktu <= '".$todate."' ";
            }
            $data["today_kunjungan"] = $this->Kunjungan_model->filter_kunjungan($filter);
           //
           $this->load->view('admin/kunjungan/listkun',$data);

        }
        else
        {
            redirect('admin');
        }
    }

    public function taskkunjungan()
    {
        if(_is_user_login($this)){

            $data = array();
            $this->load->model("Kunjungan_model");
            $this->load->model('Sales_model', '', TRUE);
                $data['id_sales'] = "";
                $data['datepicker'] = "";
                $data["today_kunjungan"] = $this->Kunjungan_model->target_kunjungan(null,null);
                if (!empty($this->input->post('datepicker')) && !empty($this->input->post('id_sales')) )
                {
                    $tgl = $this->Kunjungan_model->tgl_sql($this->input->post('datepicker'));
                    $data['today_kunjungan'] = $this->Kunjungan_model->get_kunjungan_filter($this->input->post('id_sales'),$tgl);
                    $data['id_sales'] = $this->input->post('id_sales');
                    $data['datepicker'] = $this->input->post('datepicker');
                }
           $data['namas'] = $this->Sales_model->data_sales();
           $this->load->view('admin/kunjungan/taskkun',$data);

        }
        else
        {
            redirect('admin');
        }
    }

    public function add_target()
    {
        if(_is_user_login($this)){
         if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('id_sales', 'Nama Sales', 'trim|required');
                $this->form_validation->set_rules('id_toko', 'Toko', 'trim|required');
                $this->form_validation->set_rules('jam_akhir', 'Batas Jam', 'trim|required');
                $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                     "id_sales"=>$this->input->post("id_sales"),
                    "id_toko"=>$this->input->post("id_toko"),
                    "jam_akhir"=>$this->input->post("jam_akhir"),
                    "tanggal"=>$this->input->post("tanggal"),

                    );
                    $this->common_model->data_insert("target_kunjungan",$array);

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect('admin/taskkunjungan');
              	}
            }

                $this->load->model("kunjungan_model");
                $this->load->model("Sales_model");
                $data["target"] = $this->kunjungan_model->target_kunjungan();
                $data['namas'] = $this->Sales_model->data_sales();
                $data['tokos'] = $this->Sales_model->data_toko();
                $this->load->view("admin/kunjungan/addtarget",$data);
        }
        else
        {
            redirect('admin');
        }
    }

    public function edit_target($id){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('id_sales', 'Nama Sales', 'trim|required');
                $this->form_validation->set_rules('id_toko', 'Toko', 'trim|required');
                $this->form_validation->set_rules('jam_akhir', 'Batas Jam', 'trim|required');
                $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "id_sales"=>$this->input->post("id_sales"),
                    "id_toko"=>$this->input->post("id_toko"),
                    "jam_akhir"=>$this->input->post("jam_akhir"),
                    "tanggal"=>$this->input->post("tanggal"),

                    );
                    $this->common_model->data_update("target_kunjungan",$array,array("id_target_kunjungan"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/taskkunjungan");
                }

                $this->load->model("kunjungan_model");
                $this->load->model("Sales_model");
                $data["target"]  = $this->kunjungan_model->target_kunjungan_byid($id);
                $data['namas'] = $this->Sales_model->data_sales();
                $data['tokos'] = $this->Sales_model->data_toko();
                $this->load->view("admin/kunjungan/edittarget",$data);

            }
        }
         else
        {
            redirect('admin');
        }

    }




    public function hasilkunjungan()
    {
        if(_is_user_login($this)){

            $data = array();
            $this->load->model("Kunjungan_model");
            $fromdate = date("Y-m-d");
            $todate = date("Y-m-d");
            $data['date_range_lable'] = $this->input->post('date_range_lable');

             $filter = "";
            if($this->input->post("date_range")!=""){
				$filter = $this->input->post("date_range");
			    $dates = explode(",",$filter);
                $fromdate =  date("Y-m-d", strtotime($dates[0]));
                $todate =  date("Y-m-d", strtotime($dates[1]));
                $filter = " and hasil_kunjungan.waktu >= '".$fromdate."' and hasil_kunjungan.waktu <= '".$todate."' ";
            }
            $data["today_kunjungan"] = $this->Kunjungan_model->hasil_kunjungan($filter);
           //
           $this->load->view('admin/kunjungan/hasilkun',$data);

        }
        else
        {
            redirect('admin');
        }
    }

    public function deletekun($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("kunjungan",array("id_kunjungan"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Barang anda berhasil dihapus...
                                    </div>');
            redirect('admin/listkunjungan');
        }
        else
        {
            redirect('admin');
        }
    }

    public function deletetaskkun($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("target_kunjungan",array("id_target_kunjungan"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Barang anda berhasil dihapus...
                                    </div>');
            redirect('admin/taskkunjungan');
        }
        else
        {
            redirect('admin');
        }
    }

    public function deletehasilkun($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("hasil_kunjungan",array("id_hasil_kunjungan"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Barang anda berhasil dihapus...
                                    </div>');
            redirect('admin/hasilkunjungan');
        }
        else
        {
            redirect('admin');
        }
    }

/*============end of kunjungan===============*/

/* ========== Categories =========== */
    public function addcategories()
	{
	   if(_is_user_login($this)){

            $data["error"] = "";
            $data["active"] = "addcat";
            if(isset($_REQUEST["addcatg"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('cat_title', 'Categories Title', 'trim|required');
                $this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
                    }
        		}
        		else
        		{$this->load->model("category_model");

                    $this->category_model->add_category();
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect('admin/addcategories');
               	}
            }
	   	$this->load->view('admin/categories/addcat',$data);
        }
        else
        {
            redirect('admin');
        }
	}

    public function editcategory($id)
	{
	   if(_is_user_login($this))
       {
            $q = $this->db->query("select * from `categories` WHERE id=".$id);
            $data["getcat"] = $q->row();

	        $data["error"] = "";
            $data["active"] = "listcat";
            if(isset($_REQUEST["savecat"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('cat_title', 'Categories Title', 'trim|required');
                $this->form_validation->set_rules('cat_id', 'Categories Id', 'trim|required');
                $this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
                   }
        		}
        		else
        		{
                    $this->load->model("category_model");
                    $this->category_model->edit_category();
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect('admin/listcategories');
               	}
            }
	   	   $this->load->view('admin/categories/editcat',$data);
        }
        else
        {
            redirect('admin');
        }
	}

    public function listcategories()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "listcat";
           $this->load->model("category_model");
           $data["allcat"] = $this->category_model->get_categories();
           $this->load->view('admin/categories/listcat',$data);
        }
        else
        {
            redirect('admin');
        }
    }

    public function deletecat($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("categories",array("id"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Berhasil dihapus...
                                    </div>');
            redirect('admin/listcategories');
        }
        else
        {
            redirect('admin');
        }
    }


/* ========== End Categories ========== */
/* ========== Products ==========*/
function products(){
        $this->load->model("product_model");
        $data["products"]  = $this->product_model->get_products();
        $this->load->view("admin/product/list",$data);
}



function edit_products($prod_id){
	   if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('prod_title', 'Categories Title', 'trim|required');
                $this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		   if($this->form_validation->error_string()!=""){
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                   }
        		}
        		else
        		{
                    $this->load->model("common_model");
                    $array = array(
                     "product_name"=>$this->input->post("prod_title"),
                    "category_id"=>$this->input->post("parent"),
                     "product_description"=>$this->input->post("product_description"),
                    "in_stock"=>$this->input->post("prod_status"),
                    "price"=>$this->input->post("price"),
                    "unit_value"=>$this->input->post("qty"),
                    "unit"=>$this->input->post("unit")

                    );
                    if($_FILES["prod_img"]["size"] > 0){
                        $config['upload_path']          = './uploads/products/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);

                        if ( ! $this->upload->do_upload('prod_img'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $array["product_image"]=$img_data['file_name'];
                        }

                   }

                    $this->common_model->data_update("products",$array,array("product_id"=>$prod_id));
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect('admin/products');
               	}
            }
            $this->load->model("product_model");
            $data["product"] = $this->product_model->get_product_by_id($prod_id);
            $this->load->view("admin/product/edit",$data);
        }
        else
        {
            redirect('admin');
        }

}
function add_products(){
	   if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('prod_title', 'Categories Title', 'trim|required');
                $this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');
                $this->form_validation->set_rules('in_stock', 'in_stock', 'trim|required');
                $this->form_validation->set_rules('price', 'price', 'trim|required');
                $this->form_validation->set_rules('qty', 'qty', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		      if($this->form_validation->error_string()!="") {
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                 }

        		}
        		else
        		{
                    $this->load->model("common_model");
                    $array = array(
                     "product_name"=>$this->input->post("prod_title"),
                    "category_id"=>$this->input->post("parent"),
                    "in_stock"=>$this->input->post("in_stock"),
                     "product_description"=>$this->input->post("product_description"),
                    "price"=>$this->input->post("price"),
                    "unit_value"=>$this->input->post("qty"),
                    "unit"=>$this->input->post("unit")
                    );
                    if($_FILES["prod_img"]["size"] > 0){
                        $config['upload_path']          = './uploads/products/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);

                        if ( ! $this->upload->do_upload('prod_img'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $array["product_image"]=$img_data['file_name'];
                        }

                   }

                    $this->common_model->data_insert("products",$array);
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                     <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect('admin/products');
               	}
            }

            $this->load->view("admin/product/add");
        }
        else
        {
            redirect('admin');
        }

}
function delete_product($id){
        if(_is_user_login($this)){
            $this->db->query("Delete from products where product_id = '".$id."'");
            redirect("admin/products");
        }
}
/* ========== Products ==========*/
/* ========== Purchase ==========*/
public function add_purchase(){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('product_id', 'product_id', 'trim|required');
                $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
                $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "product_id"=>$this->input->post("product_id"),
                    "qty"=>$this->input->post("qty"),
                    "price"=>$this->input->post("price"),
                    "unit"=>$this->input->post("unit")
                    );
                    $this->common_model->data_insert("purchase",$array);

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect("admin/add_purchase");
                }

                $this->load->model("product_model");
                $data["purchases"]  = $this->product_model->get_purchase_list();
                $data["products"]  = $this->product_model->get_products();
                $this->load->view("admin/product/purchase",$data);

            }
        }

}
function edit_purchase($id){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('product_id', 'product_id', 'trim|required');
                $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
                $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "product_id"=>$this->input->post("product_id"),
                    "qty"=>$this->input->post("qty"),
                    "price"=>$this->input->post("price"),
                    "unit"=>$this->input->post("unit")
                    );
                    $this->common_model->data_update("purchase",$array,array("purchase_id"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan kamu berhasil ditambahkan...
                                    </div>');
                    redirect("admin/add_purchase");
                }

                $this->load->model("product_model");
                $data["purchase"]  = $this->product_model->get_purchase_by_id($id);
                $data["products"]  = $this->product_model->get_products();
                $this->load->view("admin/product/edit_purchase",$data);

            }
        }
}
function delete_purchase($id){
        if(_is_user_login($this)){
            $this->db->query("Delete from purchase where purchase_id = '".$id."'");
            redirect("admin/add_purchase");
        }
}
/* ========== Purchase END ==========*/
    public function socity(){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('pincode', 'pincode', 'trim|required');
                $this->form_validation->set_rules('latitude', 'latitude', 'trim|required');
                $this->form_validation->set_rules('longitude', 'longitude', 'trim|required');
                $this->form_validation->set_rules('socity_name', 'Socity Name', 'trim|required');
                $this->form_validation->set_rules('delivery', 'Delivery Charges', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "socity_name"=>$this->input->post("socity_name"),
                    "pincode"=>$this->input->post("pincode"),
                    "latitude"=>$this->input->post("latitude"),
                    "longitude"=>$this->input->post("longitude"),
                    "delivery_charge"=>$this->input->post("delivery")

                    );
                    $this->common_model->data_insert("socity",$array);

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/socity");
                }

                $this->load->model("product_model");
                $data["socities"]  = $this->product_model->get_socities();
                $this->load->view("admin/socity/list",$data);

            }
        }
         else
        {
            redirect('admin');
        }

    }
    public function edit_socity($id){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('pincode', 'pincode', 'trim|required');
                $this->form_validation->set_rules('socity_name', 'Socity Name', 'trim|required');
                $this->form_validation->set_rules('delivery_charge', 'Delivery Charges', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "socity_name"=>$this->input->post("socity_name"),
                    "pincode"=>$this->input->post("pincode"),
                    "delivery_charge"=>$this->input->post("delivery_charge")

                    );
                    $this->common_model->data_update("socity",$array,array("socity_id"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/socity");
                }

                $this->load->model("product_model");
                $data["socity"]  = $this->product_model->get_socity_by_id($id);
                $this->load->view("admin/socity/edit",$data);

            }
        }
         else
        {
            redirect('admin');
        }

    }
    function delete_socity($id){
        if(_is_user_login($this)){
            $this->db->query("Delete from socity where socity_id = '".$id."'");
            redirect("admin/socity");
        }
         else
        {
            redirect('admin');
        }
    }

    // datasales //
    function registers(){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $users = $this->product_model->get_all_sales();
            $this->load->view("admin/allusers",array("users"=>$users));
        }
         else
        {
            redirect('admin');
        }
    }

    public function addusers()
    {
        if(_is_user_login($this)){
         if(isset($_POST))
            {
                $this->load->library('form_validation');
                 $this->form_validation->set_rules('user_name', 'Nama Lengkap', 'trim|required');
                $this->form_validation->set_rules('user_mobile', 'No Hp', 'trim|numeric|required|is_unique[registers.user_phone]');
                $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[registers.user_email]');
                 $this->form_validation->set_rules('password', 'Password', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');

        		}
        		else
        		{
                    $this->load->model("common_model");
                    $array = array(
                                            "user_phone"=>$this->input->post("user_mobile"),
                                            "user_fullname"=>$this->input->post("user_name"),
                                            "user_email"=>$this->input->post("user_email"),
                                            "user_password"=>md5($this->input->post("password")),
                                            "status" => 1

                    );
                     if($_FILES["prof_img"]["size"] > 0){
                        $config['upload_path']          = './uploads/profile/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);

                        if ( ! $this->upload->do_upload('prof_img'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $array["user_image"]=$img_data['file_name'];
                        }

                   }
                    $this->common_model->data_insert("registers",$array);

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect('admin/registers');
              	}
            }
	            $this->load->model("product_model");
                $users = $this->product_model->get_all_sales();
                $this->load->view("admin/addusers",array("users"=>$users));
        }
        else
        {
            redirect('admin');
        }
    }

    function edit_password($id){
        if(_is_user_login($this)){
            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "user_password"=>md5($this->input->post("user_password")),

                    );
                    $this->common_model->data_update("registers",$array,array("user_id"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/registers");
                }

                $this->load->model("product_model");
                $data["registers"]  = $this->product_model->get_registers_by_id($id);
                $this->load->view("admin/edit_password",$data);
        }
         else
        {
            redirect('admin');
        }
    }
}

    function edit_users($id){
        if(_is_user_login($this)){
            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('user_fullname', 'user_fullname', 'trim|required');
                $this->form_validation->set_rules('user_phone', 'user_phone', 'trim|required');
                $this->form_validation->set_rules('user_email', 'user_email', 'trim|required');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "user_fullname"=>$this->input->post("user_fullname"),
                    "user_phone"=>$this->input->post("user_phone"),
                    "user_email"=>$this->input->post("user_email"),
                    "user_password"=>md5($this->input->post("user_password")),
                    "status"=>$this->input->post("prod_status")

                    );
                    if($_FILES["prof_img"]["size"] > 0){
                        $config['upload_path']          = './uploads/profile/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);

                        if ( ! $this->upload->do_upload('prof_img'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $array["user_image"]=$img_data['file_name'];
                        }

                   }


                    $this->common_model->data_update("registers",$array,array("user_id"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/registers");
                }

                $this->load->model("product_model");
                $data["registers"]  = $this->product_model->get_registers_by_id($id);
                $this->load->view("admin/editusers",$data);
        }
         else
        {
            redirect('admin');
        }
    }
}



    function delete_users($id){
        if(_is_user_login($this)){
            $this->db->query("Delete from registers where user_id = '".$id."'");
            redirect("admin/registers");
        }
         else
        {
            redirect('admin');
        }
    }
// data sales end //
 /* ========== Page app setting =========*/
public function addpage_app()
	{
	   if(_is_user_login($this))
       {

            $data["error"] = "";
            $data["active"] = "addpageapp";

            if(isset($_REQUEST["addpageapp"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('page_title', 'Page  Title', 'trim|required');
                $this->form_validation->set_rules('page_descri', 'Page Description', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
        		}
        		else
        		{
                    $this->load->model("page_app_model");
                    $this->page_app_model->add_page();
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...</div>');
                    redirect('admin/addpage_app');
               	}
            }
            $this->load->view('admin/page_app/addpage_app',$data);
        }
        else
        {
            redirect('login');
        }
    }

    public function allpageapp()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "allpage";

           $this->load->model("page_app_model");
           $data["allpages"] = $this->page_app_model->get_pages();

           $this->load->view('admin/page_app/allpage_app',$data);
        }
        else
        {
            redirect('login');
        }
    }
    public function editpage_app($id)
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "allpage";

           $this->load->model("page_app_model");
           $data["onepage"] = $this->page_app_model->one_page($id);

           if(isset($_REQUEST["savepageapp"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
                $this->form_validation->set_rules('page_id', 'Page Id', 'trim|required');
                $this->form_validation->set_rules('page_descri', 'Page Description', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
        		}
        		else
        		{
                    $this->load->model("page_app_model");
                    $this->page_app_model->set_page();
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil disimpan...</div>');
                    redirect('admin/allpageapp');
               	}
            }
           $this->load->view('admin/page_app/editpage_app',$data);
        }
        else
        {
            redirect('login');
        }
    }
    public function deletepageapp($id)
	{
	   if(_is_user_login($this)){

            $this->db->delete("pageapp",array("id"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil dihapus......
                                    </div>');
            redirect('admin/allpage_app');
        }
        else
        {
            redirect('login');
        }
    }

/* ========== End page page setting ========*/

 public function setting(){
    if(_is_user_login($this)){
	      $this->load->model("setting_model");
                $data["settings"]  = $this->setting_model->get_settings();

                $this->load->view("admin/setting/settings",$data);


        }
    }
 public function edit_settings($id){
    if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('value', 'Amount', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{

                    $this->load->model("common_model");
                    $array = array(
                    "title"=>$this->input->post("title"),
                    "value"=>$this->input->post("value")
                    );

                    $this->common_model->data_update("settings",$array,array("id"=>$id));

                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect("admin/setting");
                }

                $this->load->model("setting_model");
                $data["editsetting"]  = $this->setting_model->get_setting_by_id($id);
                $this->load->view("admin/setting/edit_settings",$data);

            }
        }

    }

    function stock(){
        if(_is_user_login($this)){
            $this->load->model("product_model");
            $data["stock_list"] = $this->product_model->get_leftstock();
            $this->load->view("admin/product/stock",$data);
        }
    }
/* ========== End page page setting ========*/
   function testnoti(){
        $token =  "flbcqPKhZSk:APA91bE1akFG5ixG8DS8E1rG0tza67cTzwaohJm5NjrDu0HqZfmHKsBOubtu78njQNuTLHr5lbFtd888FmazUVzmD6wSZ6IJPSM9gaYOfVLvcESVrqvo0qaZgNi4lVqteM1xgzQe5-yL";
    }
     function notification(){
         if(_is_user_login($this)){

            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('descri', 'Description', 'trim|required');
                  if ($this->form_validation->run() == FALSE)
        		  {
                              if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                  }else{
                      $message["title"] = $this->config->item("company_title");
                                $message["message"] = $this->input->post("descri");
                                $message["image"] = "";
                                $message["created_at"] = date("Y-m-d h:i:s");

                            $this->load->helper('gcm_helper');
                            $gcm = new GCM();
                            //$result = $gcm->send_topics("/topics/rabbitapp",$message ,"ios");

                            $result = $gcm->send_topics("/topics/grocery",$message ,"android");

                            $q = $this->db->query("Select user_ios_token from users");
                            $registers = $q->result();
                      foreach($registers as $regs){
                         if($regs->user_ios_token!="")
                                 $registatoin_ids[] = $regs->user_ios_token;
                     }
                     if(count($registatoin_ids) > 1000){

                      $chunk_array = array_chunk($registatoin_ids,1000);
                      foreach($chunk_array as $chunk){
                       $result = $gcm->send_notification($chunk, $message,"ios");
                      }

                     }else{

                       $result = $gcm->send_notification($registatoin_ids, $message,"ios");
                        }

                             redirect("admin/notification");
                  }

                   $this->load->view("admin/product/notification");

            }
        }

    }

    function time_slot(){
        if(_is_user_login($this)){
                $this->load->model("time_model");
                $timeslot = $this->time_model->get_time_slot();

                $this->load->library('form_validation');
                $this->form_validation->set_rules('opening_time', 'Opening Hour', 'trim|required');
                $this->form_validation->set_rules('closing_time', 'Closing Hour', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{
        		  if(empty($timeslot)){
                    $q = $this->db->query("Insert into time_slots(opening_time,closing_time,time_slot) values('".date("H:i:s",strtotime($this->input->post('opening_time')))."','".date("H:i:s",strtotime($this->input->post('closing_time')))."','".$this->input->post('interval')."')");
                  }else{
                    $q = $this->db->query("Update time_slots set opening_time = '".date("H:i:s",strtotime($this->input->post('opening_time')))."' ,closing_time = '".date("H:i:s",strtotime($this->input->post('closing_time')))."',time_slot = '".$this->input->post('interval')."' ");
                  }
                }

            $timeslot = $this->time_model->get_time_slot();
            $this->load->view("admin/timeslot/edit",array("schedule"=>$timeslot));
        }
    }
    function closing_hours(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('opening_time', 'Start Hour', 'trim|required');
                $this->form_validation->set_rules('closing_time', 'End Hour', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
        		}
        		else
        		{
        		      $array = array("date"=>date("Y-m-d",strtotime($this->input->post("date"))),
                      "from_time"=>date("H:i:s",strtotime($this->input->post("opening_time"))),
                      "to_time"=>date("H:i:s",strtotime($this->input->post("closing_time")))
                      );
                      $this->db->insert("closing_hours",$array);
                }

         $this->load->model("time_model");
         $timeslot = $this->time_model->get_closing_date(date("Y-m-d"));
         $this->load->view("admin/timeslot/closing_hours",array("schedule"=>$timeslot));


    }


     function delete_closing_date($id){
        if(_is_user_login($this)){
            $this->db->query("Delete from closing_hours where id = '".$id."'");
            redirect("admin/closing_hours");
        }

    }
    public function addslider()
	{
	   if(_is_user_login($this)){

            $data["error"] = "";
            $data["active"] = "addslider";

            if(isset($_REQUEST["addslider"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('slider_title', 'Slider Title', 'trim|required');
                if (empty($_FILES['slider_img']['name']))
                {
                    $this->form_validation->set_rules('slider_img', 'Slider Image', 'required');
                }

                if ($this->form_validation->run() == FALSE)
        		{
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
        		}
        		else
        		{
                    $add = array(
                                    "slider_title"=>$this->input->post("slider_title"),
                                    "slider_status"=>$this->input->post("slider_status"),
                                    "slider_url"=>$this->input->post("slider_url")
                                    );

                        if($_FILES["slider_img"]["size"] > 0){
                            $config['upload_path']          = './uploads/sliders/';
                            $config['allowed_types']        = 'gif|jpg|png|jpeg';
                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('slider_img'))
                            {
                                    $error = array('error' => $this->upload->display_errors());
                            }
                            else
                            {
                                $img_data = $this->upload->data();
                                $add["slider_image"]=$img_data['file_name'];
                            }

                       }

                       $this->db->insert("slider",$add);

                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect('admin/addslider');
               	}
            }
	   	$this->load->view('admin/slider/addslider',$data);
        }
        else
        {
            redirect('admin');
        }
	}

     public function listslider()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "listslider";
           $this->load->model("slider_model");
           $data["allslider"] = $this->slider_model->get_slider();
           $this->load->view('admin/slider/listslider',$data);
        }
        else
        {
            redirect('admin');
        }
    }
     public function editslider($id)
	{
	   if(_is_user_login($this))
       {

            $this->load->model("slider_model");
           $data["slider"] = $this->slider_model->get_slider_by_id($id);

	        $data["error"] = "";
            $data["active"] = "listslider";
            if(isset($_REQUEST["saveslider"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('slider_title', 'Slider Title', 'trim|required');

                  if ($this->form_validation->run() == FALSE)
        		{
        		  if($this->form_validation->error_string()!="")
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
        		}
        		else
        		{
                    $this->load->model("slider_model");
                    $this->slider_model->edit_slider($id);
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Sukses!</strong> Permintaan anda berhasil ditambahkan...
                                    </div>');
                    redirect('admin/listslider');
               	}
            }
	   	   $this->load->view('admin/slider/editslider',$data);
        }
        else
        {
            redirect('admin');
        }
	}
     function deleteslider($id){
        $data = array();
            $this->load->model("slider_model");
            $slider  = $this->slider_model->get_slider_by_id($id);
           if($slider){
                $this->db->query("Delete from slider where id = '".$slider->id."'");
                unlink("uploads/sliders/".$slider->slider_image);
                redirect("admin/listslider");
           }
             else
        {
            redirect('admin');
        }
    }


}
