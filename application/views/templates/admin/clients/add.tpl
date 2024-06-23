<section class="content">
	<form method="post" id="form" action="{"admin/clients/save/add"|base_url}">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Müşteri Oluştur</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="input-companyname">Firma Unvanı</label>
								<input type="text" id="input-companyname" name="companyname" class="form-control" value="{$smarty.session.postdata["companyname"]}" placeholder="Firma Unvanı" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-staff-name">Yetkili Kişi</label>
								<input type="text" id="input-staff-name" name="staffname" class="form-control" placeholder="Yetkili İsmi" value="{$smarty.session.postdata["staffname"]}" required>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="input-email">E-Posta Adresi</label>
								<input type="email" id="input-email" name="email" class="form-control" placeholder="E-posta Adresi" value="{$smarty.session.postdata["email"]}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-password">Parola</label>
								<input type="password" id="input-password" name="password" class="form-control" placeholder="Parola (Giriş İçin)" value="{$smarty.session.postdata["password"]}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-password-2">Parola (Tekrar)</label>
								<input type="password" id="input-password-2" name="password2" class="form-control" placeholder="Parola (Tekrar)" value="{$smarty.session.postdata["password2"]}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="input-staff-phone">Yetkili Telefon Numarası</label>
								<input type="tel" id="input-staff-phone" name="phone" class="form-control" placeholder="Yetkili GSM" value="{substr($smarty.session.postdata["phone"], 1)}" required>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<a href="{"admin/clients"|base_url}" class="btn btn-secondary">Cancel</a>
				<button type="submit" class="btn btn-success float-right">Kaydet</button>
			</div>
		</div>
		<br>
	</form>
</section>
