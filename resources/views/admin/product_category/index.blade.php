@extends('admin.layout.app')
@section('content')

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> {{ trans('common.list') }}

            <div class="card-header-actions">
                <a class="btn btn-sm btn-primary" href="{{ admin_url('woocommerce/product_categories/create') }}">
                    <small>
                        <i class="fa fa-plus"></i>
                        {{trans('common.button.add')}}
                    </small>
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('admin.element.filter')
            <table id="simple-tree-table" class="table table-responsive-sm table-bordered table-hover font12">
                <thead>
                <tr class="bg-light">
                    <th>{{ trans('lang_woocommerce::product.title') }}</th>
                    <th>{{ trans('lang_woocommerce::product.slug') }}</th>
                    <th>{{ trans('lang_woocommerce::product.total_usage') }}</th>
                    <th class="text-center">{{ trans('lang_woocommerce::product.image_url') }}</th>
                    <th>{{ trans('lang_woocommerce::product.created_at') }}</th>
                    <th style="width: 220px;"></th>
                </tr>
                </thead>
                <tbody>
                @if ($items->count() > 0)
                    @foreach ($items as $item)
                        <tr data-node-id="{{$item->id}}" data-node-pid="{{$item->parent_id}}">
                            <td>
                                <a href="{{ admin_url('woocommerce/product_categories/'.$item->id.'/edit') }}">
                                    {{ $item->title }}
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                {{ $item->slug }}

                                @if($item->status)
                                    <label class="label label-success">Enable</label>
                                @else
                                    <label class="label label-default">Disable</label>
                                @endif

                                @if($item->is_home)
                                    <label class="label label-primary">Home</label>
                                @endif
                            </td>
                            <td>
                                {{ $item->total_usage }}
                            </td>
                            <td class="text-center">
                                @if($item->image_url)
                                    <img src="{{ asset('storage'.$item->image_url) }}" class="img-table img-thumbnail"/>
                                @endif
                            </td>
                            <td>
                                {{ $item->created_at->format(config('app.date_format')) }}
                            </td>
                            <td class="text-right">
                                <form method="post" onsubmit="return confirm('Do you want DELETE ?');"
                                      action="{{ admin_url('woocommerce/product_categories/'.$item->id ) }}">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ $item->link }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fa fa-globe"></i>
                                    </a>

                                    <a href="{{ admin_url('woocommerce/product_categories/create?parent_id='.$item->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-sitemap"></i> {{ trans('nav.add_menu_child') }}
                                    </a>

                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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
        </div>
    </div>

@endsection
