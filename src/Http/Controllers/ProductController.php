<?php

namespace TinhPHP\Woocommerce\Http\Controllers;

use TinhPHP\Woocommerce\Models\Product;
use TinhPHP\Woocommerce\Models\ProductCategory;
use \TinhPHP\Woocommerce\Services\ProductService;
use Illuminate\Http\Request;

/**
 * Class ProductController.
 *
 * @property ProductService $productService
 */
final class ProductController extends Controller
{
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;

        $this->data['is_product'] = 1;
    }

    public function index(Request $request, $slugCategory = '')
    {
        $condition = $request->only(['filter_price', 'filter_category', 'search']);
        $viewSubCate = false;
        $productCategory = ProductCategory::query()->where('slug', $slugCategory)->first();
        if (empty($productCategory)) {
            $productCategory = (object)[
                'title' => config('app.woocommerce.seo_title'),
            ];
        } elseif ($productCategory->children->count() > 0 && env('PACKAGE_WOO_PRODUCT_CATEGORY_SUB', false)) {
            $viewSubCate = true;
        }

        if(!empty($productCategory->id)) {
            $condition['category_id'] = $productCategory->getAllChildren()->pluck('id');
            $condition['category_id'][]  = $productCategory->id;
        }

        $condition['status'] = 1;

        $items = Product::filter($condition)->orderBy('id', 'desc')->paginate(config('constant.PAGE_NUMBER'));

        $data = [
            'is_product_list' => 1,
            'is_sub_category' => $viewSubCate,
            'productCategory' => $productCategory,
            'items' => $items,
            'title' => $productCategory->title,
        ];

        if ($viewSubCate) {
            return view($this->layout . '.product.cate', $this->render($data));
        }

        return view($this->layout . '.product.index', $this->render($data));
    }

    public function view($slugCategory, $slugProduct)
    {
        $product = Product::query()->where('slug', $slugProduct)->first();

        if (empty($product)) {
            return redirect(base_url('404.html'));
        }

        Product::query()->where('id', $product->id)->increment('views');

        $items = Product::active()->where(['category_id' => $product->category_id])->orderByDesc('id')->paginate(
            $this->page_number
        );
        $productCategory = ProductCategory::query()->where('slug', $slugCategory)->first();

        $data = [
            'is_product_detail' => 1,
            'title' => $product->title,
            'product' => $product,
            'productCategory' => $productCategory,
            'items' => $items,
        ];

        // set seo
        $this->seo($product, $data);

        return view($this->layout . '.product.view', $this->render($data));
    }
}
