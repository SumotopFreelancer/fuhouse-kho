<div class="modal fade" id="export-for-order">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Xuất kho cho đơn hàng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Chọn đơn hàng <span class="label label-danger">(Bắt buộc) <span class="notification error_transaction_id"></span></span></label>
                    <select class="form-control transaction_id select2" style="width: 100%;">
                        <option value="">-- Chọn đơn hàng --</option>
                        <?php if (!empty($transactions)) : ?>
                            <?php foreach ($transactions as $row) : ?>
                                <option value="<?= $row->id ?>">#<?= str_pad($row->id, 6, '0', STR_PAD_LEFT) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div id="infoOrder"></div>
                <div class="notification error_kho_ids"></div>
                <div class="notification error_order"></div>
                <div class="notification success"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success save">Xuất kho</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#export-for-order .transaction_id').on("select2:select", function(e) {
        var transaction_id = $(this).val();
        var parent = $('#export-for-order .modal-content');
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehousemap/get-order-detail') ?>",
            data: {
                transaction_id: transaction_id
            },
            beforeSend: function() {
                parent.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(result) {
                parent.find('.overlay').remove();
                if (result.status === 1) {
                    parent.find('#infoOrder').html(result.html);
                } else {
                    parent.find('#infoOrder').html('');
                    console.log(result);
                }
            }
        });
    });
    $("#export-for-order").on("hidden.bs.modal", function() {
        var parent = $('#export-for-order .modal-content');
        parent.find('.transaction_id').val('').trigger('change');
        parent.find('#infoOrder').html('');
    });

    $('#export-for-order .save').click(function name() {
        var parent = $('#export-for-order .modal-content');
        parent.find('.notification').html('');
        var transaction_id = parent.find('.transaction_id').val();
        var order_ids = [];
        parent.find('input[name="order_id"]').map(function() {
            order_ids.push($(this).val());
        }).get();
        var kho_ids = [];
        parent.find('select[name="kho_id"]').map(function() {
            kho_ids.push($(this).val())
        }).get();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehousemap/export-for-order') ?>",
            data: {
                transaction_id: transaction_id,
                order_ids: order_ids,
                kho_ids: kho_ids
            },
            beforeSend: function() {
                parent.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(result) {
                parent.find('.overlay').remove();
                console.log(result);
                if (result.status === 0) {
                    if (result.errors.transaction_id) {
                        parent.find('.error_transaction_id').html(result.errors.transaction_id);
                    }
                    if (result.errors.kho_ids) {
                        parent.find('.error_kho_ids').html(result.errors.kho_ids);
                    }
                    if (result.errors.error_export) {
                        parent.find('.error_order').html(result.errors.error_export);
                    }
                } else if (result.status === 1) {
                    parent.find('.transaction_id').val('').trigger('change');
                    parent.find('#infoOrder').html('');
                    parent.find('.success').html(result.messenger);
                } else {
                    console.log(result);
                }
            }
        });
    });
</script>