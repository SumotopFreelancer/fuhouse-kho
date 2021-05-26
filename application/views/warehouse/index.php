<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>Thống kê tồn kho</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i>Bảng điều khiển</a></li>
        <li class="active">Kho thường</li>
    </ol>
</section>
<section class="content">
    <?php if ($this->admin->type == $this->adminRoot->type) : ?>
        <div class="mb-1">
            <button class="btn btn-success" data-toggle="modal" data-target="#warehouse-import" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Nhập kho</button>
        </div>
    <?php elseif (in_array('action_import', $permissions->warehouse, true)) : ?>
        <div class="mb-1">
            <button class="btn btn-success" data-toggle="modal" data-target="#warehouse-import" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Nhập kho</button>
        </div>
    <?php endif; ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách</h3> (<?= !empty($totalRows) ? $totalRows : 0 ?>)
        </div>
        <?php $this->load->view('message', $this->data); ?>
        <div class="box-body table-responsive no-padding mailbox-messages">
            <table class="table table-hover" id="table-search">
                <form method="GET" action="<?= base_url('warehouse') ?>">
                    <tr>
                        <th style="width: 30%">
                            <div class="form-group no-margin">
                                <select name="product_id" class="form-control select2">
                                    <option value="none">-- Chọn sản phẩm --</option>
                                    <?php if (!empty($productsDefault)) : ?>
                                        <?php foreach ($productsDefault as $row) : ?>
                                            <option <?= $this->input->get('product_id') == $row->id ? 'selected' : '' ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </th>
                        <th style="width: 20%">
                            <div class="form-group no-margin">
                                <select name="kho_id" class="form-control select2">
                                    <option value="none">-- Chọn kho --</option>
                                    <?php if (!empty($warehouseLocations)) : ?>
                                        <?php foreach ($warehouseLocations as $row) : ?>
                                            <option <?= $this->input->get('kho_id') == $row->id ? 'selected' : '' ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </th>
                        <th class="pull-right">
                            <button title="Lọc" class="btn btn-warning" type="submit"><i class="fa fa-search"></i></button>
                            <a href="<?= base_url('warehouse') ?>" title="Làm mới" class="btn btn-default" type="submit"><i class="fa fa-refresh"></i></a>
                        </th>
                    </tr>
                </form>
            </table>
            <table class="table table-hover cus_text_mid">
                <tr class="btn-default">
                    <th>Sản phẩm</th>
                    <th>Kho</th>
                    <th>Tồn</th>
                    <th class="text-center" style="width: 100px;">Chuyển kho</th>
                </tr>
                <?php if (!empty($list)) : ?>
                    <?php foreach ($list as $row) : ?>
                        <tr>
                            <td><?= $row->product_name ?></td>
                            <td><?= $row->kho_name  ?></td>
                            <td><b><?= $row->total  ?></b></td>
                            <td class="text-center">
                                <?php if ($this->admin->type == $this->adminRoot->type) : ?>
                                    <button onclick="loadwarehouse(<?= $row->id ?>, <?= $row->kho_id ?>)" class="btn btn-sm btn-social-icon btn-warning" title="Chuyển kho"><i class="fa fa-retweet fa-fw"></i></button>
                                <?php elseif (in_array('action_transfer', $permissions->warehouse, true)) : ?>
                                    <button onclick="loadwarehouse(<?= $row->id ?>, <?= $row->kho_id ?>)" class="btn btn-sm btn-social-icon btn-warning" title="Chuyển kho"><i class="fa fa-retweet fa-fw"></i></button>
                                <?php else : ?>
                                    ---
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
        <?php if (!empty($phantrang)) : ?>
            <div class="box-footer clearfix">
                <?= $phantrang ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Nhập kho -->
<?php $this->load->view('warehouse/import'); ?>
<!-- Chuyển kho -->
<div class="modal fade" id="warehouse-transfer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Chuyển kho</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Đến kho <span class="label label-danger">(Bắt buộc) <span class="notification error_warehouse_to"></span></span></label>
                    <select class="form-control warehouse_to">
                        <option value="">-- Chọn kho --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Số lượng <span class="label label-danger">(Bắt buộc) <span class="notification error_qty"></span></span></label>
                    <input class="form-control qty" type="number">
                </div>
                <div class="notification success"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success btn-transfer" data-id="">Chuyển kho</button>
            </div>
        </div>
    </div>
</div>

<script>
    function loadwarehouse(warehouseId, warehouseFromId) {
        var parent = $('#warehouse-transfer .modal-content');
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehouse/warehouse-to') ?>",
            data: {
                warehouseFromId: warehouseFromId
            },
            success: function(result) {
                if (result.status === 1) {
                    parent.find('.warehouse_to').html(result.html);
                    parent.find('.btn-transfer').attr('data-id', warehouseId);
                    $('#warehouse-transfer').modal('show');
                } else {
                    console.log(result);
                }
            }
        });
    }
    $('.btn-transfer').click(function() {
        var parent = $('#warehouse-transfer .modal-content');
        parent.find('.notification').html('');
        var warehouse_id = $(this).attr('data-id');
        var warehouse_to = parent.find('.warehouse_to').val();
        var qty = parent.find('.qty').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehouse/action-transfer') ?>",
            data: {
                warehouse_id: warehouse_id,
                warehouse_to: warehouse_to,
                qty: qty
            },
            beforeSend: function() {
                parent.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(result) {
                parent.find('.overlay').remove();
                if (result.status === 0) {
                    if (result.errors.warehouse_to) {
                        parent.find('.error_warehouse_to').html(result.errors.warehouse_to);
                    }
                    if (result.errors.qty) {
                        parent.find('.error_qty').html(result.errors.qty);
                    }
                } else if (result.status === 1) {
                    parent.find('.warehouse_to').val('');
                    parent.find('.qty').val('');
                    parent.find('.success').html(result.messenger);
                } else {
                    console.log(result);
                }
            }
        });
    });

    $("#warehouse-transfer").on("hidden.bs.modal", function() {
        var parent = $('#warehouse-transfer .modal-content');
        parent.find('.warehouse_to').val('');
        parent.find('.qty').val('');
        parent.find('.notification').html('');
    });
</script>