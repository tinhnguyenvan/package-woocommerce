@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form method="post" class="submit" enctype="multipart/form-data"
                  action="{{ admin_url('woocommerce/products') }}{{ ($product->id ?? 0) > 0 ?'/'.$product->id: '' }}">
                @csrf
                @if (!empty($product->id))
                    @method('PUT')
                @endif

                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-edit"></i> {{ trans('common.form') }}
                        <div class="card-header-actions">
                            <a class="btn btn-minimize" href="#" data-toggle="collapse" data-target="#collapseExample"
                               aria-expanded="true">
                                <i class="icon-arrow-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body collapse show" id="collapseExample">

                        @include('admin.element.form.input', ['name' => 'title', 'text' => trans('lang_woocommerce::product.title'), 'value' => $product->title ?? ''])


                        <div class="row">
                            <div class="col-lg-4 col-xs-12">
                                <div class="form-group">
                                    <label class="col-form-label" for="category_id">
                                        {{ trans('lang_woocommerce::product.category_id') }}
                                    </label>
                                    <div class="controls">
                                        @include('admin.element.form.select', ['name' => 'category_id', 'data' => $dropdownCategory, 'selected' => old('category_id', $product->category_id ?? 0)])
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-xs-12">
                                @include('admin.element.form.input', ['name' => 'sku', 'text' => trans('lang_woocommerce::product.sku'), 'value' => $product->sku ?? ''])
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-xs-12">
                                @include('admin.element.form.input', ['name' => 'price', 'text' => trans('lang_woocommerce::product.price'), 'value' => $product->price ?? 0, 'type' => 'number'])
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                @include('admin.element.form.input', ['name' => 'price_promotion', 'text' => trans('lang_woocommerce::product.price_promotion'), 'value' => $product->price_promotion ?? 0, 'type' => 'number'])
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                @include('admin.element.form.input', ['name' => 'quantity', 'text' => trans('lang_woocommerce::product.quantity'), 'value' => $product->quantity ?? 0, 'type' => 'number'])
                            </div>
                        </div>

                        <!-- meta product -->
                        @if(!empty($form_meta))
                            <div class="row">
                                @foreach($form_meta as $meta)
                                    <div class="col-lg-4 col-xs-12">
                                        @include('admin.element.form.'.$meta['form'], ['name' => 'meta_key['.$meta['name'].']', 'text' => trans('lang_woocommerce::product.'.$meta['name']), 'value' => $product->meta[$meta['name']] ?? $meta['default'], 'type' => $meta['type']])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    <!-- end product -->

                        @include('admin.element.form.textarea', ['name' => 'summary', 'text' => trans('lang_woocommerce::product.summary'), 'value' => $product->summary ?? ''])

                        @include('admin.element.form.textarea', ['name' => 'detail', 'class' => 'ckeditor', 'text' => trans('lang_woocommerce::product.detail'), 'value' =>  $product->detail ?? ''])

                        <div class="nav-tabs-boxed">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#multi1" role="tab"
                                       aria-controls="multi1">
                                        <i class="fa fa-image"></i> Image
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#multi2" role="tab"
                                       aria-controls="multi2">
                                        <i class="icon-picture"></i> Gallery
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="multi1" role="tabpanel">
                                    @include('admin.element.form.image', ['name' => 'image_id', 'image_id' => $product->image_id ?? '', 'image_url' => $product->image_url ?? ''])
                                </div>
                                <div class="tab-pane" id="multi2" role="tabpanel">
                                    @include('admin.element.form.image_multi', ['value' => $product->images ?? []])
                                </div>
                            </div>
                        </div>

                        @include('admin.element.form.tags', ['name' => 'tags', 'text' => trans('common.tags'), 'value' => $product->tags ?? ''])

                        @include('admin.element.form.check', ['name' => 'is_home', 'text' => trans('lang_woocommerce::product.is_home'), 'value' => $product->is_home ?? 0])

                        <div class="form-group">
                            <label class="col-form-label"
                                   for="status">{{ trans('lang_woocommerce::product.status') }}</label>
                            <div class="controls">
                                @include('admin.element.form.radio', ['name' => 'status', 'text' => trans('lang_woocommerce::product.status.active'), 'valueDefault' => \TinhPHP\Woocommerce\Models\Product::STATUS_ACTIVE, 'value' => $product->status ?? 1])
                                @include('admin.element.form.radio', ['name' => 'status', 'text' => trans('lang_woocommerce::product.status.disable'), 'valueDefault' => \TinhPHP\Woocommerce\Models\Product::STATUS_DISABLE, 'value' => $product->status ?? 0])
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                {{ trans('common.save') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- seo form -->
                @include('admin.element.form_seo', ['info' => $product ?? ''])

            </form>

            @if (!empty($product->id))
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="form-actions text-lg-right">
                            <form method="post" onsubmit="return confirm('Do you want DELETE ?');"
                                  action="{{ admin_url('woocommerce/products/'.$product->id ) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">
                                    <i class="fa fa-trash"></i>
                                    {{ trans('common.trash') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
