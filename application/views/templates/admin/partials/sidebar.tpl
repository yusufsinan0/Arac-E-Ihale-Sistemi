<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index3.html" class="brand-link">
		<img src="{$logoUrl}" alt="{$companyName}" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">{$companyName}</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="https://www.gravatar.com/avatar/{md5($smarty.session.usermail)}?s=200" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="{"admin/profile"|base_url}" class="d-block">{$smarty.session.user_name}</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
					 with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="{base_url("admin")}" class="nav-link {if $contentTpl == "index.tpl"}active{/if}">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Ana Sayfa
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{base_url("admin/clients")}" class="nav-link {if $contentTpl == "clients/index.tpl"}active{/if}">
						<i class="nav-icon fas fa-users"></i>
						<p>
							Müşteriler
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{base_url("admin/settings")}" class="nav-link {if $contentTpl == "settings.tpl"}active{/if}">
						<i class="nav-icon fas fa-cogs"></i>
						<p>
							Ayarlar
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{base_url("admin/logs")}" class="nav-link {if $contentTpl == "logs.tpl"}active{/if}">
						<i class="nav-icon fas fa-copy"></i>
						<p>
							Sistem Günlüğü
						</p>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>
