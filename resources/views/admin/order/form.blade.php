@extends('admin.layout.app')
@section('content')

    <form method="post" action="{{ admin_url('woocommerce/orders') }}">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            <i class="fa fa-cube"></i>
                            {{ trans('lang_woocommerce::sale_order.product') }}
                        </h4>
                        <hr/>
                        @csrf

                        <div class="input-group" style="margin: 10px auto">
                            <input class="form-control" id="product_id" name="search" style="width: 100%"
                                   placeholder="{{ trans('lang_woocommerce::sale_order.search.add') }}"/>

                        </div>

                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th>{{ trans('lang_woocommerce::sale_order.product') }}</th>
                                <th style="width: 100px"
                                    class="text-center">{{ trans('lang_woocommerce::sale_order.quantity') }}</th>
                                <th style="width: 200px"
                                    class="text-center">{{ trans('lang_woocommerce::sale_order.price') }}</th>
                                <th style="width: 200px"
                                    class="text-center">{{ trans('lang_woocommerce::sale_order.total') }}</th>
                            </tr>
                            </thead>
                            <tbody id="add-item-order">

                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="text-right" colspan="3">
                                    <strong>{{ trans('lang_woocommerce::sale_order.total_final') }}</strong>
                                </td>
                                <td class="text-center">
                                    <span id="so_total_final">0</span>
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            <i class="fa fa-user"></i>
                            {{ trans('lang_woocommerce::sale_order.billing_info') }}
                        </h4>
                        <hr/>
                        <div class="form-group">
                            <label class=""><i class="fa fa-user-circle"></i> {{ trans('common.fullname') }}</label>
                            <div class="controls">
                                <input class="form-control @error('billing_fullname') is-invalid @enderror"
                                       name="billing_fullname"
                                       id="billing_fullname"
                                       required
                                       value="{{ old('billing_fullname') }}"
                                       placeholder="{{ trans('common.fullname') }}/ Fullname"
                                       autocomplete="off">

                                @error('billing_fullname')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=""><i class="fa fa-envelope-o"></i> Email</label>
                            <div class="controls">
                                <input class="form-control @error('billing_email') is-invalid @enderror"
                                       value="{{ old('billing_email') }}"
                                       required
                                       name="billing_email"
                                       id="billing_email"
                                       type="email"
                                       autocomplete="off"
                                       placeholder="Email">

                                @error('billing_email')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fa fa-phone"></i> {{ trans('common.phone') }}
                                <span class="text-info">(Input phone search info customer)</span>
                            </label>
                            <div class="controls">
                                <input class="form-control @error('billing_phone') is-invalid @enderror"
                                       name="billing_phone"
                                       id="billing_phone"
                                       required
                                       value="{{ old('billing_phone') }}"
                                       placeholder="{{ trans('common.phone') }}/ Phonenumber"
                                       autocomplete="off">
                                @error('billing_phone')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=""><i class="fa fa-map"></i> {{ trans('common.address') }}</label>
                            <div class="controls">
                                <input class="form-control"
                                       name="billing_address"
                                       id="billing_address"
                                       value="{{ old('billing_address') }}"
                                       placeholder="{{ trans('common.address') }}/ Address"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=""><i class="fa fa-info"></i> {{ trans('common.note') }}</label>
                            <div class="controls">
                                <textarea rows="4" class="form-control" name="note"
                                          placeholder="{{ trans('common.note') }}/ Note"
                                          autocomplete="off">{{ old('note') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                {{ trans('lang_woocommerce::sale_order.title_create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#billing_phone').on('blur', function () {
                let phone = $(this).val();
                $.ajax({
                    url: configs.admin_url + '/woocommerce/api/orders/find-info?phone=' + phone,
                    dataType: 'json',
                    success: function (result) {
                        if (parseInt(result.status) === 1) {
                            $('#billing_fullname').val(result.data.billing_fullname);
                            $('#billing_email').val(result.data.billing_email);
                            $('#billing_address').val(result.data.billing_address);
                        }
                    }
                });
            });
            /**
             * check all for all list data
             */
            if ($('#product_id').length > 0) {
                let options = {
                    url: function (keyword) {
                        return configs.admin_url + '/woocommerce/api/products?keyword=' + keyword
                    },
                    getValue: "title",
                    listLocation: "data",
                    list: {
                        onClickEvent: function () {
                            let item = $("#product_id").getSelectedItemData();
                            createItemOrder(item);
                        }
                    }
                };

                $("#product_id").easyAutocomplete(options);
            }
        })
    </script>
@endsection
