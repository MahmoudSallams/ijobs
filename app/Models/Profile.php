<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Profile",
 *      required={"first_name", "last_name", "mobile","title"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="first_name",
 *          description="first_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="age",
 *          description="age",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mobile",
 *          description="mobile",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="other_mobile",
 *          description="other_mobile",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mobile_verify_code",
 *          description="mobile_verify_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mobile_verify_status",
 *          description="mobile_verify_status",
 *          type="integer",
 *          format="int32"
 *      ),
 *     @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="company",
 *          description="company",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="region_id",
 *          description="region_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="country_id",
 *          description="country_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="city_id",
 *          description="city_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="gender",
 *          description="gender",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="brief",
 *          description="brief",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="photo",
 *          description="photo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Profile extends Model
{
    use SoftDeletes;

    public $table = 'profiles';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'age',
        'mobile',
    	'country_code',
    	'local_mobile',
        'other_mobile',
        'mobile_verify_code',
        'mobile_verify_status',
        'title',
        'company_name',
    	'current_work_from',
    	'current_work_to',
        'workfield',
        'workfieldother',
        'school',
        'region_id',
        'country_id',
        'city_id',
        'invited_id',
        'gender',
        'brief',
        'photo',
        'status',
    	'scientific_qualifications'
    ];





    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'age' => 'integer',
    	'mobile' => 'integer',
    	'country_code' => 'string',
    	'local_mobile' => 'string',
        'other_mobile' => 'integer',
        'mobile_verify_code' => 'string',
        'mobile_verify_status' => 'integer',
        'title' => 'string',
        'company_name' => 'string',
        'region_id' => 'integer',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'workfield' => 'string',
        'workfieldother' => 'string',
        'school' => 'string',
        'invited_id'=> 'integer',
        'gender' => 'string',
        'brief' => 'string',
        'photo' => 'string',
        'status' => 'integer',
    	'scientific_qualifications' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'title' => 'required',
        'mobile' => 'required'
    ];

    public function appliedJobs()
    {
        return $this->belongsToMany('App\Models\Job','jobs_appliers')->withPivot('status');
    }

    public function joinedGroups()
    {
        return $this->belongsToMany('App\Models\Group','group_users')->withPivot('status');
    }

    public function appliedJobswithstatus1()
    {
        return $this->belongsToMany('App\Models\Job','jobs_appliers')->withPivot('status')->wherePivot('status', 1);
    }
    
    public function workExperience()
    {
    	return $this->hasMany('App\Models\WorkExperience', 'profile_id', 'id');
    }
    
}
