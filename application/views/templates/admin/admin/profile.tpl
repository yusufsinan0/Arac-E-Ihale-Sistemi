<section class="content">
	<form method="post" id="form" action="{"admin/profileSave/{$info->id}"|base_url}">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Hesap Ayarlarım</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="inputCFirstName">Adınız</label>
								<input type="text" id="inputCFirstName" name="name" class="form-control"
									   value="{$info->name}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="inputCLastName">Kullanıcı Adı</label>
								<input type="text" id="inputCLastName" name="username" class="form-control"
									   value="{$info->username}" required>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="inputSlug">E-Posta</label>
								<input type="text" id="inputEmail" name="mail" class="form-control"
									   value="{$info->mail}" required>
							</div>
							<div class="form-group col-md-6">
								<label for="inputPrice">Parola</label>
								<input type="password" id="inputPassword" name="password" class="form-control"
									   placeholder="Değiştirmek istiyorsanız girin">
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
				<a href="{"admin"|base_url}" class="btn btn-secondary">Cancel</a>
				<input type="hidden" name="id" value="{$info->id}">
				<button type="submit" class="btn btn-success float-right">Kaydet</button>
			</div>
		</div>
		<br>
	</form>
</section>
