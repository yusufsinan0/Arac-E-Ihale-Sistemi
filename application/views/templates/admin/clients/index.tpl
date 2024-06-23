<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<a href="{"admin/clients/add"|base_url}" class="btn btn-success btn-sm float-right"><i class="fa fa-plus"></i> Oluştur</a>
						<h3 class="card-title">Müşteriler</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="categories" class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>#</th>
								<th>Firma</th>
								<th>E-Posta</th>
								<th>Başlıklar</th>
								<th>Sanal Numaralar</th>
								<th>Durumu</th>
								<th>İşlemler</th>
							</tr>
							</thead>
							<tbody>
								{foreach $clients as $item}
									<tr>
										<td>{$item->id}</td>
										<td>{$item->companyname}</td>
										<td>{$item->email}</td>
										<td>{implode(",", array_column($item->accounts, "titles"))}</td>
										<td>{implode(",", array_column($item->accounts, "virtualnumber"))}</td>
										<td>
											{if $item->active == 1}
												<span class="badge badge-success"><i class="fa fa-check"></i> Aktif</span>
											{else}
												<span class="badge badge-danger"><i class="fa fa-times"></i> Pasif</span>
											{/if}
										</td>
										<td>
											<a href="{"admin/clients/view/{$item->id}"|base_url}"
											   class="btn btn-sm btn-block btn-primary">
												<i class="fa fa-list"></i> Görüntüle
											</a>
											<a href="{"admin/clients/login/{$item->id}"|base_url}" target="_blank"
											   class="btn btn-sm btn-block btn-info">
												<i class="fa fa-lock-open"></i> Müşteri Hesabına Giriş Yap
											</a>
											<a href="{"admin/clients/delete/{$item->id}"|base_url}"
											   class="btn btn-sm btn-block btn-danger"
											   onclick="return confirm('Bu işlemi yapmak istediğinize emin misiniz? Geri dönüşü olmayacaktır.')">
												<i class="fa fa-trash"></i> Sil
											</a>
                                            {if $item->active == 1}
												<a href="{"admin/clients/toggle/{$item->id}"|base_url}" class="btn btn-block btn-sm btn-secondary"><i class="fa fa-times"></i> Pasifleştir</a>
                                            {else}
												<a href="{"admin/clients/toggle/{$item->id}"|base_url}" class="btn btn-block btn-sm btn-success"><i class="fa fa-check"></i> Aktifleştir</a>
                                            {/if}
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
