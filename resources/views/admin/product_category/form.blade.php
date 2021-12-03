@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <form method="POST" enctype="multipart/form-data"
                  action="{{ admin_url('woocommerce/product_categories') }}{{ ($product_category->id ?? 0) > 0 ?'/'.$product_category->id: '' }}">
                @csrf
                @if (!empty($product_category->id))
                    @method('PUT')
                @endif

                <input type="hidden" name="parent_id"
                       value="{{ old('parent_id', ($product_category->parent_id ?? @request('parent_id', 0))) }}">

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
                        <div class="form-group">
                            <label class="col-form-label" for="category_id">
                                {{ trans('lang_woocommerce::product.parent_id') }}
                            </label>
                            <div class="controls">
                                @include('admin.element.form.select', ['name' => 'parent_id', 'empty' => true, 'data' => $dropdownCategory, 'selected' => old('parent_id', $product_category->parent_id ?? request('parent_id', 0))])
                            </div>
                        </div>

                        @include('admin.element.form.input', ['name' => 'title', 'text' => trans('lang_woocommerce::product.title'), 'value' => $product_category->title ?? ''])
                        @include('admin.element.form.input', ['name' => 'slug', 'text' => trans('lang_woocommerce::product.slug'), 'value' => $product_category->slug ?? ''])
                        @include('admin.element.form.textarea', ['name' => 'description', 'class' => 'ckeditor', 'text' => trans('lang_woocommerce::product.description'), 'value' => $product_category->description ?? ''])
                        @include('admin.element.form.image', ['name' => 'image_id', 'image_id' => $product_category->image_id ?? '', 'image_url' => $product_category->image_url ?? ''])
                        @include('admin.element.form.check', ['name' => 'is_home', 'text' => trans('lang_woocommerce::product.is_home'), 'value' => $product_category->is_home ?? 0])
                        @include('admin.element.form.check', ['name' => 'status', 'text' => trans('lang_woocommerce::product.status'), 'value' => $product_category->status ?? (empty($product_category->id) ? 1 : 0)])

                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                {{ trans('common.save') }}
                            </button>
                        </div>
                    </div>
                </div>


                <!-- seo form -->
                @include('admin.element.form_seo', ['info' => $product_category ?? ''])

            </form>

            @if (!empty($product_category->id))
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="form-actions text-lg-right">
                            <form method="POST" onsubmit="return confirm('Do you want DELETE ?');"
                                  action="{{ admin_url('woocommerce/product_categories/'.$product_category->id ) }}">
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
