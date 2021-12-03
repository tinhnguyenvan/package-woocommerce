<?php

namespace TinhPHP\Woocommerce\Http\Controllers;

use App\Models\RolePermission;
use TinhPHP\Woocommerce\Jobs\ShoppingCartJob;
use TinhPHP\Woocommerce\Models\Product;
use TinhPHP\Woocommerce\Models\SaleOrder;
use TinhPHP\Woocommerce\Models\SaleStore;
use TinhPHP\Woocommerce\Services\SaleOrderLineService;
use TinhPHP\Woocommerce\Services\SaleOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CartController.
 *
 * @property SaleOrderService $saleOrderService
 * @property SaleOrderLineService $saleOrderLineService
 */
final class CartController extends Controller
{
    public function __construct(SaleOrderService $saleOrderService, SaleOrderLineService $saleOrderLineService)
    {
        parent::__construct();
        $this->saleOrderService = $saleOrderService;
        $this->saleOrderLineService = $saleOrderLineService;
        $this->data['is_cart'] = 1;
    }

    public function index(Request $request)
    {
        $userAgent = $request->header('user-agent');

        $items = \Cart::getContent();
        $data = [
            'token_checkout' => md5($userAgent),
            'items' => $items,
            'title' => trans('common.cart.title'),
        ];

        if (!empty($this->data['manifest']['layout_cart_index'])) {
            return view($this->data['manifest']['layout_cart_index'], $this->render($data));
        }

        return view('view_woocommerce::web.cart.index', $this->render($data));
    }

    public function checkout(Request $request, $token_checkout = '')
    {
        $userAgent = $request->header('user-agent');
        $items = \Cart::getContent();

        if (md5($userAgent) != $token_checkout || 0 == $items->count()) {
            return redirect(base_url('cart'));
        }

        $data = [
            'token_checkout' => $token_checkout,
            'items' => $items,
            'title' => trans('common.cart.checkout'),
        ];

        if (!empty($this->data['manifest']['layout_cart_checkout'])) {
            return view($this->data['manifest']['layout_cart_checkout'], $this->render($data));
        }

        return view('view_woocommerce::web.cart.checkout', $this->render($data));
    }

    public function checkoutSuccess()
    {
        $data = [
            'title' => trans('common.cart.checkout.success'),
        ];

        if (!empty($this->data['manifest']['layout_cart_success'])) {
            return view($this->data['manifest']['layout_cart_success'], $this->render($data));
        }

        return view('view_woocommerce::web.cart.success', $this->render($data));
    }

    public function checkoutError()
    {
        $data = [
            'title' => trans('common.cart.checkout.error'),
        ];

        if (!empty($this->data['manifest']['layout_cart_error'])) {
            return view($this->data['manifest']['layout_cart_error'], $this->render($data));
        }

        return view('view_woocommerce::web.cart.error', $this->render($data));
    }

    public function checkoutSave(Request $request)
    {
        $request->validate(
            [
                'billing_fullname' => 'bail|required',
                'billing_email' => 'required|email',
            ]
        );

        $params = $request->all();

        try {
            DB::beginTransaction();
            $orderId = 0;
            $items = \Cart::getContent();
            $mySaleOrder = new SaleOrder($params);
            if ($items->count() > 0) {
                $mySaleOrder->code = SaleOrder::generateCode();
                $mySaleOrder->status = SaleOrder::STATUS_NEW;
                $mySaleOrder->member_id = auth(RolePermission::GUARD_NAME_WEB)->id() ?? 0;
                $mySaleOrder->price_sell = \Cart::getSubTotal();
                $mySaleOrder->price_final = \Cart::getTotal();
                $mySaleOrder->save($params);
                $orderId = $mySaleOrder['id'];
                $mySaleStore = SaleStore::query()->first();
                foreach ($items as $item) {
                    $product = $item->associatedModel;

                    $this->saleOrderLineService->create(
                        [
                            'organization_id' => 0,
                            'customer_id' => 0,
                            'order_id' => $orderId,
                            'sale_store_id' => $mySaleStore['id'],
                            'order_code' => $mySaleOrder['code'],
                            'product_id' => $product->id,
                            'product_sku' => $product->sku,
                            'product_name' => $product->title,
                            'product_category_id' => $product->product_category_id,
                            'quantity' => $item->quantity,
                            'item_price_cost' => $item->price,
                            'item_price_sell' => $item->price,
                            'cost_total' => $item->quantity * $item->price,
                            'sub_total' => $item->quantity * $item->price,
                            'status' => $mySaleOrder['status'],
                            'report_year' => date('Y'),
                            'report_month' => date('m'),
                            'report_date' => date('d'),
                        ]
                    );
                }

                // clear cart
                \Cart::clear();
            }

            if ($orderId > 0) {
                // push queue send mail
                ShoppingCartJob::dispatch(
                    ['action' => ShoppingCartJob::ACTION_SEND_MAIL_CUSTOMER, 'id' => $orderId]
                );
            }
            DB::commit();
            return redirect(base_url('cart/checkout-success/' . md5($orderId)));
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();
            return redirect(base_url('cart/checkout-error/' . md5($orderId)));
        }
    }

    public function add(Request $request)
    {
        $params = $request->only(['product_id', 'quantity']);
        $product = Product::query()->where('id', $params['product_id'])->first();

        if ($product->id > 0) {
            $price = $product->price_promotion > 0 ? $product->price_promotion : $product->price;
            \Cart::add(
                [
                    'id' => uniqid(),
                    'name' => $product->title,
                    'price' => $price,
                    'quantity' => $params['quantity'],
                    'attributes' => [],
                    'associatedModel' => $product,
                ]
            );
        }

        return redirect(base_url('cart'));
    }

    public function delete($rowId)
    {
        \Cart::remove($rowId);

        return redirect(base_url('cart'));
    }
}
