<?php
/**
 * @author: nguyentinh
 * @create: 11/18/19, 10:46 AM
 */

namespace TinhPHP\Woocommerce\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use TinhPHP\Woocommerce\Jobs\ShoppingCartJob;
use TinhPHP\Woocommerce\Models\Product;
use App\Models\RolePermission;
use TinhPHP\Woocommerce\Models\SaleOrder;
use TinhPHP\Woocommerce\Models\SaleStore;
use TinhPHP\Woocommerce\Services\SaleOrderLineService;
use TinhPHP\Woocommerce\Services\SaleOrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 * @property SaleOrderService $saleOrderService
 * @property SaleOrderLineService $saleOrderLineService
 */
class OrderController extends AdminWoocommerceController
{
    public function __construct(SaleOrderService $saleOrderService, SaleOrderLineService $saleOrderLineService)
    {
        parent::__construct();
        $this->middleware(['permission:' . RolePermission::PRODUCT_SHOW]);
        $this->saleOrderService = $saleOrderService;
        $this->saleOrderLineService = $saleOrderLineService;
    }

    public function index(Request $request)
    {
        $this->saleOrderService->buildCondition($request->all(), $condition, $sortBy, $sortType);
        $items = SaleOrder::query()->where($condition)->orderBy($sortBy, $sortType)->paginate($this->page_number);

        $filter = $this->saleOrderService->filter($request->all());
        $data = [
            'filter' => $filter,
            'items' => $items,
        ];

        return view('view_woocommerce::admin.order.index', $this->render($data));
    }

    public function create()
    {
        $data = [
            'title' => trans('lang_woocommerce::sale_order.title_create'),
        ];

        return view('view_woocommerce::admin.order.form', $this->render($data));
    }

    public function store(Request $request)
    {
        $param = $request->all();
        if (!empty($param['product_id'])) {
            $productIds = $param['product_id'];
            $quantity = $param['quantity'];

            $products = Product::query()->whereIn('id', $productIds)->get();
            if ($products->count() > 0) {
                try {
                    DB::beginTransaction();
                    $mySaleOrder = new SaleOrder($param);
                    $mySaleOrder->code = SaleOrder::generateCode();
                    $mySaleOrder->status = SaleOrder::STATUS_NEW;
                    $mySaleOrder->save();

                    $mySaleStore = SaleStore::query()->first();

                    $priceFinal = 0;
                    foreach ($products as $product) {
                        $price = $product->price;
                        if ($product->price_promotion > 0 && $product->price > $product->price_promotion) {
                            $price = $product->price_promotion;
                        }
                        $qty = $quantity[$product->id] ?? 1;

                        $dataInsert = [
                            'organization_id' => 0,
                            'customer_id' => 0,
                            'order_id' => $mySaleOrder->id,
                            'sale_store_id' => $mySaleStore->id,
                            'order_code' => $mySaleOrder->code,
                            'product_id' => $product->id,
                            'product_sku' => $product->sku,
                            'product_name' => $product->title,
                            'product_category_id' => $product->product_category_id,
                            'quantity' => $qty,
                            'item_price_cost' => $price,
                            'item_price_sell' => $price,
                            'cost_total' => $qty * $price,
                            'sub_total' => $qty * $price,
                            'status' => $mySaleOrder->status,
                            'report_year' => date('Y'),
                            'report_month' => date('m'),
                            'report_date' => date('d'),
                        ];
                        $priceFinal += $dataInsert['sub_total'];
                        $this->saleOrderLineService->create($dataInsert);
                    }

                    $mySaleOrder->price_sell = $priceFinal;
                    $mySaleOrder->price_final = $priceFinal;
                    $mySaleOrder->save();

                    DB::commit();

                    $request->session()->flash('success', trans('common.add.success'));

                    return redirect(admin_url('woocommerce/orders'));
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        } else {
            $request->session()->flash('success', trans('lang_woocommerce::sale_order.error_add_cart_empty'));
        }

        return back()->withInput();
    }

    public function show($id)
    {
        $myObject = SaleOrder::query()->findOrFail($id);
        $data = [
            'saleOrder' => $myObject,
            'title' => trans('lang_woocommerce::sale_order.code') . ' #' . $myObject->code,
        ];

        return view('view_woocommerce::admin.order.show', $this->render($data));
    }

    public function resentMail(Request $request, $id): RedirectResponse
    {
        SaleOrder::query()->findOrFail($id);

        // push queue send mail
        $paramsMail = ['action' => ShoppingCartJob::ACTION_RESEND_EMAIL_ORDER, 'id' => $id];
        ShoppingCartJob::dispatch($paramsMail);

        $request->session()->flash('success', trans('lang_woocommerce::sale_order.resent_mail'));

        return back()->withInput();
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function status(Request $request, $id): RedirectResponse
    {
        $myObject = SaleOrder::query()->findOrFail($id);
        $status = $request['status'];
        $myObject->status = $status;
        $myObject->save();

        $request->session()->flash('success', trans('lang_woocommerce::sale_order.update_status_success'));

        return back();
    }


    /**
     * Đặt lại đơn hàng
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function reorder(Request $request, $id): RedirectResponse
    {
        $order = SaleOrder::query()->findOrFail($id);

        $newOrder = $order->replicate();

        $newOrder->created_at = Carbon::now();
        $newOrder->code = SaleOrder::generateCode();
        $newOrder->status = SaleOrder::STATUS_NEW;
        $newOrder->save();

        foreach ($order->sale_order_lines as $orderLine) {
            $newOrderLine = $orderLine->replicate();
            $newOrderLine->created_at = Carbon::now();
            $newOrderLine->order_id = $newOrder->id;
            $newOrderLine->save();
        }

        $request->session()->flash('success', trans('lang_woocommerce::sale_order.reorder_success'));

        return back();
    }

    public function destroy(Request $request, $id)
    {
        $myObject = SaleOrder::query()->findOrFail($id);

        if (!empty($myObject->id)) {
            SaleOrder::destroy($id);
        }

        $request->session()->flash('success', trans('common.delete.success'));

        return redirect(admin_url('woocommerce/orders'));
    }

    public function report(Request $request)
    {
        $data = [
            'title' => trans('lang_woocommerce::sale_order.report'),
        ];

        return view('view_woocommerce::admin.order.report', $this->render($data));
    }

    public function getReport(): JsonResponse
    {
        $total = SaleOrder::query()
            ->selectRaw('SUM(price_final) as total, MONTH(created_at) as month')
            ->where('status', SaleOrder::STATUS_COMPLETED)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->toArray();

        $totalOrder = SaleOrder::query()
            ->selectRaw('COUNT(id) as total, MONTH(created_at) as month')
            ->where('status', SaleOrder::STATUS_COMPLETED)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->toArray();

        $totalRevenue = SaleOrder::query()
            ->where('status', SaleOrder::STATUS_COMPLETED)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->sum('price_final');

        $totalRevenue7day = SaleOrder::query()
            ->whereRaw('DATE(created_at) > (NOW() - INTERVAL 7 DAY)')
            ->where('status', SaleOrder::STATUS_COMPLETED)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->sum('price_final');

        $totalOrderNew = SaleOrder::query()
            ->whereIn('status', [SaleOrder::STATUS_PROCESSING, SaleOrder::STATUS_NEW])
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->count();

        $totalOrderCompleted = SaleOrder::query()
            ->where('status', SaleOrder::STATUS_COMPLETED)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->count();

        $dataChart = [
            'total_revenue' => number_format($totalRevenue) . ' ' . config('app.currency'),
            'total_revenue_7day' => number_format($totalRevenue7day) . ' ' . config('app.currency'),
            'total_order_new' => number_format($totalOrderNew),
            'total_order_completed' => number_format($totalOrderCompleted),
            'total' => $this->saleOrderService->convertHighChart($total),
            'totalOrder' => $this->saleOrderService->convertHighChart($totalOrder),
        ];

        return response()->json($dataChart);
    }

    public function apiOrderFindInfo(Request $request)
    {
        $status = 0;
        $data = [];
        $phone = $request->get('phone');
        if (!empty($phone)) {
            $select = [
                'id',
                'billing_phone',
                'billing_email',
                'billing_address',
                'billing_fullname',
            ];
            $object = SaleOrder::query()->where('billing_phone', $phone)->orderByDesc('id')->first($select);

            if (!empty($object->id)) {
                $data = $object->toArray();
                $status = 1;
            }
        }

        $dataResponse = [
            'status' => $status,
            'data' => $data,
        ];
        return response()->json($dataResponse);
    }

}
