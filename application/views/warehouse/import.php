<div class="modal fade" id="warehouse-import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nhập kho</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Chọn sản phẩm <span class="label label-danger">(Bắt buộc) <span class="notification error_product_id"></span></span></label>
                    <select class="form-control product_id select2" style="width: 100%;">
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php if (!empty($productsDefault)) : ?>
                            <?php foreach ($productsDefault as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kho hàng <span class="label label-danger">(Bắt buộc) <span class="notification error_kho_id"></span></span></label>
                    <select class="form-control kho_id">
                        <option value="">-- Chọn kho --</option>
                        <?php if (!empty($warehouseLocations)) : ?>
                            <?php foreach ($warehouseLocations as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                <button type="button" class="btn btn-success save">Nhập kho</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#warehouse-import .save').click(function() {
        var parent = $('#warehouse-import .modal-content');
        parent.find('.notification').html('');
        var product_id = parent.find('.product_id').val();
        var kho_id = parent.find('.kho_id').val();
        var qty = parent.find('.qty').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehouse/action-import') ?>",
            data: {
                product_id: product_id,
                kho_id: kho_id,
                qty: qty
            },
            beforeSend: function() {
                parent.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(result) {
                parent.find('.overlay').remove();
                if (result.status === 0) {
                    if (result.errors.product_id) {
                        parent.find('.error_product_id').html(result.errors.product_id);
                    }
                    if (result.errors.kho_id) {
                        parent.find('.error_kho_id').html(result.errors.kho_id);
                    }
                    if (result.errors.qty) {
                        parent.find('.error_qty').html(result.errors.qty);
                    }
                } else if (result.status === 1) {
                    parent.find('.product_id').val('').trigger('change');
                    parent.find('.kho_id').val('');
                    parent.find('.qty').val('');
                    parent.find('.success').html(result.messenger);
                } else {
                    console.log(result);
                }
            }
        });
    })
    $("#warehouse-import").on("hidden.bs.modal", function() {
        var parent = $('#warehouse-import .modal-content');
        parent.find('.product_id').val('').trigger('change');
        parent.find('.kho_id').val('');
        parent.find('.qty').val('');
        parent.find('.notification').html('');
    });
</script>