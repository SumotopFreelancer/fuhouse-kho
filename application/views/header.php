<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<header class="main-header">
  <a href="<?= base_url() ?>" class="logo">
    <span class="logo-mini"><img src="<?= public_url(_imgWebsiteShort) ?>"></span>
    <span class="logo-lg"><img src="<?= public_url(_imgWebsite) ?>"></span>
  </a>
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">MENU</span></a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?= public_url('img/user2-160x160.jpg') ?>" class="user-image">
            <span class="hidden-xs"><?= $this->admin->name; ?></span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="<?= public_url('img/user2-160x160.jpg') ?>" class="img-circle">
              <p><?= $this->admin->name; ?>
                <small>Thành viên từ: <?= get_date_admin($this->admin->created) ?></small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?= base_url('admin/edit/' . $this->admin->id) ?>" class="btn btn-default btn-flat">Tài khoản</a>
              </div>
              <div class="pull-right">
                <a href="<?= base_url('home/logout') ?>" class="btn btn-default btn-flat">Đăng xuất</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>