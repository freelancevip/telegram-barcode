@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.customer.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Customer">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.chatid') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.first_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.last_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.username') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $key => $customer)
                        <tr data-entry-id="{{ $customer->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $customer->id ?? '' }}
                            </td>
                            <td>
                                {{ $customer->chatid ?? '' }}
                            </td>
                            <td>
                                {{ $customer->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $customer->last_name ?? '' }}
                            </td>
                            <td>
                                {{ $customer->username ?? '' }}
                            </td>
                            <td>
                                {{ $customer->created_at ?? '' }}
                            </td>
                            <td>
                                @can('customer_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.customers.show', $customer->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan


                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[1, 'desc']],
                pageLength: 100,
            });
            let table = $('.datatable-Customer:not(.ajaxTable)').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })

    </script>
@endsection
