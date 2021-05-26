$(function(){
    'use strict'
    // replace textare to ckeditor
    CKEDITOR.replaceAll('editor');
    //Initialize Select2 Elements
    $('.select2').select2();
    $("select").on("select2:select", function(evt){
      var element = evt.params.data.element;
      var $element = $(element);
      $element.detach();
      $(this).append($element);
      $(this).trigger("change");
    });

    // Check string empty
    function isEmpty(str){
      return (!str || 0 === str.length);
    }
    // Format price
    $('[data-format_price]').priceFormat({
        prefix: '',
        centsLimit: 0,
        // centsSeparator: '.',
        thousandsSeparator: ',',
        clearOnEmpty: true
    });
    // convert VI to EN
    function change_alias(alias){
        var str = alias;
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
        str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
        str = str.replace(/đ/g,"d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
        str = str.replace(/ + /g," ");
        str = str.trim(); 
        return str;
    }
    //Date range picker with time picker
    $('.cus_timer').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": true,
        "drops": "down",
        "locale": {
          "format": "DD/MM/YYYY hh:mm A",
          "separator": " - ",
          "applyLabel": "Chọn",
          "cancelLabel": "Hủy",
          "daysOfWeek": [
              "CN",
              "Hai",
              "Ba",
              "Tư",
              "Năm",
              "Sáu",
              "Bảy"
          ],
          "monthNames": [
              "Tháng 1",
              "Tháng 2",
              "Tháng 3",
              "Tháng 4",
              "Tháng 5",
              "Tháng 6",
              "Tháng 7",
              "Tháng 8",
              "Tháng 9",
              "Tháng 10",
              "Tháng 11",
              "Tháng 12"
          ],
          "firstDay": 1
        },
    });
    //Enable iCheck plugin for checkboxes
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });
    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function(){
      var clicks = $(this).data('clicks');
      if(clicks){
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      }else{
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });
    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function(e){
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");
      //Switch states
      if(glyph){
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }
      if(fa){
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
    // icheck for Nhóm quyền
  $('.add-nhomquyen input[type="checkbox"]').iCheck({
    checkboxClass: 'icheckbox_flat-blue',
    radioClass: 'iradio_flat-blue'
  });
  $(".checkbox-add").click(function(){
    var clicks = $(this).data('clicks');
    if($(".fa", this).hasClass("fa-check-square")){
      if(clicks === undefined){clicks = true}
    }
    if($(".fa", this).hasClass("fa-square-o")){
      if(clicks === undefined){clicks = false}
    }
    if(clicks){
      //Uncheck all checkboxes
      $(this).parents('.parentcheck').find('input[type="checkbox"]').iCheck("uncheck");
      $(".fa", this).removeClass("fa-check-square").addClass('fa-square-o');
    }else{
      //Check all checkboxes
      $(this).parents('.parentcheck').find('input[type="checkbox"]').iCheck("check");
      $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square');
    }
    $(this).data("clicks", !clicks);
  });


    //Xac thuc xoa du lieu
    $('a.btn_del_one').click(function(){
      if(!confirm('Dữ liệu xóa sẽ không khôi phục được! Bạn chắc chắn muốn xóa ?')){
        return false;
      }
    });
    //xoa nhieu du lieu
    $('.btn_del_all').click(function(){ //tim toi the co id = submit,su kien click
      $('.cus_messenger_action').remove();
      if(!confirm('Bạn chắc chắn muốn xóa dữ liệu đã chọn ?')){
        return false;
      }
      var ids = new Array();
      $('[name="id[]"]:checked').each(function(){
        ids.push($(this).val());
      });
      if(!ids.length) return false;
      var url = $(this).attr('url');
      //ajax để xóa
      $.ajax({
        url: url,
        type: 'POST',
        data : {'ids': ids},
        success: function(data){
          var list_id = new Array();
          $(data).filter(".id_delete").each(function(){
            list_id.push($(this).text());
          });
          $('.msg').append('<div class="cus_messenger_action">'+data+'</div>');
          $(list_id).each(function(id, val){
            $('tr.row_'+val).fadeOut( "slow", function() {
              $('tr.row_'+val).remove();
            });
          });
        }
      })
    });

    $('input[name="name"]#auto_convert_name').blur(function(){
      var name = $.trim($(this).val());
      var url = $.trim($("input[name='url']").val());
      var model = $('#form').attr('data-model');
      var action = $('#form').attr('data-action');
      if(isEmpty(name)){
        return false;
      }
      if(!isEmpty(url)){
        return false;
      }
      var data = {'name': name, 'model': model, 'action':action}
      if(action == 'edit'){
        var id = $('#form').attr('data-id');
        data = {'name': name, 'model': model, 'action':action, 'id':id}
      }
      $.ajax({
        url: "/admin/ajax/slug",
        type: 'POST',
        data : data,
        success: function(data){
          $("input[name='url']").val(data);
        }
      });
    });

    $('input[name="name"]#auto_convert_tags').blur(function(){
        var name = $.trim($(this).val());
        var url = $.trim($("input[name='url']").val());
        var action = $('#form').attr('data-action');
        if(isEmpty(name)){
          return false;
        }
        if(!isEmpty(url)){
          return false;
        }
        var data = {'name': name, 'action':action}
        if(action == 'edit'){
          var id = $('#form').attr('data-id');
          data = {'name': name, 'action':action, 'id':id}
        }
        $.ajax({
          url: "/admin/ajax/convert_tags",
          type: 'POST',
          data : data,
          success: function(data){
            $("input[name='url']").val(data);
          }
        });
    });

    $('input[name="name"]#auto_convert_tagsproduct').blur(function(){
        var name = $.trim($(this).val());
        var url = $.trim($("input[name='url']").val());
        var action = $('#form').attr('data-action');
        if(isEmpty(name)){
          return false;
        }
        if(!isEmpty(url)){
          return false;
        }
        var data = {'name': name, 'action':action}
        if(action == 'edit'){
          var id = $('#form').attr('data-id');
          data = {'name': name, 'action':action, 'id':id}
        }
        $.ajax({
          url: "/admin/ajax/convert_tagsproduct",
          type: 'POST',
          data : data,
          success: function(data){
            $("input[name='url']").val(data);
          }
        });
    });

    $(".cus_btn_seo").click(function(){
      var name = $.trim($("input[name='name']").val());
      var intro = $.trim($("textarea[name='intro']").val());
      var title = $.trim($("input[name='title']").val());
      var description = $.trim($("textarea[name='description']").val());
      var keyword = $.trim($("input[name='keyword']").val());

      if(!isEmpty(name)){
        if(isEmpty(title)){
          $("input[name='title']").val(name);
        }
        if(isEmpty(description)){
          $("textarea[name='description']").val(intro);
        }
        if(isEmpty(keyword)){
          $("input[name='keyword']").val(name+', '+change_alias(name));
        }
      }else{
        return false;
      }

    });

})