<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin #page-container -->
<div id="page-container" class="fade page-sidebar-fixed page-header-fixed <?php if($subtitle == 'Pesanan'){ echo 'in page-sidebar-minified';}?>">
  <!-- begin #header -->
  <div id="header" class="header navbar navbar-default navbar-fixed-top" style="background-color: #FFFF;">
    <!-- begin container-fluid -->
    <div class="container-fluid">
      <!-- begin mobile sidebar expand / collapse button -->
      <div class="navbar-header">
        <a href="<?php echo base_url(changeLink('panel/dashboard/'));?>" class="navbar-brand" style="font-weight:bold"><?php echo $apps_code;?> <?php echo $apps_version;?></a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
          <span class="icon-bar" style="background: black;"></span>
          <span class="icon-bar" style="background: black;"></span>
          <span class="icon-bar" style="background: black;"></span>
        </button>
      </div>
      <!-- end mobile sidebar expand / collapse button -->

      <!-- begin header navigation right -->
          
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown navbar-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <?php if (!empty($this->session->userdata('foto_pengguna'))): ?>
                <img src="<?php echo base_url().$this->session->userdata('foto_pengguna');?>" alt="" style="border:1px solid #fff" />
              <?php else: ?>
                <img src="<?php echo base_url('assets/img/user.png');?>" alt="" style="border:1px solid #fff" />
            <?php endif; ?>
            <span class="hidden-xs" style="font-weight: bold;"><?php echo $this->session->userdata('nama_lengkap');?></span> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu animated fadeInLeft">
            <li class="arrow"></li>
            <li><a href="<?php echo base_url(changeLink('panel/profile/edit'));?>">Edit Profile</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo base_url('auth/logout');?>">Log Out</a></li>
          </ul>
        </li>
      </ul>
      <!-- end header navigation right -->
      <div class="navbar-nav hidden-md hidden-lg" style="padding-top: 10px;">
        <div class="navbar-item">          
            <a href="<?php echo base_url(changeLink('panel/dashboard/'));?>" class="navbar-link btn blue-rasp text-white"><i class="fa fa-home"></i> Dashboard</a>
        </div>
      </div>
    </div>
    <!-- end container-fluid -->
  </div>
  <!-- end #header -->
