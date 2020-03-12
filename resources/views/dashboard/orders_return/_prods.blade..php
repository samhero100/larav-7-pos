<div id="print-area">

    @if ($products->count() > 0)

    <table class="table table-hover table-bordered">
    <tr>
        <th>@lang('site.name')</th>
        <th>@lang('site.stock')</th>
        <th>@lang('site.sale_price')</th>
        <th>@lang('site.sale_havegoml')</th>
        <th>@lang('site.sale_goml')</th>

        <th>@lang('site.add')</th>
    </tr>

    @foreach ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ number_format($product->sale_price, 2) }}</td>
            <td>{{ number_format($product->sale_havegoml, 2) }}</td>
            <td>{{ number_format($product->sale_goml, 2) }}</td>

            <td>
                <a href=""
                    id="product-{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-id="{{ $product->id }}"
                    data-price="{{ $product->sale_price }}"

                    class="btn btn-success btn-sm add-product-btn">
                    <i class="fa fa-plus"></i>
                </a>
            </td>
        </tr>
    @endforeach

</table><!-- end of table -->

@else
<h5>@lang('site.no_records')</h5>
@endif

</div>

