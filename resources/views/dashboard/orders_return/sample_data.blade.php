		<div >    
  			<br />
  			<br />
  			<br />
			<div class="table-responsive">
				<table id="user_table" class="table table-bordered table-striped">
					<thead>
						<tr>
						<th>@lang('site.name')</th>
		                <th>@lang('site.sale_price')</th>
				
                        <th>@lang('site.stock')</th>
				            <th width="30%">-</th>
						</tr>
					</thead>
				</table>
			</div>
			<br />
			<br />
		</div>



<script>
$(document).ready(function(){

	$('#user_table').DataTable({
		processing: true,
		serverSide: false,
		ajax: {
			url: "{{ route('orders.get_prods') }}",
		},
		columns: [
			{
				data: 'name',
				name: @lang('site.name')
			},
			{
				data: 'sale_price',
				name: 'sale_price'
			},
			{
				data: 'stock',
				name: 'stock'
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

