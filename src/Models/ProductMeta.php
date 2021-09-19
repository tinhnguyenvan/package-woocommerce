<?php

namespace TinhPHP\Woocommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMeta extends Model
{
    protected $table = 'woo_product_meta';

    protected $fillable = [
        'product_id',
        'meta_key',
        'meta_value',
    ];

    public const LIST_META = [
        [
            'form' => 'input',
            'type' => 'text',
            'name' => 'link_demo',
            'default' => '',
        ]
    ];

    public $timestamps = false;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function insertOrUpdateMeta($params, $productId)
    {
        foreach (self::LIST_META as $meta) {
            $dataCondition = [
                'product_id' => $productId,
                'meta_key' => $meta['name']
            ];
            $dataValue = [
                'meta_value' => $params[$meta['name']] ?? $meta['default']
            ];
            self::query()->updateOrCreate($dataCondition, $dataValue);
        }
    }
}
