<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Laporan</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/plugins/daterangepicker/daterangepicker-bs3.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/plugins/datepicker/datepicker3.css"); ?>">

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
            <?php echo $this->lang->line("Laporan Penjualan Sales");?>
          </h1>
          <form action="" method="post">
                    <div class="form-group">
                        <div class="input-group">
                        <select class='btn btn-default' id='namasales' name="tahun">
                        <option value='0'>--Pilih Tahun--</option>
                        <?php
                        foreach ($thn as $thn) {
                            if ($tahun == $thn->thn) {
                                echo "<option selected=selected value='$thn->thn'>$thn->thn</option>";
                            }else {
                                echo "<option value='$thn->thn'>$thn->thn</option>";
                            }
                        } ?>
                        </select>
                        <select class='btn btn-default' id='namasales' name="bulan">
                        <option value='0'>--Pilih Bulan--</option>
                        <?php if ($bulan == 1) {?>
                            <option selected=selected value='1'>Januari</option>
                        <?php }else{ ?>
                            <option value='1'>Januari</option>
                        <?php } ?>
                        <?php if ($bulan == 2) {?>
                            <option selected=selected value='2'>Februari</option>
                        <?php }else{ ?>
                            <option value='2'>Februari</option>
                        <?php } ?>
                        <?php if ($bulan == 3) {?>
                            <option selected=selected value='3'>Maret</option>
                        <?php }else{ ?>
                            <option value='3'>Maret</option>
                        <?php } ?>
                        <?php if ($bulan == 4) {?>
                            <option selected=selected value='4'>April</option>
                        <?php }else{ ?>
                            <option value='4'>April</option>
                        <?php } ?>
                        <?php if ($bulan == 5) {?>
                            <option selected=selected value='5'>Mei</option>
                        <?php }else{ ?>
                            <option value='5'>Mei</option>
                        <?php } ?>
                         <?php if ($bulan == 6) {?>
                            <option selected=selected value='6'>Juni</option>
                        <?php }else{ ?>
                            <option value='6'>Juni</option>
                        <?php } ?>
                        <?php if ($bulan == 7) {?>
                            <option selected=selected value='7'>Juli</option>
                        <?php }else{ ?>
                            <option value='7'>Juli</option>
                        <?php } ?>
                        <?php if ($bulan == 8) {?>
                            <option selected=selected value='8'>Agustus</option>
                        <?php }else{ ?>
                            <option value='8'>Agustus</option>
                        <?php } ?>
                        <?php if ($bulan == 9) {?>
                            <option selected=selected value='9'>September</option>
                        <?php }else{ ?>
                            <option value='9'>September</option>
                        <?php } ?>
                        <?php if ($bulan == 10) {?>
                            <option selected=selected value='10'>Oktober</option>
                        <?php }else{ ?>
                            <option value='10'>Oktober</option>
                        <?php } ?>
                         <?php if ($bulan == 11) {?>
                            <option selected=selected value='11'>Nopember</option>
                        <?php }else{ ?>
                            <option value='11'>Nopember</option>
                        <?php } ?>
                          <?php if ($bulan == 12) {?>
                            <option selected=selected value='12'>Desember</option>
                        <?php }else{ ?>
                            <option value='12'>Desember</option>
                        <?php } ?>

                        </select>
                        <input type="submit" name="filter" class="btn btn-success" value="Filter" />
                        <a href="" class="btn btn-danger" value="Clear" />Clear</a>
                        </div>
                      </div>
                    </form>
           <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("Report");?></a></li>
                        <li><a href="#"><?php echo $this->lang->line("Laporan Penjualan Sales");?></a></li>

                    </ol>
        </section>

        <!-- Main content -->
        <section class="content">
         <div class="row">
                        <div class="col-xs-12">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo $this->lang->line("Report");?></h3>

                                </div><!-- /.box-header -->

          <div class="box-body table-responsive">
          <button class="btn btn-success" onclick="window.print();"><i class='fa fa-print'></i> Print</button>
          <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>

                    <th><?php echo $this->lang->line("Name");?></th>
                    <th><?php echo $this->lang->line("Bulan");?></th>
                    <th><?php echo $this->lang->line("Total Orders");?></th>
                    <th><?php echo $this->lang->line("Total Amount");?></th>

                </tr>
                </thead>
          <tbody>
          <?php
          foreach($users as $user)
          {

            ?>

                <tr>


                    <td><?php echo $user->user_fullname; ?></td>
                    <td><?php echo $user->month; ?></td>
                    <td><?php echo $user->total_orders; ?></td>
                    <td><?php echo "Rp. ".number_format($user->total_amount, 0, ',', '.'); ?></td>

                </tr>
            <?php
          }
          ?>
          </tbody>
          </table>
                <html>
    <head>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
        <style type="text/css">
            .container {
                width: 35%;
                margin: 15px auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <canvas id="myChart" width="100" height="100"></canvas>
        </div>
        <script>
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php foreach($users as $user) echo '"'.$user->user_fullname.' / '.$user->month.'",'; ?>],
                    datasets: [{
                            label: 'Jumlah Penjualan',
                            data: [<?php foreach($users as $user) echo ($user->total_amount).','; ?>],
                            backgroundColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 2
                        }]
                },
                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
                }
            });
        </script>
    </body>
</html>


        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <?php  $this->load->view("admin/common/common_footer"); ?>


            <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/jQuery/jQuery-2.1.4.min.js"); ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/bootstrap/js/bootstrap.min.js"); ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datatables/jquery.dataTables.min.js"); ?>"></script>
    <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datatables/dataTables.bootstrap.min.js"); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/dist/js/app.min.js"); ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url($this->config->item("theme_admin")."/dist/js/demo.js"); ?>"></script>
   <script src="<?php echo base_url($this->config->item("theme_admin")."/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

    <script>
      $(function () {

        $('#example1').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": true,
        });

      });
    </script>

  </body>
</html>
