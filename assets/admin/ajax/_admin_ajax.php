<?php
session_start();
$thisShop = $_SESSION['thisShop'];
define('IMG_PRODUCT', 'pict/');

// АВТОЗАГРУЗКА КЛАССОВ
define('DOCROOT', realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR);
set_include_path(DOCROOT);
spl_autoload_register(function ($class) {
    $pt = DOCROOT . str_replace('\\', '/', "classes\\".$class).'.php'; // если это класс
    if(!file_exists($pt)) {
        $pt = DOCROOT . str_replace('\\', '/', "controllers\\".$class).'.php'; // если это контроллер
        if(!file_exists($pt)) {
            $pt = DOCROOT . str_replace('\\', '/', "models\\".$class).'.php'; // если это модель
        }
    }
    include_once $pt;
});


function print_pre($arr){echo'<pre>';print_r($arr);echo'</pre>';}

$db = DataBase::getDB();
$category_arr = '';
$non_category = 0;
$sel_cat = array();

//сохранение формы
if(isset($_POST['table'])){
    saveForms($_POST);
}

function saveForms($post){
    $db = DataBase::getDB();
    $form = $post['table'];
    if($post['table'] != 'properties') {
        $post['status'] = (isset($post['status'])) ? 1 : 0;
    }
    unset($post['table']);
    if($post['id']>0) {
        // обновление таблицы
        $id = $post['id'];
        unset($post['id']);
        $query = 'UPDATE '.$form.' SET ';
        foreach ($post as $k => $v) {
            $query.=" $k = '$v',";
        }
        $query = substr($query,0,-1);
        $query.=" WHERE id=$id";
        $db->query($query);

    } else {
        // новая запись
        $id = $post['id'];
        unset($post['id']);
        $query = 'INSERT INTO '.$form.' (';
        $pr='';
        $zn='';
        foreach ($post as $k => $v) {
            $pr.="$k,";
            $zn.="'$v',";
        }
        $pr = substr($pr,0,-1);
        $zn = substr($zn,0,-1);
        $query.=$pr.") VALUES (".$zn.")";
        $db->query($query);
    }
}
// Сохранене форм товара
if(isset($_POST['SaveProduct'])) {
    $prod = new Product($_POST['id']);
    $prod->saveMain($_POST);
    $prod->grandphoto();
    echo $prod->id;
    unset($prod);
}

// Удаление записи
if(isset($_POST['delid'])) {
    $table = false;
    switch ($_POST['deltable']) {
        case 'shop_category':
            $table = new Shop_category($thisShop);
            break;
        case 'properties':
            $shop = new Shop($thisShop);
            $shop->delProperty($_POST['delid']);
            break;
    }
    if($table) $table->del($_POST['delid']);
}

//////////////////////////////////////////////// К А Т Е Г О Р И И
//  Вывод в таблицу строк категории
if(isset($_POST['getCategoryRow'])){
    $cat = new Shop_category($thisShop);
    $result = $cat->Tree(0);
    if(count($result>0))
        foreach ($result as $v) {
            echo'<tr><td style="cursor:pointer; padding-left: '.(($v["level"] * 20)+20).'px;" class="bigger-120" onclick="editcat('.$v["id"].')">'.$v["title"].'</td><td><div class=" btn-group"><a href="/admin/shop/product/?cat='.$v["id"].'" class="btn btn-xs btn-info"><i class="ace-icon fa fa-pencil "></i> Товары</a><button class="btn btn-xs btn-danger" onclick="delid('."'".shop_category."'".','.$v["id"].');"><i class="ace-icon fa fa-trash-o "></i> Удалить</button></div></td></tr>';
        }
    unset($cat);
}

//  Вывод в select строк категории
if(isset($_POST['getCategorySelect'])){
    $cat = new Shop_category($thisShop);
    $cat->block_tree = $_POST['blocktree'];
    $result = $cat->Tree(0);
    if(count($result>0))
        foreach ($result as $v) {
            if($v["id"] != $_POST['blocktree']){
                $sd = '';
                for ($i=0; $i < $v['level']; $i++) { 
                    $sd.= '&nbsp;&nbsp;';
                }
                echo'<option value="'.$v["id"].'">'.$sd.$v["title"].'</option>';
            }
        }
    unset($cat);
}

//// форирование списка категорий, в которых виден продукт с галочками
if(isset($_POST['view_category_list'])){
    $cats = new Shop_category($thisShop);
    $prod = new Product($_POST['view_category_list']);
    $list = $cats->Tree();
    if($list)
        foreach ($list as $v) {
            $chk = (in_array($v["id"], $prod->category)) ? 'checked' : '';
            $prb = ' ';
            for ($i=0; $i <$v['level'] ; $i++) { 
                $prb .= '&hellip;&nbsp;';
            }
            echo'<div class="checkbox">
                <label>
                    <input name="incat['.$v["id"].']" class="ace ace-checkbox-2" type="checkbox" '.$chk.' >
                    <span class="lbl">'.$prb.$v["title"].'</span>
                </label>
            </div>';
        }
    unset($cats);
    unset($prod);
}

//////////////////////////////////////////////// Т О В А Р Ы
// Вывод таблицы товаров
if(isset($_POST['displayProduct'])) {
    $products = new Product(0,$thisShop);
    $res = ($_POST['displayProduct'] > 0) ? $products->getProductsInCategory($_POST['displayProduct']) : $res = $products->allProducts();
    $result = array();
    if($res){
        foreach ($res as $v) {
            $result[] = array($v['id'],$v['title'],$v['views'],$v['img']);
        }
    }
    echo json_encode($result);
}































/**
 * Вывод дерева всех категорий в таблицу
 * @param Integer $parent_id - id-родителя
 * @param Integer $level - уровень вложености
 */
function outTree($parent_id, $level) {
    global $category_arr;
    if (isset($category_arr[$parent_id])) { //Если категория с таким parent_id существует
        $ug = ''; $lv = $level;
        while ( $lv > 0) {
            $ug.='&nbsp;&nbsp;&hellip;&nbsp;&nbsp;&nbsp;';
            $lv--;
        }
        foreach ($category_arr[$parent_id] as $value) { //Обходим ее
            $clrow = ($value['status'] == 0) ? 'style="background: #f3f2e3;"' : '';
            echo '
                <tr '.$clrow.'>
                    <td class="center"></td>
                    <td style="cursor: pointer;" onclick="editcat('.$value["id"].')">'.$ug.'
                        <a href="javascript:void(0)" class="blue2 bigger-120 show-details-btn">'.$value["title"].'</a>
                    </td>
                    <td><center>'.$value["products"].'</center></td>
                    <td>
                        <div class=" btn-group">
                            <a href="/admin/shop/product/?cat='.$value["id"].'" class="btn btn-xs btn-info"><i class="ace-icon fa fa-pencil "></i> Товары</a>
                            <button class="btn btn-xs btn-danger" onclick="delid('."'category'".','.$value["id"].');"><i class="ace-icon fa fa-trash-o "></i> Удалить</button>
                        </div>
                    </td>
                </tr>
            ';
            $level++; //Увеличиваем уровень вложености
            //Рекурсивно вызываем этот же метод, но с новым $parent_id и $level
            outTree($value["id"], $level);
            $level--; //Уменьшаем уровень вложености
        }
    }
}
/**
 * Вывод дерева категорий в select
 * @param Integer $parent_id - id-родителя
 * @param Integer $level - уровень вложености
 */
function outSelectTree($parent_id, $level) {
    global $category_arr;
    global $non_category;
    if (isset($category_arr[$parent_id])) { //Если категория с таким parent_id существует
        $ug = ''; $lv = $level;
        while ( $lv > 0) {
            $ug.='&nbsp;&nbsp;';
            $lv--;
        }
        foreach ($category_arr[$parent_id] as $value) { //Обходим ее
            echo '<option value="'.$value["id"].'">'.$ug.$value["title"].'</option>';
            $level++; //Увеличиваем уровень вложености
            //Рекурсивно вызываем этот же метод, но с новым $parent_id и $level
            if($non_category != $value["id"]) outSelectTree($value["id"], $level);
            $level--; //Уменьшаем уровень вложености
        }
    }
}
/**
 * Вывод дерева категорий в multi-select
**/
function outSelectTreeSpis($parent_id, $level) {
    global $category_arr;
    global $sel_cat;
    if (isset($category_arr[$parent_id])) { //Если категория с таким parent_id существует
        $ug = ' '; $lv = $level;
        while ( $lv > 0) {
            $ug.='&nbsp;&nbsp;';
            $lv--;
        }
        foreach ($category_arr[$parent_id] as $value) {
$chk = (in_array($value["id"],$sel_cat)) ? 'checked' : '';
echo'
<div class="checkbox">
    <label>
        <input name="incat['.$value["id"].']" class="ace ace-checkbox-2" type="checkbox" '.$chk.' >
        <span class="lbl">'.$ug.$value["title"].'</span>
    </label>
</div>';

//            echo '<option value="'.$value["id"].'">'.$ug.$value["title"].'</option>';
            $level++;
            outSelectTreeSpis($value["id"], $level);
            $level--;
        }
    }
}


/*
****************   Ф О Р М Ы   ********************
*/

if(isset($_POST['loadform'])) {
    // модаль изменения категории
    if($_POST['loadform'] == 'editcat'){
        $res=false;
        if($_POST['id'] > 0) {
            $cat = new Shop_category($thisShop);
            $res = $cat->getCategory($_POST['id']);
        }
        if(!$res) {
            $res['id'] = -1;
            $res['parent'] = 0;
            $res['status'] = 1;
            $res['title'] = '';
            $res['description'] = '';
            $res['image'] = '';
            $res['shop'] = $thisShop;
        }
        $chk = ($res['status'] > 0) ? 'checked' : '';
        echo '<form class="form-horizontal" id="validation-form" name="category">
    <input type="hidden" name="id" value="'.$res['id'].'"/>
    <input type="hidden" name="table" value="shop_category"/>
    <input type="hidden" id="s_parent" value="'.$res['parent'].'"/>
    <input type="hidden" name="shop" value="'.$res['shop'].'"/>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">Наименование:</label>
        <div class="col-xs-12 col-sm-5">
            <div class="clearfix">
                <input type="text" name="title" id="title" class="col-xs-12 col-sm-12" value="'.$res['title'].'" placeholder="Наименование категории" required/>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <label> Отображать

                <input id="skip-validation" name="status" type="checkbox" class="ace ace-switch ace-switch-4"  '.$chk.'/>
                <span class="lbl middle"></span>
            </label>
        </div>

    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="owner">Входит в :</label>
        <div class="col-xs-12 col-sm-8">
            <div class="clearfix">
                <select name="parent" id="parent" class="col-xs-12 col-sm-12"></select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="rem">Описание:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="clearfix">
                <textarea name="description" id="description" class="col-xs-12 col-sm-12" placeholder="Описание категории" rows="6">'.$res['description'].'</textarea>
                <script>CKEDITOR.replace("description");</script>
            </div>
        </div>
    </div>

    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="button" onclick="form_save_but();">
                <i class="ace-icon fa fa-check"></i>Сохранить
            </button>

            &nbsp; &nbsp; &nbsp;
            <button class="btn" type="reset" onclick='."dialog.modal('hide');".'>
                <i class="ace-icon fa fa-undo"></i>Отменить
            </button>
        </div>
    </div>
</form>';
    }

    // модаль изменения юзера в суперадминке
    if($_POST['loadform'] == 'edituser'){
        $user = new User($_POST['id']);
        echo '<form class="form-horizontal" id="validation-form" name="category">
        <input type="hidden" name="id" value="'.$user->id.'"/>
        <input type="hidden" name="table" value="users"/>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="title">Логин:</label>
            <div class="col-xs-12 col-sm-4">
                <div class="clearfix"><h4>'.$user->login.'</h4>
                </div>
            </div>
            <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="title">Email:</label>
            <div class="col-xs-12 col-sm-4">
                <div class="clearfix"><h4>'.$user->email.'</h4>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="owner">Статус:</label>
            <div class="col-xs-12 col-sm-8">
                <div class="clearfix">
                    <select name="parent" id="parent" class="col-xs-12 col-sm-12"></select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="rem">Описание:</label>
            <div class="col-xs-12 col-sm-8">
                <div class="clearfix">
                    <textarea name="description" id="description" class="col-xs-12 col-sm-12" placeholder="Описание категории" rows="6">'.$res['description'].'</textarea>
                    <script>CKEDITOR.replace("description");</script>
                </div>
            </div>
        </div>

        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="button" onclick="form_save_but();">
                    <i class="ace-icon fa fa-check"></i>Сохранить
                </button>

                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset" onclick='."dialog.modal('hide');".'>
                    <i class="ace-icon fa fa-undo"></i>Отменить
                </button>
            </div>
        </div>
    </form>';
    }

    // модаль свойства
    if($_POST['loadform'] == 'property_click'){
        $shop = new Shop($thisShop);
        $res = $shop->getProperty($_POST['id']);
        echo '
        <form class="form-horizontal" id="validation-form" name="properties">
        <input type="hidden" name="id" id="id" value="'.$res['id'].'" />
        <input type="hidden" name="table" value="properties"/>
        <input type="hidden" name="shop" value="'.$thisShop.'"/>
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">Наименование:</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" name="title" id="title" class="col-xs-12 col-sm-12" value="'.$res['title'].'" placeholder="Укажите наименование свойства" required/>
                    </div>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="button" onclick="form_save_but();">
                        <i class="ace-icon fa fa-check"></i>Сохранить
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick='."dialog.modal('hide');".'>
                        <i class="ace-icon fa fa-undo"></i>Отменить
                    </button>
                </div>
            </div>

        </form>
        ';
    }

    // загрузка содержмого вкладки свойств товара в карточке
    if($_POST['loadform'] == 'view_property'){
        $shop = new Shop($thisShop);
        $res = $shop->properties;
        unset($shop);
        echo '
            <div class="row">
                <div class="col-sm-4">
                    <div class="widget-box widget-color-blue2">
                        <div class="widget-header"><h4 class="widget-title lighter smaller">Свойства</h4></div>
                        <div class="widget-body"><div class="widget-main padding-8">';
        foreach ($res as $v) {
            echo '<button class="btn btn-white btn-block" onclick="widget_tabl('.$_POST['id'].','.$v['id'].','."'".$v['title']."'".');">'.$v['title'].'</button>';
        }
        echo'           </div></div>
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="widget-box widget-color-green">
                        <div class="widget-header"><h4 class="widget-title lighter smaller title-view-propety">Значения</h4>
                            <div id="addrow_propery" style="display:none;float: right;margin: 4px;">
                                <button type="button" class="btn btn-primary" id="saverow_but" onclick="saverow_propery();" style="display:none">
                                    <i class="fa fa-floppy-o fa-lg"></i> Сохранить
                                </button>
                                <button type="button" class="btn btn-success" onclick="addrow_propery();">
                                    <i class="fa fa-plus-square-o fa-lg"></i> Добавить значение
                                </button>
                            </div>
                        </div>
                        <form method="post" id="properties_form" action="/admin/shop/pay">
                            <input type="hidden" id="thisproperties" name="thisproperties" value="">
                            <input type="hidden" id="thisproduct" name="thisproduct" value="'.$_POST['id'].'">
                            <div class="widget-body">
                            <table id="property-table" class="table table-bordered table-hover" width="100%"><thead><tr><th  width="60%" class="detail-col">Значение</th><th width="20%">Разница в цене</th><th width="20%">Вид разницы</th><th width="10%"></th></tr></thead>
                                <tbody id="widget_tabl">
                                </tbody>
                            </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>';
    }

    // загрузка содержмого конкретного свойства на вкладке свойств товара в карточке
    if($_POST['loadform'] == 'view_property_set') {
        $prod = new Product($_POST['product']);
        $res = $prod->view_property_set($_POST['id']);
        unset($prod);

        foreach ($res as $v) {
            $sel=($v['proc']=='руб') ? '<option value="руб" selected>руб</option><option value="%" >%</option>' : '<option value="руб">руб</option><option value="%" selected>%</option>';
            echo'<tr id="proprowid'.$v['id'].'">
            <td><input type="text" id="val['.$v['id'].']" name="val['.$v['id'].']" value="'.$v['title'].'" class="prop-row form-control" required="required"></td>
            <td><input type="number" id="price['.$v['id'].']" name="price['.$v['id'].']" value="'.$v['add_price'].'" class="prop-row form-control"></td>
            <td><select id="proc['.$v['id'].']" name="proc['.$v['id'].']" class="prop-row form-control">'.$sel.'</select></td>
            <td src=""><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
            </tr>';
        }
    }

}



// новый артикул
if(isset($_POST['newarticle'])){
    $res = $db->selectCell('SELECT (MAX(id)+1) FROM products');
    $res = str_pad($res, 10, "0", STR_PAD_LEFT);
    echo 'SH'.$thisShop.'-'.$res;
}


// Удаление товара
if(isset($_POST['delproduct'])){
    $product = new Product($_POST['delproduct']);
    if($thisShop == $product->shop) echo $product->delete();
    unset($product);
}

// сохранение формы свойств товара
if(isset($_POST['saverow_propery'])){
    $product = new Product($_POST['thisproduct']);
    $product->saverow_propery($_POST);
    unset($product);
}

// отобразить изображения товара
if(isset($_POST['view_product_images'])){
    $prod = new Product($_POST['view_product_images']);
    $res = $prod->getAllImages();
    if($res)
        foreach ($res as $v) {
            echo'<li>
                    <img width="150" alt="Изображение не найдено" src="'.$v['image'].'" />
                    <div class="tools tools-top"><a href="javascript:void(0);" onclick="delproductimage('."'".$v['image']."'".');"><i class="ace-icon fa fa-times red"></i></a></div>
                </li>';
        }
    echo'';
}
// удалить картинку из товара
if(isset($_POST['delproductimage'])){
    $prod = new Product($_POST['product']);
    $prod->delImage($_POST['delproductimage']);
    unset($prod);
}


// формирование формы класса Forms
if(isset($_POST['getDinamicForm'])){
    $frm = new Forms($_POST['getDinamicForm']);
    $frm->where = 'shop='.$thisShop;
    $res = $frm->getForm($_POST['id']);
    echo '
        <form class="form-horizontal" id="validation-form" name="'.$_POST['getDinamicForm'].'">
        <input type="hidden" name="id" id="id" value="'.$res['id'].'" />
        <input type="hidden" name="table" value="'.$_POST['getDinamicForm'].'"/>
        <input type="hidden" name="shop" value="'.$thisShop.'"/>';
    foreach ($frm->colums as $v) { if($v[3]) {
        $r = ($v[4]) ? ' required' : '';
        echo '
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="title">'.$v[2].':</label>
                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="text" name="'.$v[1].'" id="'.$v[1].'" class="col-xs-12 col-sm-12" value="'.$res[$v[1]].'"'.$r.'/>
                    </div>
                </div>
            </div>';
        }
    }
    echo '  <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="button" onclick="dinamic_save_but();">
                        <i class="ace-icon fa fa-check"></i>Сохранить
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset" onclick='."dialog.modal('hide');".'>
                        <i class="ace-icon fa fa-undo"></i>Отменить
                    </button>
                </div>
            </div>
        </form>
        ';
}



?>
