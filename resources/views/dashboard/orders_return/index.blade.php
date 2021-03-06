@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders_return')
                <small>({{ $orders_return->total() }} )</small>
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders_return')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-7">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.orders_return')</h3>

                            <form action="{{ route('dashboard.orders_return.index') }}" method="get">

                                <div class="row">

                                    <div class="col-md-8">
                                        <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>

                                </div><!-- end of row -->

                            </form><!-- end of form -->

                        </div><!-- end of box header -->

                        @if ($orders_return->count() > 0)

                            <div class="box-body table-responsive">

                                <table class="table table-hover">
                                    <tr>
                                        <th>@lang('site.client_name')</th>
                                        <th>@lang('site.price')</th>
{{--                                        <th>@lang('site.status')</th>--}}
                                        <th>@lang('site.created_at')</th>
                                        <th>@lang('site.action')</th>
                                    </tr>

                                    @foreach ($orders_return as $order_return)
                                        <tr>
                                            <td>{{ $order_return->client->name }}</td>
                                            <td>{{ number_format($order_return->total_price, 2) }}</td>
                                            {{--<td>--}}
                                                {{--<button--}}
                                                    {{--data-status="@lang('site.' . $order->status)"--}}
                                                    {{--data-url="{{ route('dashboard.orders.update_status', $order->id) }}"--}}
                                                    {{--data-method="put"--}}
                                                    {{--data-available-status='["@lang('site.processing')", "@lang('site.finished') "]'--}}
                                                    {{--class="order-status-btn btn {{ $order->status == 'processing' ? 'btn-warning' : 'btn-success disabled' }} btn-sm"--}}
                                                {{-->--}}
                                                    {{--@lang('site.' . $order->status)--}}
                                                {{--</button>--}}
                                            {{--</td>--}}
                                            <td>{{ $order_return->created_at->toFormattedDateString() }}</td>
                                            <td>
                                            <button class="btn btn-primary btn-sm order-products"
                                                        data-url="{{ route('dashboard.orders_return.products', $order_return->id) }}"
                                                        data-method="get"
                                                >
                                                    <i class="fa fa-list"></i>
                                                    @lang('site.show')
                                                </button>
                                                @if (auth()->user()->hasPermission('update_orders_return'))
                                                    <a href="{{ route('dashboard.clients.orders_return.edit', ['client' => $order_return->client->id, 'orders_return' => $order_return->id]) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> @lang('site.edit')</a>
                                                @endif

                                                @if (auth()->user()->hasPermission('delete_orders_return'))
                                                    <form action="{{ route('dashboard.orders_return.destroy', $order_return->id) }}" method="post" style="display: inline-block;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                                    </form>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </table><!-- end of table -->

                                {{ $orders_return->appends(request()->query())->links() }}

                            </div>

                        @else

                            <div class="box-body">
                                <h3>@lang('site.no_records')</h3>
                            </div>

                        @endif

                    </div><!-- end of box -->

                </div><!-- end of col -->

                <div class="col-md-5">

                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.show_products')</h3>
                        </div><!-- end of box header -->

                        <div class="box-body">

                            <div style="display: none; flex-direction: column; align-items: center;" id="loading">
                                <div class="loader"></div>
                                <p style="margin-top: 10px">@lang('site.loading')</p>
                            </div>

                            <div id="order-product-list">

                            </div><!-- end of order product list -->

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </section><!-- end of content section -->

    </div><!-- end of content wrapper -->

@endsection
