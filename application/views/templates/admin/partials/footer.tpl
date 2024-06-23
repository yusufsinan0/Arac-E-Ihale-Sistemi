<!-- /.content-wrapper -->
<footer class="main-footer">
	<strong>Copyright &copy; {date("Y")} <a href="{base_url()}">{$companyName}</a>.</strong>
	All rights reserved.
	<div class="float-right d-none d-sm-inline-block">
		<b>Version</b> 1.0
	</div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- jQuery -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/jquery/jquery.min.js"|base_url}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/jquery-ui/jquery-ui.min.js"|base_url}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"|base_url}"></script>
<!-- ChartJS -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/chart.js/Chart.min.js"|base_url}"></script>
<!-- Sparkline -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/sparklines/sparkline.js"|base_url}"></script>
<!-- JQVMap -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/jqvmap/jquery.vmap.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"|base_url}"></script>
<!-- jQuery Knob Chart -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/jquery-knob/jquery.knob.min.js"|base_url}"></script>
<!-- daterangepicker -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/moment/moment.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/daterangepicker/daterangepicker.js"|base_url}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"|base_url}"></script>
<!-- Summernote -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/summernote/summernote-bs4.min.js"|base_url}"></script>
<!-- overlayScrollbars -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"|base_url}"></script>
<!-- AdminLTE App -->
<script src="{"/application/views/templates/admin/partials/assets/dist/js/adminlte.js"|base_url}"></script>
<!-- SweetAlert -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/sweetalert2/sweetalert2.min.js"|base_url}"></script>
<!-- DataTables  & Plugins -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables/jquery.dataTables.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/jszip/jszip.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/pdfmake/pdfmake.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/pdfmake/vfs_fonts.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-buttons/js/buttons.html5.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-buttons/js/buttons.print.min.js"|base_url}"></script>
<script src="{"/application/views/templates/admin/partials/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"|base_url}"></script>

<!-- Select2 -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/select2/js/select2.full.min.js"|base_url}"></script>

<!-- Bootstrap Switch -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"|base_url}"></script>


<!-- Prism -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/prism/prism.js"|base_url}"></script>

<!-- Input Mask -->
<script src="{"/application/views/templates/admin/partials/assets/plugins/inputmask/jquery.inputmask.min.js"|base_url}"></script>

<!-- Custom JS -->
<script src="{"/application/views/templates/admin/partials/assets/dist/js/custom.js?v={$smarty.now}"|base_url}"></script>

{if $javascriptFile}
	<script src="{"/application/views/templates/admin/partials/assets/dist/js/{$javascriptFile}?v={$smarty.now}"|base_url}"></script>
{/if}


<script>
	$(document).ready(function (){
		let Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
		$(".alertboxes").each(function (index, element) {
			Toast.fire({
				icon: $(element).data("alert-type"),
				title: $(element).data("alert-message")
			})
		})
	});
</script>
