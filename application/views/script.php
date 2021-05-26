<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= public_url('library/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
<script src="<?= public_url('js/adminlte.min.js') ?>"></script>
<script src="<?= public_url('plugins/iCheck/icheck.min.js') ?>"></script>
<script src="<?= public_url('library/select2/dist/js/select2.full.min.js') ?>"></script>
<script src="<?= public_url('library/fancybox/jquery.fancybox.min.js') ?>"></script>
<script src="<?= public_url('plugins/ckeditor/ckeditor.js') ?>"></script>
<script src="<?= public_url('plugins/ckfinder/ckfinder.js') ?>"></script>
<script src="<?= public_url('library/moment/min/moment.min.js') ?>"></script>
<script src="<?= public_url('library/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?= public_url('plugins/input-mask/jquery.inputmask.js') ?>"></script>
<script src="<?= public_url('js/jquery.priceformat.min.js') ?>"></script>
<script src="<?= public_url('js/custom.js') ?>"></script>

<script type="text/javascript">
  // Open CKFinder for choose file
  function openFile(div) {
    CKFinder.popup({
      chooseFiles: true,
      width: 800,
      height: 600,
      onInit: function(finder) {
        finder.on('files:choose', function(evt) {
          var file = evt.data.files.first();
          $(div).parents('.choose_file').find('.link_file').val(file.getUrl());
        });
      }
    });
  };
  // End Choose file

  // Open CKFinder for choose avatar
  function openCKfinder(div) {
    $(div).parent('.tongimg').find('.cus_delete_img').remove();
    CKFinder.popup({
      chooseFiles: true,
      width: 800,
      height: 600,
      onInit: function(finder) {
        finder.on('files:choose', function(evt) {
          var file = evt.data.files.first();
          $(div).find('.cus_ckfinder').val(file.getUrl());
          $(div).find('.show_img').html('<img class="img" src="' + file.getUrl() + '">');
          $(div).parent('.tongimg').append('<span class="cus_delete_img" title="Xóa" onclick="deleteOneImg(this)"><i class="fa fa-times fa-fw"></i></span>');
        });
        finder.on('file:choose:resizedImage', function(evt) {
          $(div).find('.cus_ckfinder').val(evt.data.resizedUrl);
          $(div).find('.show_img').html('<img class="img" src="' + evt.data.resizedUrl + '"><span class="cus_delete_img" title="Xóa" onclick="deleteOneImg(this)"><i class="fa fa-times fa-fw"></i></span>');
          $(div).parent('.tongimg').append('<span class="cus_delete_img" title="Xóa" onclick="deleteOneImg(this)"><i class="fa fa-times fa-fw"></i></span>');
        });
      }
    });
  };
  // Delete avatar
  function deleteOneImg(img) {
    var parent = $(img).parent('.tongimg');
    parent.find('.img').remove();
    parent.find('.show_img').html('<b>Chọn ảnh</b>');
    parent.find('.image_link').attr('value', '');
    $(img).remove();
  }

  // Open and choose Muti image
  function openCKfinderMulti(img, altImg, wrapImg) {
    CKFinder.popup({
      chooseFiles: true,
      width: 800,
      height: 600,
      onInit: function(finder) {
        finder.on('files:choose', function(evt) {
          //  console.log(evt.data.files);
          var filesArr = evt.data.files;
          var countI = 0;
          filesArr.forEach(function(file) {
            countI++;
            //set source image vo
            var strAppend = '<div class="col-md-6">';
            strAppend += '<input name="' + img + '[]" type="hidden" value="' + file.getUrl() + '">';
            strAppend += '<div class="cus_img_box"><img src="' + file.getUrl() + '" class="img-responsive"/></div>';
            strAppend += '<input name="' + altImg + '[]" type="text" placeholder="Alt ảnh...">';
            strAppend += '<span class="cus_delete_img" title="Xóa" onclick="deleteAnhKemTheo(this)"><i class="fa fa-times fa-fw"></i></span>';
            strAppend += '</div>';
            $('#' + wrapImg).append(strAppend);
          });
        });
      }
    });
  };
  // Delete muti image
  function deleteAnhKemTheo(img) {
    var parent = $(img).parent();
    parent.remove()
  }

  // Note ? in textarea
  $(document).ready(function() {
    $('.cTool').tooltip();
  });

  // Active menu sidebar
  var url = window.location;
  $('ul.sidebar-menu a').filter(function() {
    return this.href == url;
  }).parent().addClass('active');
  $('ul.treeview-menu a').filter(function() {
    return this.href == url;
  }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
</script>