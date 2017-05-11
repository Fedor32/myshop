<?php extract($this->data);?>

<div class="tabbable tabs-left">
	<ul class="nav nav-tabs" id="myTab3">
		<li class="active">
			<a data-toggle="tab" href="#home3">
				<i class="pink ace-icon fa fa-users bigger-110"></i>
				Пользователи
			</a>
		</li>

		<li>
			<a data-toggle="tab" href="#profile3">
				<i class="blue ace-icon fa fa-user bigger-110"></i>
				Profile
			</a>
		</li>

		<li>
			<a data-toggle="tab" href="#dropdown13">
				<i class="ace-icon fa fa-rocket"></i>
				More
			</a>
		</li>
	</ul>

	<div class="tab-content">
		<div id="home3" class="tab-pane in active">
			<div class="clearfix">
				<div class="pull-right tableTools-container"></div>
			</div>
				<div class="table-header">Пользователи</div>
					<div>
						<table id="users-table" class="table table-striped table-bordered table-hover">
							<thead><tr><th>Логин</th><th>mail</th><th>Статус</th><th>Уровень</th></tr></thead>
							<tbody id="users_list">
<? foreach ($users as $v) { ?>
	<tr>
		<td><?=$v['login'];?></td>
		<td><?=$v['email'];?></td>
		<td><?=$v['status'];?></td>
		<td><?=$v['role'];?></td>
	</tr>
<?	}	?>


								
							</tbody>
						</table>
					</div>

		</div>










		<div id="profile3" class="tab-pane">
			<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
			<p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
		</div>

		<div id="dropdown13" class="tab-pane">
			<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
			<p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
		</div>
	</div>
</div>




<script type="text/javascript">
	jQuery(function($) {
		load_superadminforms();
		$('#users-table').DataTable();
	} );
			
</script>