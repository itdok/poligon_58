<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BlogCategory
 * @package App\Models
 *
 * @property-reed BlogCategory  $parentCategory
 * @property-read string        $parentTitle
 * @method static create(array|string|null $data)
 * @method static make()
 */
class BlogCategory extends Model
{
    use SoftDeletes;

    const ROOTID = 1;

    protected $fillable
        = [
            'title',
            'slug',
            'parent_id',
            'description'
        ];

    /**
     * Подключить родительскую категорию
     *
     * @return BlogCategory|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id','id');
    }

    /**
     *  Пример аксесуара (Accessor)
     *
     * @url https://laravel/com/docs/5/8/eloquent-mutators
     *
     * @return string
     */
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ? 'Кореневая категория'
                : '???');

        return $title;
    }

    /**
     * Является ли текущий объект корневым
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->id === BlogCategory::ROOTID;
    }

    /**
     * Пример аксессуара
     * @param  string $valueFromDB
     * @return bool|false|mixed|string|string[]|null
     */
    public function getTitleAttribute($valueFromObject)
    {
        return mb_strtoupper($valueFromObject);
    }

    /**
     * Пример мутатора
     * @param string $incomingValue
     */
    public function setTitleAttribute($incomingValue)
    {
        $this->attributes['title'] = mb_strtolower($incomingValue);
    }
}
