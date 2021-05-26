<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header"><a href="<?= base_url('warranty/new') ?>">BẢNG ĐIỀU KHIỂN</a></li>
    <?php if (isset($permissions->warehousemap)) : ?>
        <li>
            <a href="<?= base_url('warehousemap') ?>">
                <i class="fa fa-scribd"></i><span>KHO MAP</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($permissions->warehouse)) : ?>
        <li>
            <a href="<?= base_url('warehouse') ?>">
                <i class="fa fa-th-large"></i><span>KHO THƯỜNG</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (isset($permissions->warehousemap) && in_array('export_for_order', $permissions->warehousemap, true)) : ?>
        <li>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#export-for-order" data-backdrop="static" data-keyboard="false">
                <i class="fa fa-sign-out"></i><span>XUẤT KHO</span>
            </a>
        </li>
    <?php endif; ?>
</ul>