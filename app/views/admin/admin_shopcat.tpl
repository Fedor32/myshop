<?php extract($this->data);?>

<div class="row">
    <div class="col-xs-12">
        <table id="simple-table" class="table  table-bordered table-hover">
        <thead><tr><th><center>Наименование</center></th><th width="161px">
                <button class="btn btn-primary btn-xs btn-block" onclick="editcat(-1);"><i class="fa fa-plus-square-o fa-lg"></i> Новая категория</button></th></tr></thead>
            <tbody id="main-body-table"></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    $(function(){
        loadcategory(<?=$shop->id;?>);
    });
</script>
