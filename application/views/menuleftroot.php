<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header"><a href="<?= base_url() ?>">BẢNG ĐIỀU KHIỂN</a></li>
    <li>
        <a href="<?= base_url('warehousemap') ?>">
            <i class="fa fa-scribd"></i><span>KHO MAP</span>
        </a>
    </li>
    <li>
        <a href="<?= base_url('warehouse') ?>">
            <i class="fa fa-th-large"></i><span>KHO THƯỜNG</span>
        </a>
    </li>
    <li>
        <a href="javascript:void(0)" data-toggle="modal" data-target="#export-for-order" data-backdrop="static" data-keyboard="false">
            <i class="fa fa-sign-out"></i><span>XUẤT KHO</span>
        </a>
    </li>
</ul>