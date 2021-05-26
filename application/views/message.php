<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
if (isset($message) && $message) : ?>
	<div class="cus_messenger_action"><?= $message; ?></div>
<?php endif; ?>