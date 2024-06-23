<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Giriş Yap | {$companyName}</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{"/application/views/templates/admin/partials/assets/plugins/fontawesome-free/css/all.min.css"|base_url}">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="{"/application/views/templates/admin/partials/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css"|base_url}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{"/application/views/templates/admin/partials/assets/dist/css/adminlte.min.css"|base_url}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<!-- /.login-logo -->
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<a href="{"/application/views/templates/admin/partials/assets/index2.html"|base_url}" class="h1"><b>{$companyName}</b> Admin</a>
		</div>
		<div class="card-body">
			<p class="login-box-msg">Giriş Yap</p>

			<form method="post" id="loginForm">
				<div class="input-group mb-3">
					<input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" class="form-control" placeholder="Password" name="password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-8">
						<div class="icheck-primary">
							<input type="checkbox" id="remember" name="rememberMe">
							<label for="remember">
								Beni Hatırla
							</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-4">
						<button type="submit" id="loginBtn" class="btn btn-primary btn-block">Giriş Yap</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/jquery/jquery.min.js"|base_url}"></script>
<!-- Bootstrap 4 -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"|base_url}"></script>
<!-- AdminLTE App -->
<script src="{"/application/views/templates/admin/partials/assets/dist/js/adminlte.js"|base_url}"></script>


<script>
    {literal}
	$(document).ready(function(){
		$('#loginBtn').click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			$.ajax({
				url: "{/literal}{base_url()}{literal}/admin/Login/trylogin",
				method: "POST",
				dataType: "json",
				data: $('#loginForm').serialize(),
				success: function(data){
					if(data.status == 'success'){
						$(document).Toasts('create', {
							title: "İşlem Başarılı",
							class: "bg-success",
							delay: 750,
							subtitle: "Yönlendiriliyorsunuz",
							body: data.message,
							autoremove: true,
						})
						setTimeout(function (){
							window.location.href = "{/literal}{base_url()}{literal}admin";
						}, 1500)
					}else{
						$(document).Toasts('create', {
							title: "İşlem Başarısız",
							class: "bg-danger",
							delay: 750,
							autoremove: true,
							body: data.failed_message
						})
					}
				}
			});
		})
	})
    {/literal}
</script>

</body>
</html>
