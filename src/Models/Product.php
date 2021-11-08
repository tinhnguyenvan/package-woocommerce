<?php

namespace TinhPHP\Woocommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use willvincent\Rateable\Rateable;

class Product extends Model
{
    use SoftDeletes;
    use Rateable;
    // use HasTranslations;

    public $translatable = ['title', 'summary', 'detail'];

    public const IS_HOME = 1;

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
    protected $table = 'woo_products';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'title',
        'vendor_id',
        'category_id',
        'summary',
        'detail',
        'image_id',
        'image_url',
        'barcode',
        'quantity',
        'price',
        'price_promotion',
        'is_home',
        'status',
        'slug',
        'views',
        'tags',
        'seo_title',
        'seo_description',
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
    protected $casts = [
        'price' => 'double',
        'price_promotion' => 'double',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public static function dropDownStatus(): array
    {
        $data = self::STATUS_LIST;

        $html = [];
        foreach ($data as $value) {
            $html[$value] = trans('lang_woocommerce::product.status.'.$value);
        }

        return $html;
    }

    public static function link($item)
    {
        $prefix = config('constant.URL_PREFIX_PRODUCT').'/';

        $prefix .= $item->category->slug ?? 'no-category';

        return base_url($prefix.'/'.$item->slug.'.html');
    }

    /**
     * text status.
     *
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_DISABLE:
                $text = trans('lang_woocommerce::product.status.disable');
                break;
            case self::STATUS_ACTIVE:
                $text = trans('lang_woocommerce::product.status.active');
                break;
            default:
                $text = '--';
                break;
        }

        return $text;
    }

    /**
     * color status.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_DISABLE:
                $text = 'danger';
                break;
            case self::STATUS_ACTIVE:
                $text = 'success';
                break;
            default:
                $text = 'default';
                break;
        }

        return $text;
    }

    /**
     * color status.
     *
     * @return string
     */
    public function getPriceFormatAttribute(): string
    {
        $text = '--';
        if ($this->price > 0) {
            $text = number_format($this->price);
        }

        return $text;
    }

    public function getLinkAttribute(): string
    {
        $prefix = config('constant.URL_PREFIX_PRODUCT').'/';

        $prefix .= $this->category->slug ?? 'no-category';

        return base_url($prefix.'/'.$this->slug.'.html');
    }

    /**
     * coloumn name: meta
     *
     * @return array
     */
    public function getMetaAttribute(): array
    {
        $items = ProductMeta::query()->where('product_id', $this->id)->get();

        return $items->pluck('meta_value', 'meta_key')->all();
    }

    public function getFullImageUrlAttribute(): string
    {
        if ($this->image_id > 0) {
            return asset('storage'.$this->image_url);
        } elseif (!empty($this->image_url)) {
            return $this->image_url;
        } else {
            return asset('site/img/empty.svg');
        }
    }
}
