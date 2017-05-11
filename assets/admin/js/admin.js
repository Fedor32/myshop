var adminajax = '/assets/admin/ajax/admin_ajax.php';
var dialog;


// нажатие кнопки SUBMIT в форме
$('.submit').click(function() {
    if( validform( $('.vform') ) ) {
//        CKupdate();
        var mes = $('.vform').serialize();
        $.ajax({type:'POST',url:adminajax,data: mes,cache: false,
            success:function(data){
                switch($('.vform').attr('name')) {
                    case 'loginform':
                        if(data == '1') {
                            location.reload();
                        } else {
                            $.gritter.add({title: 'Контроль доступа!',time: 3000,text: 'Пользователь с указанными данными не найден или заблокирован!',class_name: 'gritter-error'});
                        }
                        break;
                }
            }
        });
    } else {
        $.gritter.add({title: 'Ошибка!',time: 3000,text: 'Некоторые поля заполены некорректно!',class_name: 'gritter-error'});
    };
});

// валидация формы

function validform(form) {
    var err=true;
    var re;
    var valid;

    $(form).find ('input, textearea, select').each(function(nf,inputData){
        var l_err = true;
        $(inputData).css('border-color','#D5D5D5');
        // Проверка обязательных полей
        if(($(inputData).attr('required') == 'required') && ($(inputData).val().length < 1)) {
            l_err = false;
        }
        // Проверка email
        if($(inputData).attr('type') == 'email') {
            re = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;
            if(! re.test($(inputData).val())) {
                l_err = false;
            }
        }
        // Проверка телефона
        if($(inputData).attr('type') == 'tel') {
            re = /^[\d-]+$/;
            if(! re.test($(inputData).val())) {
                l_err = false;
            }
        }
        // Проверка числа
        if($(inputData).attr('type') == 'number') {
            re = /^[\d-]+$/;
            if(! re.test($(inputData).val())) {
                l_err = false;
            }
        }


        if(!l_err){
            err = false;
            $(inputData).css('border-color','red');
        }
    });
    return err;
}

// нажатие кнопки восстановления пароля в админке
$('.btn_respas').click(function(){
    var re = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;
    var myMail = $('#re_email').val();
    var valid = re.test(myMail);
    if(valid) {
        $('#forgot-box-main').html('<h4 class="header red lighter bigger"><center>На указанный Вами email отправлена инструкция для восстановления пароля</center></h4>');
    } else {
        $.gritter.add({title: 'Неверный email!',time: 2000,text: '',class_name: 'gritter-error'});
        $('#re_email').css('border-color','red');
    }
});


// 
// Загрузка странички суперадмина
// 
function load_superadminforms(){
    
}

//////////////////////////////////////////////////////////////////////
//
//  Страница категории в админке

function editcat(id) {
    dialog = bootbox.dialog({
        title: 'Категория',
        size: 'large',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Загрузка...</p>'
    });
    $.ajax({type:'POST',url:adminajax,data:'loadform=editcat&id='+id,cache: false,
        success:function(data){
            dialog.find('.bootbox-body').html(data);
            var mes = 'getCategorySelect='+id+'&blocktree='+id;
            $.ajax({type:'POST',url:adminajax,data: mes,cache: false,
                success:function(data){
                    $('#parent').html('<option value=0>Категория верхнего уровня</option>'+data);
                    var parent = $('#s_parent').val();
                    $("#parent [value='"+parent+"']").attr("selected", "selected");
                }
            });
        }
    });
}

function loadcategory(shopid){
    var mes = 'getCategoryRow=rows&shop='+shopid;
    $('#main-body-table').html('<tr><td colspan="4"><center><img src="/assets/admin/css/loader.gif" alt=""> Загрузка...</center></td></tr>');
    $.ajax({type:'POST',url:adminajax,data: mes,cache: false,
        success:function(data){
            $('#main-body-table').html(data);
        }
    });        
}


//////////////////////////////////////////////////////////////////////













//сохранение формы
$('#shop_setting_save_but').click(function(){
    form_save_but();
});

// сохранение формы категории
function form_save_category(){
    CKupdate();
    if( validform( $('#validation-form') ) ) {
        var mes = $('#validation-form').serialize();
       $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: mes,cache: false,
            success:function(data){
                $.gritter.add({title: 'Сведения о категории сохранены!',time: 2000,text: '',class_name: 'gritter-success gritter-light'});
            }
        });
    } else {
        $.gritter.add({title: 'Данные не сохранены!',time: 3000,text: 'В выделенных красным цветом полях формы находятся некорректные данные!',class_name: 'gritter-error'});
    };
}

//сохранение формы товара
$('[id ^= shop_product_save_but]').click(function(){
    CKupdate();
    if( validform( $('#validation-form') ) ) {
        var mes = $('#validation-form').serialize();
        $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: mes+'&SaveProduct=mainform',cache: false,
            success:function(data){
                $.gritter.add({title: 'Сведения о товаре сохранены!',time: 2000,text: '',class_name: 'gritter-success gritter-light'});
                setTimeout('location.href="/admin/shop/product/'+data+'"',1000);
            }
        });
    } else {
        $.gritter.add({title: 'Данные не сохранены!',time: 3000,text: 'В выделенных красным цветом полях формы находятся некорректные данные!',class_name: 'gritter-error'});
    };
});

function form_save_but() {
    if( validform( $('#validation-form') ) ) {
        CKupdate();
        var mes = $('#validation-form').serialize();
        $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: mes,cache: false,
            success:function(data){
                $.gritter.add({title: 'Данные успешно сохранены!',time: 2000,text: '',class_name: 'gritter-success gritter-light'});
                if($('#validation-form').attr('name') == 'category'){
                    dialog.modal('hide');
                    loadcategory();
                }
                if($('#validation-form').attr('name') == 'properties'){
                    dialog.modal('hide');
                    location.reload();
                }
            }
        });
    } else {
        $.gritter.add({title: 'Данные не сохранены!',time: 3000,text: 'В выделенных красным цветом полях формы находятся некорректные данные!',class_name: 'gritter-error'});
    };
}
// сохранение динамической формы
function dinamic_save_but() {
    if( validform( $('#validation-form') ) ) {
        CKupdate();
        var mes = $('#validation-form').serialize();
        $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: mes,cache: false,
            success:function(data){
                $.gritter.add({title: 'Данные успешно сохранены!',time: 2000,text: '',class_name: 'gritter-success gritter-light'});
                dialog.modal('hide');
                location.reload();
            }
        });
    } else {
        $.gritter.add({title: 'Данные не сохранены!',time: 3000,text: 'В выделенных красным цветом полях формы находятся некорректные данные!',class_name: 'gritter-error'});
    };
}
// Удаление записи из таблиц
function delid(table,id){
    var mes = 'Удалить выбранную запись?';
    if(table == 'shop_category') mes='Будут удалены <br>так же все вложенные категории!!!<br><b>Удалить?</b>';
    if(table == 'properties') mes='Будут удалены так же все привязки <br>к этому свойтсву у всех товаров!!!<br><b>Удалить?</b>';
    bootbox.confirm({
        message: '<center>'+mes+'</center>',size: 'small',
            buttons: {
              cancel: {
                 label: "Отмена",
                 className: "btn-sm",
              },
              confirm: {
                 label: " ДА ",
                 className: "btn-primary btn-sm",
              }
            },
            callback: function(result) {
                if(result) {
                    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'delid='+id+'&deltable='+table,cache: false});

                    if(table == 'category') loadcategory();

                    if(table == 'properties') location.reload();
                }
            }
        });
}




// формрование таблцы товаров
function displayProducts(parent){
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'displayProduct='+parent,cache: false,
        success:function(data){
            data = $.parseJSON(data);
            var t=$('#dynamic-table');
            var tb = $('#dynamic-table').DataTable();
            tb.rows().remove().draw();
            $.each(data,function(index,value){
                tb.row.add( [
                    '<label class="pos-rel"><input type="checkbox" id="checkbox'+data[index][0]+'" class="ace" /><span class="lbl"></span></label>',
                    '<center><a href="/admin/shop/product/'+data[index][0]+'"><img src="'+data[index][3]+'" height="60px"></a></center>',
                    '<a href="/admin/shop/product/'+data[index][0]+'" class="blue2 bigger-130 show-details-btn">'+data[index][1]+'</a>',
                    '<div class="blue2 bigger-130"><center>'+data[index][2]+'</center></div>',
                    '<center><button class="btn btn-xs btn-danger" onclick="delproduct('+data[index][0]+');"><i class="ace-icon fa fa-trash-o"></i> Удалить</button></center>'
                ] ).draw( false );
            });
        }
    });
}

// форирование списка категорий, в которых виден продукт с галочками
function view_category_list(id,obj){
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'view_category_list='+id,cache: false,
        success:function(data){
            $('#'+obj+'').html(data);
        }
    });
}

// случайный артикул
$('#newarticlebut').click(function(){
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'newarticle=1',cache: false,
        success:function(data){
            $('#article').val(data);
        }
    });
});

// форма изенения свойства товара из таблицы
function property_click(id){
    dialog = bootbox.dialog({title: 'Свойство товара',message: '<p><i class="fa fa-spin fa-spinner"></i> Загрузка...</p>'});
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'loadform=property_click&id='+id,cache: false,
        success:function(data){
          dialog.find('.bootbox-body').html(data);
        }
    });
}

// загрузка содержмого вкладки свойств товара в карточке
// приходит id товара  и id объекта куда вставлять html
function view_property(id,obj) {
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'loadform=view_property&id='+id,cache: false,
        success:function(data){
          $('#'+obj).html(data);
        }
    });
}
// Отметка изменения полей формы свойств
var input_change_prop = function () {$('#saverow_but').css('display','inline');}
// действия по кнопке удаления строки таблцы свойств
var ddel_row_prop = function() {$(this).parent('tr').remove();$('#saverow_but').css('display','inline');}


// загрузка содержмого конкретного свойства на вкладке свойств товара в карточке
// приходит id продукта и id свойства
function widget_tabl(product,id,caption) {
    if($('#saverow_but').css('display') == 'inline-block') {
        bootbox.confirm("Выйти без сохранения?", function(result){ if (result!=false) {widget_tabl_proc(product,id,caption); }});
    } else {widget_tabl_proc(product,id,caption);}
}
function widget_tabl_proc(product,id,caption){
    $('#widget_tabl').html('<tr><td colspan="4"><center><img src="/assets/admin/css/loader.gif" alt=""> Загрузка...</center></td></tr>');
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'loadform=view_property_set&product='+product+'&id='+id,cache: false,
        success:function(data){
            $('.title-view-propety').html(caption);
            $('#widget_tabl').html(data);
            $('#addrow_propery').css('display','block');
            $('.prop-row').bind('change',input_change_prop);
            $('#property-table [src]').bind('click', ddel_row_prop);
            $('#saverow_but').css('display','none');
            $('#thisproperties').val(id);
        }
    });   
}

//*********************************************
// Удаление товара
function delproduct(id){
     bootbox.confirm("Будут удалены все сведения о товаре! Удалить?", function(result){ 
        if (result!=false) {
            $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'delproduct='+id,cache: false,
                success:function(data){ location.reload();}
            });   
        }
    });
  
}
// добавление строки в таблицу свойств товара
function addrow_propery() {
    $('#property-table > tbody:last').append('<tr><td><input type="text" name="valn[]" value="" class="prop-row form-control" required="required"></td><td><input type="number" name="pricen[]" value="0" class="prop-row form-control"></td><td><select name="procn[]" class="prop-row form-control"><option value="руб" selected>руб</option><option value="%" >%</option></select></td><td src=""><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td></tr>');
    $('.prop-row').unbind('change',input_change_prop);
    $('.prop-row').bind('change',input_change_prop);
    $('#property-table [src]').unbind('click', ddel_row_prop);
    $('#property-table [src]').bind('click', ddel_row_prop);

}
// сохранение формы свойств товара
function saverow_propery() {
    if( validform( $('#properties_form') ) ) {
        $('#saverow_but').css('display','none');
        mes = $('#properties_form').serialize();
        $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: mes+'&saverow_propery=1',cache: false,
            success:function(data){}
        });
    } else {
        $.gritter.add({title: 'Данные не сохранены!',time: 3000,text: 'В выделенных красным цветом полях формы находятся некорректные данные!',class_name: 'gritter-error'});
    };
}
// удаление строки в таблицу свойств товара
function delrow_prop(id) {
    $('#proprowid'+id).remove();
}



// Отметить все чекбоксы в таблице
function checkall(status){$("[id^='checkbox']").each(function(){$(this).prop('checked', status);});}
// Удаление отмеченных продуктов
$('#delselectproduct').on('click',function(){
    var prod = [];
    $("[id^='checkbox']").each(function(){
        if($(this).prop('checked')){prod.push($(this).attr('id').replace(/[^0-9]/g,''));}
    }); 
    if(prod.length == 0) {
        $.gritter.add({title: 'Внимание!',time: 3000,text: 'Нет выделенных товаров для удаления!',class_name: 'gritter-warning'});
    }else{
        bootbox.confirm("Будут удалены все сведения о выделенных товарах! Продолжить удаление?", function(result){ 
        if (result!=false) {
            for (var i=0, len=prod.length; i<len; i++) {
                $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'delproduct='+prod[i],cache: false
                });  
            }
            location.reload();
        }
    });
    }
});
        
// отобразить изображения товара
function view_product_images(product,obj){
    $.ajax({type:'POST',url:'/assets/admin/ajax/admin_ajax.php',data: 'view_product_images='+product,cache: false,
        success:function(data){ 
            $('#'+obj).html(data);}
    });  
}




function CKupdate() {
    for (instance in CKEDITOR.instances)
        CKEDITOR.instances[instance].updateElement();
}

 
function alertObj(obj) { 
    var str = ""; 
    for(k in obj) { 
        str += k+": "+ obj[k]+"\r\n"; 
    } 
    alert(str); 
} 