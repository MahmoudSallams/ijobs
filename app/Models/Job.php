<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Job",
 *      required={"title"},
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
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_url",
 *          description="file_url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="applied_count",
 *          description="applied_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="forwarded_count",
 *          description="forwarded_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="shared_count",
 *          description="shared_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="profile_id",
 *          description="profile_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="group_id",
 *          description="group_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="parent_id",
 *          description="parent_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *       @SWG\Property(
 *          property="contact_id",
 *          description="contact_id",
 *          type="integer",
 *          format="int32"
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
class Job extends Model
{
    use SoftDeletes;

    public $table = 'jobs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'description',
    	'image',
    	'file_url',
        'applied_count',
        'forwarded_count',
        'shared_count',
        'profile_id',
        'group_id',
        'parent_id',
        'contact_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
    	'image' => 'string',
    	'file_url' => 'string',
        'applied_count' => 'integer',
        'forwarded_count' => 'integer',
        'shared_count' => 'integer',
        'profile_id' => 'integer',
        'group_id' => 'integer',
        'parent_id' => 'integer',
        'contact_id',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        //title' => 'required'
    ];

    public function jobsAppliers()
    {
        return $this->belongsToMany('App\Models\Profile','jobs_appliers')->withPivot('status');
    }
    public function contacts()
    {
        return $this->hasOne('App\Models\Contact');
    }
    
    public function profile()
    {
    	return $this->belongsTo('App\Models\Profile', 'profile_id');
    }
    
    public static function filterJobs($jobs, $addProfile = false)
    {
    	for ($i = 0; $i < sizeof($jobs); $i++)
    	{
    		$job = $jobs[$i];
    		
    		if($job->job_user_status == JobTransaction::$STATUS_HIDDEN)
    		{
    			array_splice($jobs, $i, 1);
    			$i--;
    			continue;
    		}
    		
    		if($addProfile)
    		{
	    		if($job->profile_id != null)
	    		{
	    			$profile = Profile::findOrFail($job->profile_id);
	    			$job->profile = $profile;
	    		}
    		}
    		
    		$job = self::addGroup($job);
    	}
    	
    	return $jobs;
    }
    
    public static function addGroup($job)
    {
    	try
    	{
    		if(isset($job->group_id) && $job->group_id != null && $job->group_id > 0)
    		{
    			
    			$group = Group::where("id", "=", $job->group_id)->first();
    			
    			if($group != null)
    			{
    				$job->group = $group;
    			}
    		}
    	}
    	catch (\Exception $e)
    	{
    		//print_r($e->getMessage());
    	}
    	
    	return $job;
    }
}
