<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= _nameWebsite ?> - Đăng nhập hệ thống</title>
  <meta name="robots" content="noindex, nofollow" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= public_url('library/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= public_url('library/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= public_url('library/Ionicons/css/ionicons.min.css') ?>">
  <link rel="stylesheet" href="<?= public_url('css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= public_url('plugins/iCheck/square/blue.css') ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700&amp;subset=vietnamese">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-box-body">
      <div class="login-logo">
        <a target="_blank" href="<?= _Website ?>"><img src="<?= public_url(_imgWebsiteDo) ?>"></a>
      </div>
      <p class="login-box-msg">Đăng nhập hệ thống quản trị website</p>
      <form action="" method="POST">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username" value="<?= $this->input->post('username') ?>">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Mật khẩu" name="password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <?php if (form_error('login')) : ?><?= form_error('login') ?><?php endif; ?>
        <div class="row">
          <div class="col-xs-7"></div>
          <div class="col-xs-5">
            <button type="submit" class="btn btn-primary btn-flat pull-right">Đăng nhập</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="<?= public_url('library/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= public_url('library/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
  <script src="<?= public_url('plugins/iCheck/icheck.min.js') ?>"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
      });
    });
  </script>
</body>

</html>