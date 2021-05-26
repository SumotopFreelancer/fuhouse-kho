<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image"><img src="<?= public_url('img/user2-160x160.jpg') ?>" class="img-circle"></div>
      <div class="pull-left info">
        <p><?= $this->admin->name; ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <?php if ($this->admin->type != $this->adminRoot->type) : ?>
      <?php $this->load->view('menuleftadmin'); ?>
    <?php else : ?>
      <?php $this->load->view('menuleftroot'); ?>
    <?php endif; ?>
  </section>
</aside>