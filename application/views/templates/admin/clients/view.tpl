<section class="content">
	<form method="post" id="form" action="{"admin/clients/save/edit"|base_url}">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Müşteri Bilgileri #{$info->id}</h3>

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
								<input type="text" id="input-companyname" name="companyname" class="form-control" value="{$info->companyname}" placeholder="Firma Unvanı" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-staff-name">Yetkili Kişi</label>
								<input type="text" id="input-staff-name" name="staffname" class="form-control" placeholder="Yetkili İsmi" value="{$info->staffname}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-email">E-Posta Adresi</label>
								<input type="email" id="input-email" name="email" class="form-control" placeholder="E-posta Adresi" value="{$info->email}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-password">Parola</label>
								<input type="password" id="input-password" name="password" class="form-control" placeholder="Değiştirmek İstiyorsanız Girin">
							</div>
							<div class="form-group col-md-4">
								<label for="input-company-phone">Firma Telefon Numarası</label>
								<input type="tel" id="input-company-phone" name="company_phone" class="form-control" placeholder="Firma Telefon Numarası" value="{$info->company_phone}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="input-staff-phone">Yetkili Telefon Numarası</label>
								<input type="tel" id="input-staff-phone" name="staff_phone" class="form-control" placeholder="Yetkili GSM" value="{substr($info->staff_phone, 1)}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="input-commercial-phone">Kurumsal Telefon Numarası</label>
								<input type="tel" id="input-commercial-phone" name="commercial_phone" class="form-control" placeholder="Kurumsal Telefon Numarası" value="{$info->commercial_phone}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-tax-office">Vergi Dairesi</label>
								<input type="text" id="input-tax-office" name="taxoffice" class="form-control" value="{$info->taxoffice}" placeholder="Vergi Dairesi" required>
							</div>
							<div class="form-group col-md-6">
								<label for="input-tax-number">Vergi Numarası</label>
								<input type="text" id="input-tax-number" name="taxnumber" class="form-control" maxlength="10" minlength="10" placeholder="Vergi Numarası" value="{$info->taxnumber}" required>
							</div>
							<div class="form-group col-md-12">
								<label for="input-invoice-address">Fatura Adresi</label>
								<textarea cols="3" class="form-control" name="invoice_address" id="input-invoice-address" placeholder="Fatura Adresi">{$info->invoice_address}</textarea>
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
				<input type="hidden" name="id" value="{$info->id}">
				<button type="button" data-toggle="modal" data-target="#add-sms-account" class="btn btn-info">SMS Hesabı Ekle</button>
				<button type="submit" class="btn btn-success float-right">Kaydet</button>
			</div>
		</div>
		<br>
	</form>
</section>
