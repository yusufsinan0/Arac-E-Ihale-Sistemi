<section class="content">
	<div class="container-fluid">
		<form method="GET" action="">
			<div class="row align-items-end">
				<div class="col-md-3 form-group">
					<label for="startdate">Başlangıç Tarihi</label>
					<input type="date" id="startdate" class="form-control" name="startdate" max="{$dates.max}" value="{$dates.start}">
				</div>
				<div class="col-md-3 form-group">
					<label for="enddate">Bitiş Tarihi</label>
					<input type="date" id="enddate" name="enddate" max="{$dates.max}" class="form-control" value="{$dates.end}">
				</div>
				<div class="col-md-4 form-group">
					<button type="submit" class="btn btn-primary mr-2">Gönder</button>

					<a class="btn btn-success" href="{"reports/sms/excel/sms-list?startdate={$dates.start}&enddate={$dates.end}"|base_url}"><i class="fa fa-file-excel"></i> Excel'e Aktar</a>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Müşteri Giriş Kayıtları</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="client-logs" class="table table-striped table-bordered" data-startdate="{$dates.start}" data-enddate="{$dates.end}">
							<thead>
							<tr>
								<th>#</th>
								<th>Müşteri İsmi</th>
								<th>IP Adresi</th>
								<th>Port</th>
								<th>Giriş Tarihi</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
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
