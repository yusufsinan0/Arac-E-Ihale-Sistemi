<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">{$pageTitle}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					{if $parentPage}
						<li class="breadcrumb-item"><a href="{$parentPage["link"]}">{$parentPage["name"]}</a></li>
                    {/if}
					<li class="breadcrumb-item active">{$pageTitle}</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
