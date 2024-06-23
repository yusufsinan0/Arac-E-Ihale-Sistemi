<!DOCTYPE html>
<html lang="en">

{include file="{$templateType}/partials/head.tpl" scope="root"}

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
	<!-- Preloader -->
	<div class="preloader flex-column justify-content-center align-items-center">
		<img class="animation__shake" src="{$logoUrl}" alt="{$companyName}" height="60" width="60">
	</div>

	<!-- Navbar -->
    {include file="{$templateType}/partials/navbar.tpl" scope="root"}
	<!-- /.navbar -->

	<!-- Main Sidebar Container -->
    {include file="{$templateType}/partials/sidebar.tpl" scope="root"}

	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
        {include file="{$templateType}/partials/pageheader.tpl" scope="root"}
		<!-- /.content-header -->
        {if $smarty.session.flash_message}
			<div class="alertboxes"
				 data-alert-title="{if $smarty.session.flash_message.type == 'success'}İşlem Başarılı{else}İşlem Başarısız{/if}"
				 data-alert-type="{if $smarty.session.flash_message.type == 'success'}success{else}error{/if}"
				 data-alert-message="{$smarty.session.flash_message.message}">
			</div>
        {/if}

		<!-- Main content -->
        {include file="{$templateType}/{$contentTpl}" scope=root}
		<!-- /.content -->
	</div>

    {include file="{$templateType}/partials/footer.tpl" scope="root"}
</div>
</body>

</html>
