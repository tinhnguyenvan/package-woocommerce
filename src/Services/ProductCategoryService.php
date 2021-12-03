<?php
/**
 * @author: nguyentinh
 * @create: 11/20/19, 8:21 PM
 */

namespace TinhPHP\Woocommerce\Services;

use TinhPHP\Woocommerce\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class ProductCategoryService.
 *
 * @property ProductCategory $model
 */
class ProductCategoryService extends BaseService
{
    public function __construct(ProductCategory $model)
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
                'title' => 'required|min:2|max:255',
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
            $formData['slug'] = $formData['title'];
        }

        $formData['slug'] = Str::slug($formData['slug']);

        if ($isNews) {
            $countSlug = ProductCategory::query()->where('slug', $formData['slug'])->count();
            if ($countSlug > 0) {
                $formData['slug'] .= '-' . $countSlug;
            }
        }

        if (empty($formData['parent_id'])) {
            $formData['level'] = 0;
        } else {
            $myObject = ProductCategory::query()->find($formData['parent_id']);
            $formData['level'] = $myObject->level + 1;
        }

        if (!empty($formData['status']) && $formData['status'] == 'on') {
            $formData['status'] = 1;
        } else {
            $formData['status'] = 0;
        }

        if (!empty($formData['is_home']) && $formData['is_home'] == 'on') {
            $formData['is_home'] = 1;
        } else {
            $formData['is_home'] = 0;
        }
    }

    /**
     * @param $params
     *
     * @return array|bool|object
     */
    public function create($params)
    {
        $validator = $this->validator($params);
        if (!empty($validator)) {
            return $this->responseValidator($validator);
        }

        $this->beforeSave($params, true);
        $myObject = new ProductCategory($params);

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

        return ProductCategory::query()->findOrFail($id)->update($params);
    }

    /**
     * @param array $params
     *   - exclude array
     *
     * @return array
     */
    public function dropdown(array $params = []): array
    {
        $model = ProductCategory::query();
        if (!empty($params['exclude'])) {
            $model->whereNotIn('id', $params['exclude']);
        }

        $data = $model->orderByRaw('CASE WHEN parent_id = 0 THEN id ELSE parent_id END, parent_id,id')->get();
        $html = [];
        if (!empty($data)) {
            foreach ($data as $key => $myCategory) {
                $html[$myCategory->id] = create_line($myCategory->level) . ' ' . $myCategory->title;
            }
        }

        return $html;
    }

    /**
     * @return object
     */
    public static function itemMenu(): object
    {
        return ProductCategory::query()->orderByRaw(
            'CASE WHEN parent_id = 0 THEN id ELSE parent_id END, parent_id,id'
        )->get();
    }

    public function buildCondition($params = [], &$condition = [], &$sortBy = null, &$sortType = null)
    {
        $sortBy = 'id';
        $sortType = 'asc';

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
}
