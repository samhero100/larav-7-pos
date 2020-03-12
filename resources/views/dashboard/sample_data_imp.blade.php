	
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />


	<div class="row">

<div class="col-md-12">
<br />

<div class="table-responsive">
				<table id="user_table" class="table table-bordered table-striped">
					<thead>
						<tr>
						<th width="5%">id</th>
							<th width="15%">@lang('site.category')</th>

							<th width="30%">@lang('site.name')</th>

							<th width="15%">@lang('site.purchase_price')</th>

				            <th width="5%"></th>
						</tr>
					</thead>
				</table>
			</div>
			<br />
			<br />
		</div>
		</div>
		</div>

	</body>
</html>


<script>
$(document).ready(function(){

	$('#user_table').DataTable({
		processing: true,
		serverSide: false,
		ajax: {
			url: "{{ route('orders.get_prods1') }}",
		},
		columns: [
			{
				data: 'id',
				name: 'id'
			},
			{
				data: 'category',
				name: 'category'
			},

			{
				data: 'product',
				name: 'product'
			},
			{
				data: 'purchase_price',
				name: 'price'
			},

			{
				data: 'action',
				name: 'action',
				orderable: false
			}
		]
	});



});
</script>

