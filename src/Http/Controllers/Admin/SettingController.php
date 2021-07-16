<?php

namespace TinhPHP\Woocommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TinhPHP\Woocommerce\Models\Setting;
use TinhPHP\Woocommerce\Services\SettingService;

/**
 * @property SettingService $settingService
 */
class SettingController extends AdminWoocommerceController
{
    public function __construct(SettingService $settingService)
    {
        parent::__construct();

        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $setting = $this->settingService->getSetting();
        $tabs = [
            'general',
        ];
        $data = [
            'tabActive' => $request->get('tab', 'general'),
            'tabs' => $tabs,
            'setting' => $setting,
            'title' => 'Setting Woocommerce'
        ];
        return view('view_woocommerce::admin.setting.index', $this->render($data));
    }

    public function save(Request $request)
    {
        $params = $request->all();
        if (!empty($params['_token'])) {
            foreach ($params as $key => $item) {
                if ('_token' == $key) {
                    continue;
                }

                $myConfig = Setting::query()->where('name', $key)->first();
                if (empty($myConfig)) {
                    $myConfig = new Setting();
                    $myConfig->creator_id = Auth::id();
                }

                $myConfig->organization_id = 0;
                $myConfig->editor_id = Auth::id();
                $myConfig->name = $key;
                $myConfig->slug = Str::slug($key);
                $myConfig->value = $item;
                $myConfig->save();
            }

            $request->session()->flash('success', trans('common.edit.success'));
        }

        return back()->withInput();
    }
}
