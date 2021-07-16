<?php
/**
 * @author: nguyentinh
 * @create: 11/20/19, 8:21 PM
 */

namespace TinhPHP\Woocommerce\Services;

use TinhPHP\Woocommerce\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @property Setting $model
 */
class SettingService extends BaseService
{
    public function __construct(Setting $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function validator($params): array
    {
        $error = [];

        $validator = Validator::make(
            $params,
            [
                'name' => 'required|min:5|max:255',
            ]
        );

        if ($validator->fails()) {
            static::convertErrorValidator($validator->errors()->toArray(), $error);
        }

        return $error;
    }

    public function beforeSave(&$formData = [], $isNews = false)
    {
        if (empty($formData['slug'])) {
            $formData['slug'] = $formData['name'];
        }

        $formData['slug'] = Str::slug($formData['slug']);

        if ($isNews) {
            $formData['creator_id'] = Auth::id() ?? 0;
            $countSlug = Setting::query()->where('slug', $formData['slug'])->count();
            if ($countSlug > 0) {
                $formData['slug'] .= '-' . $countSlug;
            }
        } else {
            $formData['editor_id'] = Auth::id() ?? 0;
        }
    }

    public function create($params)
    {
        $validator = $this->validator($params);
        if (!empty($validator)) {
            return $this->responseValidator($validator);
        }

        $this->beforeSave($params, true);
        $myObject = new Setting($params);

        if ($myObject->save($params)) {
            return $myObject;
        }

        return 0;
    }

    /**
     * @param $id
     * @param $params
     *
     * @return array|bool
     */
    public function update($id, $params)
    {
        $validator = $this->validator($params);
        if (!empty($validator)) {
            return $this->responseValidator($validator);
        }

        $this->beforeSave($params);

        $myObject = Setting::query()->findOrFail($id);
        return $myObject->update($params);
    }

    public function buildCondition($params = [], &$condition = [], &$sortBy = null, &$sortType = null)
    {
        $sortBy = 'id';
        $sortType = 'desc';

        if (!empty($params['status'])) {
            $condition['status'] = $params['status'];
        }

        if (!empty($params['search'])) {
            $search = [
                ['title', 'like', $params['search'] . '%'],
            ];

            if (empty($condition)) {
                $condition = $search;
            } else {
                $condition = array_merge($condition, $search);
            }
        }
    }

    public static function getSetting(): array
    {
        $items = Setting::all()->sortByDesc('id');

        $config = [];
        if (!empty($items)) {
            $items = $items->toArray();
            $config = array_column($items, 'value', 'name');
        }

        return $config;
    }
}
