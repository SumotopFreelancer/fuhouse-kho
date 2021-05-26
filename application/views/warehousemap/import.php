<div class="modal fade" id="warehouse-map-import">
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
                        <?php if (!empty($productsMap)) : ?>
                            <?php foreach ($productsMap as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Chọn màu <span class="label label-danger">(Bắt buộc) <span class="notification error_color"></span></span></label>
                    <select class="form-control color">
                        <option value="">-- Chọn màu --</option>
                    </select>
                </div>
                <div class="form-group color_required hidden">
                    <label>Nhập màu <span class="label label-danger">(Bắt buộc) <span class="notification error_color_required"></span></span></label>
                    <input class="form-control color_required_val" type="text">
                </div>
                <div class="form-group">
                    <label>Chọn size <span class="label label-danger">(Bắt buộc) <span class="notification error_size_id"></span></span></label>
                    <select class="form-control size_id">
                        <option value="">-- Chọn size --</option>
                    </select>
                </div>
                <div class="form-group size_required hidden">
                    <label>Nhập size <span class="label label-danger">(Bắt buộc) <span class="notification error_size_required"></span></span></label>
                    <input class="form-control size_required_val" type="text">
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
    $('#warehouse-map-import .product_id').on("select2:select", function(e) {
        var product_id = $(this).val();
        var parent = $('#warehouse-map-import .modal-content');
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehousemap/get-size-color') ?>",
            data: {
                product_id: product_id,
            },
            beforeSend: function() {
                parent.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(result) {
                parent.find('.overlay').remove();
                if (result.status === 1 || result.status === 0) {
                    parent.find('.size_id').html(result.sizeHtml);
                    parent.find('.color').html(result.colorHtml);
                } else {
                    console.log(result);
                }
            }
        });
    });
    $('#warehouse-map-import .save').click(function() {
        var parent = $('#warehouse-map-import .modal-content');
        parent.find('.notification').html('');
        var product_id = parent.find('.product_id').val();
        var color = parent.find('.color').val();
        var color_required_val = parent.find('.color_required_val').val();
        var size_id = parent.find('.size_id').val();
        var size_required_val = parent.find('.size_required_val').val();
        var kho_id = parent.find('.kho_id').val();
        var qty = parent.find('.qty').val();
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "<?= base_url('warehousemap/action-import') ?>",
            data: {
                product_id: product_id,
                color: color,
                color_required_val: color_required_val,
                size_id: size_id,
                size_required_val: size_required_val,
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
                    if (result.errors.color) {
                        parent.find('.error_color').html(result.errors.color);
                    }
                    if (result.errors.color_required_val) {
                        parent.find('.error_color_required').html(result.errors.color_required_val);
                    }
                    if (result.errors.size_id) {
                        parent.find('.error_size_id').html(result.errors.size_id);
                    }
                    if (result.errors.size_required_val) {
                        parent.find('.error_size_required').html(result.errors.size_required_val);
                    }
                    if (result.errors.kho_id) {
                        parent.find('.error_kho_id').html(result.errors.kho_id);
                    }
                    if (result.errors.qty) {
                        parent.find('.error_qty').html(result.errors.qty);
                    }
                } else if (result.status === 1) {
                    parent.find('.product_id').val('').trigger('change');
                    parent.find('.color').val('');
                    parent.find('.color_required_val').val('');
                    parent.find('.color_required').addClass('hidden');
                    parent.find('.size_id').val('');
                    parent.find('.size_required_val').val('');
                    parent.find('.size_required').addClass('hidden');
                    parent.find('.kho_id').val('');
                    parent.find('.qty').val('');
                    parent.find('.success').html(result.messenger);
                } else {
                    console.log(result);
                }
            }
        });
    });
    $("#warehouse-map-import").on("hidden.bs.modal", function() {
        var parent = $('#warehouse-map-import .modal-content');
        parent.find('.product_id').val('').trigger('change');
        parent.find('.kho_id').val('');
        parent.find('.qty').val('');
        parent.find('.size_required_val').val('');
        parent.find('.color_required_val').val('');
        parent.find('.size_required').addClass('hidden');
        parent.find('.color_required').addClass('hidden');
        parent.find('.notification').html('');
    });
    $('#warehouse-map-import .size_id').change(function() {
        var parent = $('#warehouse-map-import .modal-content');
        var size = $(this).val();
        if (size == 'required') {
            parent.find('.size_required').removeClass('hidden')
        } else {
            parent.find('.size_required').addClass('hidden')
        }
    });
    $('#warehouse-map-import .color').change(function() {
        var parent = $('#warehouse-map-import .modal-content');
        var color = $(this).val();
        if (color == 'required') {
            parent.find('.color_required').removeClass('hidden')
        } else {
            parent.find('.color_required').addClass('hidden')
        }
    });
</script>