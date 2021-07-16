<?php

namespace TinhPHP\Woocommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'woo_settings';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organization_id',
        'name',
        'slug',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
        'creator_id',
        'editor_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function getConfig(): array
    {
        $items = Setting::query()->get(['value', 'name'])->toArray();

        $config = [];
        if (!empty($items)) {
            $config = array_column($items, 'value', 'name');
        }

        return $config;
    }
}
