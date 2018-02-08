<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Target Kunjungan</title>
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
                         <?php echo $this->lang->line("Target Kunjungan");?>
                        <small> <?php echo $this->lang->line("Preview");?></small>
                    </h1>
                    <form action="" method="post">
                    <div class="form-group">
                        <div class="input-group">
                        <input type="text" name="datepicker" class="btn btn-default" placeholder="--Pilih--" value= "<?php echo $datepicker; ?>"/>
                        <select class='btn btn-default' id='namasales' name="id_sales">
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
                        <input type="submit" name="filter" class="btn btn-success" value="Filter" />
                        <a href="" class="btn btn-danger" value="Clear" />Clear</a>
                        </div>
                      </div>
                    </form>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("Home");?></a></li>
                        <li><a href="#"> <?php echo $this->lang->line("Kunjungan");?></a></li>
                        <li class="active"> <?php echo $this->lang->line("Target Kunjungan");?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('success_req'); ?>
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"> <?php echo $this->lang->line("Target Kunjungan");?></h3> <br>
                                    <div class="pull-right">
                                    <a href="add_target" class="btn btn-primary">Tambah Data</a>
                                    </div>

                                </div><!-- /.box-header -->

                                <div class="box-body table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                 <th>  <?php echo $this->lang->line("Sales Name");?></th>
                                                <th><?php echo $this->lang->line("Toko");?>  </th>
                                                <th><?php echo $this->lang->line("Batas Jam");?>  </th>
                                                <th><?php echo $this->lang->line("Date");?>  </th>
                                                <th><?php echo $this->lang->line("Nilai");?>  </th>
                                                <th><?php echo $this->lang->line("Waktu Berkunjung");?>  </th>

                                                <th class="text-center" style="width: 100px;"> <?php echo $this->lang->line("Action");?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php foreach($today_kunjungan as $task){ ?>
                                            <tr>
                                                <td><?php echo $task->user_fullname; ?></td>
                                                <td><?php echo $task->socity_name; ?></td>
                                                <td><?php echo $task->jam_akhir; ?></td>
                                                <td><?php echo $task->tanggal; ?></td>
                                                <td><?php echo $task->nilai; ?></td>
                                                <td><?php echo $task->waktu_update; ?></td>
                                                <td class="text-center"><div class="btn-group">
                                                        <?php echo anchor('admin/edit_target/'.$task->id_target_kunjungan, '<i class="fa fa-edit"></i>', array("class"=>"btn btn-success")); ?>
                                                        <?php echo anchor('admin/deletetaskkun/'.$task->id_target_kunjungan, '<i class="fa fa-trash"></i>', array("class"=>"btn btn-danger", "onclick"=>"return confirm('Are you sure delete?')")); ?>

                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div><!-- /.box-body -->
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
<!-- jQuery 2.1.4 -->
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

    </script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.flash.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.data_table').DataTable( {
        dom: 'Bfrtip',
        "order": [[ 0, "desc" ]],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
    </script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

    <!-- Bootstrap 3.3.5 -->
 <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/bootstrap/js/bootstrap.min.js"); ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datatables/jquery.dataTables.min.js"); ?>"></script>
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datatables/dataTables.bootstrap.min.js"); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/dist/js/app.min.js"); ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/dist/js/demo.js"); ?>"></script>
    <script>
      $(function () {

        $('#example1').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "order": [[ 0, "desc" ]],
          "ordering": true,
          "info": true,
          "autoWidth": true,
        });

      });
    </script>
  </body>
</html>