@extends('admin.layout.app')
@section('content')

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> {{ trans('common.list') }}

            <div class="card-header-actions">
                <a class="btn btn-sm btn-primary" href="{{ admin_url('woocommerce/orders/create') }}">
                    <small>
                        <i class="fa fa-plus"></i>
                        {{trans('common.button.add')}}
                    </small>
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.element.filter')

            <form method="get" action="{{ admin_url('woocommerce/orders') }}">
                <table class="table table-responsive-sm table-bordered table-hover font12">
                    <thead>
                    <tr class="bg-light">
                        <th>{{ trans('lang_woocommerce::sale_order.code') }}</th>
                        <th class="th-category_id">{{ trans('lang_woocommerce::sale_order.billing_fullname') }}</th>
                        <th class="th-category_id">{{ trans('lang_woocommerce::sale_order.billing_phone') }}</th>
                        <th class="th-created_at">{{ trans('lang_woocommerce::sale_order.created_at') }}</th>
                        <th class="th-created_at">{{ trans('lang_woocommerce::sale_order.price_final') }}</th>
                        <th class="th-status text-center">{{ trans('lang_woocommerce::sale_order.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if ($items->count() > 0)
                        @foreach ($items as $item)
                            <tr>
                                <td><a href="{{ admin_url('woocommerce/orders/'.$item->id) }}">{{ $item->code }}</a></td>
                                <td>{{ $item->billing_fullname }}</td>
                                <td>{{ $item->billing_phone }}</td>
                                <td>{{ $item->created_at->format(config('app.date_format')) }}</td>
                                <td class="text-primary">{{ number_format($item->price_final) }}</td>
                                <td class="text-center">
                                    <label class="label label-{{ $item->status_color }}">
                                        {{ $item->status_text }}
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                {{ trans('common.data_empty') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>

            @include('admin.element.pagination')
        </div>
    </div>

@endsection
