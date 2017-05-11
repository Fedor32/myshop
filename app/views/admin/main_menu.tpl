<?php  
$mmenu = array('admin/' => array('name'=>'Главная','icon'=>'tachometer','menu'=>array()));

$men = Model::getUserSection($user->id);
if($men)
    foreach ($men as $v) {
        $mmenu['admin/'.$v['section']] = array('name'=>$v['title'],'icon'=>$v['icon'],'menu'=>array());
    }
if(isset($mmenu['admin/shop'])) $mmenu['admin/shop']['menu'] = array(
        'admin/shop/cat' => array('name'=>'Категории','icon'=>'folder-open'),
        'admin/shop/product' => array('name'=>'Товары','icon'=>'cubes'),
        'admin/shop/prop' => array('name'=>'Свойства товаров','icon'=>'address-book-o'),
        'admin/shop/vendor' => array('name'=>'Производители','icon'=>'university'),
        'admin/shop/price' => array('name'=>'Изменение цен','icon'=>'rub'),
//        'admin/shop/pay' => array('name'=>'Способы оплаты','icon'=>'cc-visa'),
//        'admin/shop/delivery' => array('name'=>'Доставка','icon'=>'truck'),
//        'admin/shop/sale' => array('name'=>'Скидки','icon'=>'percent'),
        'admin/shop/setting' => array('name'=>'Настройки','icon'=>'cogs'),
    );
if($user->role != 9) { // super user
    unset($mmenu['admin/superadmin']);
}

$act = ($this->action == 'index') ? '' : $this->action;
$actp = (isset($this->params[0])) ? $this->params[0] : '';
foreach ($mmenu as $key => $value) {
    if(count($value['menu']) == 0) { // одиночный пункт меню
    $pars = explode("/", $key);
    $ac = ((isset($pars[1]))&&($pars[1] == $act)) ? 'active' : '';
   ?>
    <li class="<?php echo $ac; ?>">
        <a href="<?=MAINDIR;?>/<?php echo $key;?>">
            <i class="menu-icon fa fa-<?php echo $value['icon'];?>"></i>
            <span class="menu-text"> <?php echo $value['name'];?> </span>
        </a>
        <b class="arrow"></b>
    </li>
   <? } else { // Группа пунктов 
        $pars = explode("/", $key);
        $ac = ((isset($pars[1]))&&($pars[1] == $act)) ? 'active open' : '';
    ?>
    <li class="<?php echo $ac; ?>">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-<?php echo $value['icon'];?>"></i>
            <span class="menu-text"> <?php echo $value['name'];?> </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <?php foreach ($value['menu'] as $k => $v) { 
                    $pars = explode("/", $k);
                    $ac = ((isset($pars[2]))&&($pars[2] == $actp)) ? 'active' : '';
                ?>
                <li class="<?php echo $ac; ?>">
                    <a href="<?=MAINDIR;?>/<?php echo $k;?>">
                        <i class=" fa fa-<?php echo $v['icon'];?>"></i>
                        <?php echo $v['name'];?>
                    </a>

                    <b class="arrow"></b>
                </li>
            <?
            } ?>
        </ul>
    </li>
   <? }
}


?>