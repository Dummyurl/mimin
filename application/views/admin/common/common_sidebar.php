<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo base_url("img/user2-160x160.jpg"); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php _get_current_user_name($this); ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i>  <?php echo $this->lang->line("Online");?></a>
            </div>
          </div>

          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"> <?php echo $this->lang->line("MAIN NAVIGATION");?></li>
            <li>
              <a href="<?php echo site_url("admin/dashboard"); ?>">
                <i class="fa fa-dashboard"></i> <span> <?php echo $this->lang->line("Dashboard");?>
              </a>
            </li>
            <?php if(_get_current_user_type_id($this)==0){ ?>
            <!--<li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Common Settings</span>
                <span class="label label-primary pull-right"></span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> User Settings</a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url("admin/user_types"); ?>"><i class="fa fa-circle-o"></i> User Types</a></li>

                    </ul>
                </li>

              </ul>
             </li>-->
            <li>
              <a href="<?php echo site_url("admin/registers"); ?>">
                <i class="fa fa-users"></i> <span> <?php echo $this->lang->line("Data Sales");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("admin/monitoring"); ?>">
                <i class="fa fa-map-marker"></i> <span> <?php echo $this->lang->line("Maps");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <!--li>
              <a href="<!?php echo site_url("admin/listkunjungan"); ?>">
                <i class="fa fa-plane"></i> <span> <!?php echo $this->lang->line("Kunjungan");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li-->
               <li>
              <a >
                <i class="fa fa-plane"></i> <span> <?php echo $this->lang->line("Kunjungan");?></span><i class="fa fa-angle-left pull-right"></i></small>
              </a>
              <ul class="treeview-menu">
                     <li>
              <a href="<?php echo site_url("admin/listkunjungan"); ?>">
                <i class="fa fa-table"></i> <span> <?php echo $this->lang->line("Form Kunjungan");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
                    <li>
                      <a href="<?php echo site_url("admin/taskkunjungan"); ?>">
                        <i class="fa fa-table"></i> <span> <?php echo $this->lang->line("Task Kunjungan");?></span> <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                </ul>
            </li>

            <li>
              <a href="<?php echo site_url("admin/listcategories"); ?>">
                <i class="fa fa-list"></i> <span> <?php echo $this->lang->line("Categories");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("admin/socity"); ?>">
                <i class="fa fa-map-signs"></i> <span> <?php echo $this->lang->line("Socity");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("admin/products"); ?>">
                <i class="fa fa-list-alt"></i> <span> <?php echo $this->lang->line("Products");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url("admin/orders"); ?>">
                <i class="fa fa-cart-plus"></i> <span> <?php echo $this->lang->line("Orders_name");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
            <!--li>
              <a href="<!?php echo site_url("admin/stock"); ?>">
                <i class="fa fa-database"></i> <span> <!?php echo $this->lang->line("Stock");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li-->
            <li>
              <a href="<?php echo site_url("admin/listretur"); ?>">
                <i class="fa fa-cart-arrow-down"></i> <span> <?php echo $this->lang->line("Retur");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
             <li>
              <a href="#">
                <i class="fa fa-pie-chart"></i> <span> <?php echo $this->lang->line("Report");?></span><i class="fa fa-angle-left pull-right"></i></small>
              </a>
              <ul class="treeview-menu">
                     <li>
              <a href="<?php echo site_url("admin/laporan"); ?>">
                <i class="fa fa-table"></i> <span> <?php echo $this->lang->line("Report Penjualan");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
                    <li>
                      <a href="<?php echo site_url("admin/laporan_kunjungan"); ?>">
                        <i class="fa fa-bar-chart"></i> <span> <?php echo $this->lang->line("Report Kunjungan");?></span> <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                </ul>
            </li>

             <li>
              <a href="#">
                <i class="fa fa-clock-o"></i> <span> <?php echo $this->lang->line("Delivery Schedule Hours");?></span><i class="fa fa-angle-left pull-right"></i></small>
              </a>
              <ul class="treeview-menu">
                    <li>
                      <a href="<?php echo site_url("admin/time_slot"); ?>">
                        <i class="fa fa-clock-o"></i> <span> <?php echo $this->lang->line("Time Slot");?></span> <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo site_url("admin/closing_hours"); ?>">
                        <i class="fa fa-clock-o"></i> <span> <?php echo $this->lang->line("Closing Hours");?></span> <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                </ul>
            </li>



            <li>
              <a href="#">
                <i class="fa fa-users"></i> <span> <?php echo $this->lang->line("User Management");?></span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                        <li><a href="<?php echo site_url("users"); ?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line("List Admin Users");?></a></li>


              </ul>
            </li>

            <li>
              <a href="#">
                <i class="fa fa-file"></i> <span> <?php echo $this->lang->line("Pages");?></span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                         <li><a href="<?php echo site_url("admin/allpageapp"); ?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line("List");?></a></li>

              </ul>
            </li>

             <li>
              <a href="<?php echo site_url("admin/setting"); ?>">
                <i class="fa fa-cogs"></i> <span> <?php echo $this->lang->line("Order Limit Setting");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>

            <li>
              <a href="<?php echo site_url("admin/notification"); ?>">
                <i class="fa fa-bell"></i> <span> <?php echo $this->lang->line("Notification");?></span> <small class="label pull-right bg-green"></small>
              </a>
            </li>
             <li class="treeview">
              <a href="#">
                <i class="fa fa-picture-o"></i>
                <span> <?php echo $this->lang->line("Slider");?> </span>
                <span class="label label-primary pull-right"></span><i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo site_url("admin/listslider"); ?>"><i class="fa fa-circle-o"></i>  <?php echo $this->lang->line("List");?> </a></li>
                <li><a href="<?php echo site_url("admin/addslider"); ?>"><i class="fa fa-circle-o"></i>  <?php echo $this->lang->line("Add New");?>  </a></li>
              </ul>
            </li>
            <?php  } ?>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>