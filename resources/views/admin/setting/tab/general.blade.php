@include('admin.element.form.input', ['name' => 'seo_title', 'text' => 'Seo Title', 'value' => old('seo_title', $setting['seo_title'] ?? '')])
<div class="form-group">
    <label class="col-form-label" for="currency">{{ trans('lang_woocommerce::product.setting.currency') }}</label>
    <div class="controls">

        @include('admin.element.form.select', ['name' => 'currency', 'data' => ['VND' => 'Vietnam đồng (VND)', 'USD' => 'United States Dollars (USD)'], 'selected' => old('currency', $setting['currency'] ?? '')])


    </div>
</div>
