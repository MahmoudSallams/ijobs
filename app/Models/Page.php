<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Page",
 *      required={"title", "slug"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      )
 * )
 */
class Page extends Model
{
    use SoftDeletes;
    
    public static $PAGE_ABOUT_US_ID_EN = 1;
    public static $PAGE_ABOUT_US_ID_AR = 2;
    public static $PAGE_PRIVACY_POLICY_ID_EN = 3;
    public static $PAGE_PRIVACY_POLICY_ID_AR = 4;
    public static $PAGE_FAQ_ID_EN = 5;
    public static $PAGE_FAQ_ID_AR = 6;

    public $table = 'pages';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'slug',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'slug' => 'required'
    ];

    
}
