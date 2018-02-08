<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Tambah Target </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/plugins/daterangepicker/daterangepicker-bs3.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/plugins/datepicker/datepicker3.css"); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/bootstrap/css/bootstrap.min.css"); ?>" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/plugins/datatables/dataTables.bootstrap.css"); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/dist/css/AdminLTE.css
    "); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/dist/css/skins/_all-skins.min.css"); ?>">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php  $this->load->view("admin/common/common_header"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php  $this->load->view("admin/common/common_sidebar"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

                <!-- Content Header (Page header) -->
                 <section class="content-header">
                    <h1>
                         <?php echo $this->lang->line("Tambah Target Kunjungan"); ?>
                        <small> <?php echo $this->lang->line("Preview"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i>  <?php echo $this->lang->line("Home"); ?></a></li>
                        <li><a href="#"> <?php echo $this->lang->line("Categories"); ?></a></li>
                        <li class="active"> <?php echo $this->lang->line("Tambah Target Kunjungan"); ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('success_req'); ?>
                            <!-- general form elements -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"> <?php echo $this->lang->line("Tambah Target Kunjungan"); ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label class="">  <?php echo $this->lang->line("Sales Name"); ?><span class="text-danger">*</span></label>
                                            <select class='text-input form-control' id='namasales' name="id_sales">
                                        <option value='0'>--Pilih Sales--</option>
                                        <?php
                                        foreach ($namas as $nama) {
                                            if ($nama->user_id == $id_sales) {
                                                echo "<option selected=selected value='$nama->user_id'>$nama->user_fullname</option>";
                                            }else{
                                                echo "<option value='$nama->user_id'>$nama->user_fullname</option>";
                                            }
                                        } ?>
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="">  <?php echo $this->lang->line("Toko"); ?><span class="text-danger">*</span></label>
                                            <select class='text-input form-control' id='namatoko' name="id_toko">
                                        <option value='0'>--Pilih Toko--</option>
                                        <?php
                                        foreach ($tokos as $nama) {
                                            if ($nama->socity_id == $id_toko) {
                                                echo "<option selected=selected value='$nama->socity_id'>$nama->socity_name</option>";
                                            }else{
                                                echo "<option value='$nama->socity_id'>$nama->socity_name</option>";
                                            }
                                        } ?>
                                        </select>
                                        </div>
                                          <div class="form-group">
                                            <label class="">  <?php echo $this->lang->line("Batas Jam"); ?><span class="text-danger">*</span></label>
                                            <input type="text" name="jam_akhir" class="form-control" placeholder="00:00"/>
                                        </div>
                                          <div class="form-group">
                                            <label class="">  <?php echo $this->lang->line("Date"); ?><span class="text-danger">*</span></label>
                                            <input type="text" name="tanggal" class="form-control" placeholder="2018-02-04"/>
                                        </div>

                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Tambah Target" />
                                        <a class="btn btn-primary" href="#" onclick="history.back(1);">Kembali</a>
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <!-- Main row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- /.content-wrapper -->

      <?php  $this->load->view("admin/common/common_footer"); ?>


      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
<script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/jQuery/jQuery-2.1.4.min.js"); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

   <script type="text/javascript">
$(function() {
    $('input[name="datepicker"]').datepicker({
        format: "dd-mm-yyyy",
        autoclose:true
    });
});
</script>
    <!-- jQuery 2.1.4 -->
  </body>
</html>
