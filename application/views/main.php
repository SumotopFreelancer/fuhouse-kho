<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('head'); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<?php $this->load->view('header'); ?>
		<?php $this->load->view('menuleft'); ?>
		<div class="content-wrapper">
			<?php $this->load->view($temp, $this->data); ?>
		</div>
		<?php $this->load->view('footer'); ?>
		<div class="control-sidebar-bg"></div>
	</div>
	<?php $this->load->view('script'); ?>
	<!-- Xuất kho -->
	<?php $this->load->view('export-for-order', $this->data); ?>
</body>

</html>