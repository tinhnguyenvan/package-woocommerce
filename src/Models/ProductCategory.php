<?php

namespace TinhPHP\Woocommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductCategory extends Model
{
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DISABLE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'woo_product_categories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'level',
        'description',
        'image_id',
        'image_url',
        'total_usage',
        'status',
        'is_home',
        'seo_title',
        'seo_description',
        'creator_id',
        'editor_id',
        'deleted_at',
        'created_at',
        'updated_at',
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
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'id', 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    }

    public function getAllChildren(): Collection
    {
        $sections = new Collection();

        foreach ($this->children as $section) {
            $sections->push($section);
            $sections = $sections->merge($section->getAllChildren());
        }

        return $sections;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public static function dropDownStatus(): array
    {
        $data = self::STATUS_LIST;

        $html = [];
        foreach ($data as $value) {
            $html[$value] = trans('lang_woocommerce::product.status.' . $value);
        }

        return $html;
    }

    public function getLinkAttribute(): string
    {
        $prefix = config('constant.URL_PREFIX_PRODUCT');

        return base_url($prefix . '/' . $this->slug);
    }

    /**
     * name: full_image_url
     *
     * @return string
     */
    public function getFullImageUrlAttribute(): string
    {
        if ($this->image_id > 0) {
            return asset('storage' . $this->image_url);
        } elseif (!empty($this->image_url)) {
            return $this->image_url;
        } else {
            return asset('site/img/empty.svg');
        }
    }


}
