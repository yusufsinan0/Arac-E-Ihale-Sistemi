<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>{$page_title} - {$company_name}</title>

	<!-- Custom fonts for this template-->
	<link href="{base_url()}/application/views/templates/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
	<link
			href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
			rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="{base_url()}application/views/templates/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container">

	<!-- Outer Row -->
	<div class="container-fluid">

		<!-- 404 Error Text -->
		<div class="text-center" style="margin-top: 25%">
			<div class="error mx-auto" data-text="404">404</div>
			<p class="lead text-gray-800 mb-5">{$error_title}</p>
			<p class="text-gray-500 mb-0">{$error_message}</p>
		</div>

	</div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="{base_url()}/application/views/templates/admin/vendor/jquery/jquery.min.js"></script>
<script src="{base_url()}/application/views/templates/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    {literal}
	$(document).ready(function(){
		$('#loginBtn').click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			$.ajax({
				url: "{/literal}{base_url()}{literal}/Login/trylogin",
				method: "POST",
				dataType: "json",
				data: $('#loginForm').serialize(),
				success: function(data){
					if(data.status == 'success'){
						toastr.options.timeOut = 800;
						toastr.options.fadeOut = 800;
						toastr.options.onHidden = function(){
							let a= document.createElement('a');
							a.href= "{/literal}{base_url()}{literal}";
							a.click();
						};
						toastr["success"](data.message, "İşlem Başarılı")
					}else{
						toastr["error"](data.failed_message, "İşlem Başarısız")
					}
				}
			});
		})
	})
    {/literal}
</script>


<!-- Core plugin JavaScript-->

<script src="{base_url()}/application/views/templates/admin/js/toastr.min.js"></script>
<script src="{base_url()}/application/views/templates/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="{base_url()}/application/views/templates/admin/js/sb-admin-2.min.js"></script>

</body>

</html>
