<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateJobAPIRequest;
use App\Http\Requests\API\UpdateJobAPIRequest;
use App\Models\Job;
use App\Models\Profile;
use App\Models\ProfileToken;
use App\Repositories\JobRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Http\Controllers\JobController;
use App\Models\JobTransaction;
use Illuminate\Support\Facades\Log;
use App\Models\GroupUser;

/**
 * Class JobController
 * @package App\Http\Controllers\API
 */

class JobAPIController extends AppBaseController
{
    /** @var  JobRepository */
    private $jobRepository;

    public function __construct(JobRepository $jobRepo)
    {
        $this->jobRepository = $jobRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobs",
     *      summary="Get a listing of the Jobs.",
     *      tags={"Job"},
     *      description="Get all Jobs",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Job")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     * get all jobs
     */
    public function index(Request $request)
	{
		$input = $request->all ();
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$this->jobRepository->pushCriteria ( new RequestCriteria ( $request ) );
		$this->jobRepository->pushCriteria ( new LimitOffsetCriteria ( $request ) );
		if (isset ( $input ['group_id'] ) && ! empty ( $input ['group_id'] ))
		{
			//$jobs_ids = DB::select ( 'select GROUP_CONCAT(id) AS jobsList FROM group_jobs where invited_id=' . $input ['group_id'] )->get ();
			//$jobs_idsarray = explode ( ',', $jobs_ids [0] ['jobsList'] );
			//// $jobs = $this->jobRepository->whereIn('id',$jobs_idsarray)->where('status','!=',-1)->all();
			//$jobs = Job::whereIn ( 'id', $jobs_idsarray )->where ( 'status', '!=', - 1 )->with ( 'profile' )->get ();
			
			//$jobs = DB::select ( 'select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id where j.status != -1 and j.deleted_at is null and j.id in (select job_id from group_jobs where group_id = ' . $input ['group_id'] . ') order by j.created_at desc' );
			//$jobs = Job::filterJobs($jobs, true);
			
			$jobs = $this->getJobs($profile, $input['group_id']);
		}
		else
		{
			/* $jobs = Job::whereNotIn('id', function($query) use ($profile)
			{
				$query->select('job_id')
				->from('job_transaction')
				->where('profile_id', '=', $profile->id)
				->where('status', '!=', JobTransaction::$STATUS_HIDDEN);
			})->where ( 'status', '!=', - 1 )->with('profile')->get(); */
			
			//$jobs = DB::select ( 'select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id where j.status != -1 and j.deleted_at is null and (group_id = 0 or group_id is null or j.id in (select job_id from group_jobs where group_id in (select group_id from group_users where profile_id = ' . $profile->id . ' and status = ' . GroupUser::$STATUS_JOINED . '))) order by j.created_at desc' );
			//$jobs = Job::filterJobs($jobs, true);
			
			$jobs = $this->getJobs($profile);
		}
		
		return $this->sendResponse ( $jobs, 'Jobs retrieved successfully' );
	}
	
	private function getJobs($profile, $group_id = 0)
	{
		if ($group_id > 0)
		{
			$jobs = DB::select ( 'select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id where j.status != -1 and j.deleted_at is null and j.id in (select job_id from group_jobs where group_id = ' . $group_id . ') order by j.created_at desc' );
			$jobs = Job::filterJobs($jobs, true);
		}
		else
		{
			$jobs = DB::select ( 'select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id where j.status != -1 and j.deleted_at is null and (group_id = 0 or group_id is null or j.id in (select job_id from group_jobs where group_id in (select group_id from group_users where profile_id = ' . $profile->id . ' and status = ' . GroupUser::$STATUS_JOINED . '))) order by j.created_at desc' );
			$jobs = Job::filterJobs($jobs, true);
		}
		
		return $jobs;
	}
	
	public function search(Request $request)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
			return $profile;
		
		$this->jobRepository->pushCriteria ( new RequestCriteria ( $request ) );
		$this->jobRepository->pushCriteria ( new LimitOffsetCriteria ( $request ) );
		$jobs = Job::where ( 'description', 'like', '%' . $requestData ['search'] . '%' )->with ( 'profile' )->find ();
		
		$jobs = Job::filterJobs($jobs);
		
		return $this->sendResponse ( $jobs->toArray (), 'Jobs retrieved successfully' );
	}

    /**
     * @param CreateJobAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/jobs",
     *      summary="Store a newly created Job in storage",
     *      tags={"Job"},
     *      description="Store Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Job that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Job")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     * post job
     */
    public function store(CreateJobAPIRequest $request)
	{
    	//print_r($request);
    	
    	$input = $request->all ();
    	$profile = $this->getAuthUser();
    	if(!isset($profile->id))
    		return $profile;
		
		if(!isset($input['title']) || empty($input['title']))
			$input['title'] = "";
		
		$input['profile_id'] = $profile->id;
		
		$jobs = $this->jobRepository->create ( $input );
		if (isset ( $input ['group_id'] ) && ! empty ( $input ['group_id'] ))
		{
			$job_id = $jobs->id;
			DB::insert ( 'insert into group_jobs (job_id, group_id, status, created_at) values (' . $job_id . ', ' . $input ['group_id'] . ', 1, now() )' );
		}
		
		$job = Job::findOrFail ( $jobs->id );
		$job = Job::addGroup($job);
		
		return $this->sendResponse ( $job->toArray (), 'Job saved successfully' );
	}

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobs/{id}",
     *      summary="Display the specified Job",
     *      tags={"Job"},
     *      description="Get Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($lang = 'en', $id)
    {
    	$profile = $this->getAuthUser();
    	if(!isset($profile->id))
    		return $profile;
    	
    	$job = Job::with ( 'profile' )->findOrFail ( $id );
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		
		$job = Job::addGroup($job);
		
		/* $jt = new JobTransaction();
		$jt->job_id = $job->id;
		$jt->profile_id = $profile->id;
		$jt->status = JobTransaction::$STATUS_SEEN;
		$jt->save(); */
		
		//DB::insert('insert into job_transaction (job_id, profile_id, status) values (' . $job->id . ', ' . $profile->id . ', ' . JobTransaction::$STATUS_SEEN . ') on duplicate key update status = status');
		
		return $this->sendResponse ( $job->toArray (), 'Job retrieved successfully' );
	}
	
	public function myPublishedJobs($lang = 'en')
	{
		
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		/** @var Job $job */
		//$job = Job::where ( 'profile_id', '=', $profile->id )->where ( 'status', '=', 1 )->with('jobsAppliers')->orderBy('created_at', 'desc')->get ();
		$job = Job::where ( 'profile_id', '=', $profile->id )->with('jobsAppliers')->orderBy('created_at', 'desc')->get ();
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		
		foreach ($job as $j)
		{
			$j = Job::addGroup($j);
		}
		
		return $this->sendResponse ( $job->toArray (), 'Job retrieved successfully' );
	}
	
	public function deactivatejob($lang = 'en', $job_id)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$job = $this->jobRepository->findWithoutFail ( $job_id );
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		else
		{
			if($job->profile_id == $profile->id)
			{
				Job::where ( 'id', $job_id )->update ( array ( 'status' => -1 ) );
				
				$jaList = DB::table('jobs_appliers')->where('job_id', $job_id )->get();
				foreach ($jaList as $ja)
				{
					ProfileToken::sendNotification($ja->profile_id, "The Job has been closed", ProfileToken::$ACTION_TYPE_NONE, array());
				}
				
				return $this->sendResponse ( array ( 'Done' => 'True' ), 'Job Blocked' );
			}
			else 
			{
				return $this->sendError ( 'You are not the owner to perform this action' );
			}
		}
	}
	
	public function removeJobFromGroup($lang = 'en', $job_id, $group_id)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		Log::info("job id: $job_id");
		Log::info("group id: $group_id");
		
		
		$job = $this->jobRepository->findWithoutFail ( $job_id );
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		else
		{
			if($job->profile_id == $profile->id)
			{
				Job::where ( 'group_id', $group_id )->where ( 'id', $job_id )->delete ();
				return $this->sendResponse ( array ( 'Done' => 'True' ), 'Job Remove from Group' );
			}
			else
			{
				return $this->sendError ( 'You are not the owner to perform this action' );
			}
		}
	}

    /**
     * @param int $id
     * @param UpdateJobAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/jobs/{id}",
     *      summary="Update the specified Job in storage",
     *      tags={"Job"},
     *      description="Update Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Job that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Job")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Job"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateJobAPIRequest $request)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
			return $profile;
		$input = $request->all ();
		
		/** @var Job $job */
		$job = $this->jobRepository->findWithoutFail ( $id );
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		
		if($job->profile_id == $profile->id)
		{
			
			$job = $this->jobRepository->update ( $input, $id );
			return $this->sendResponse ( $job->toArray (), 'Job updated successfully' );
		}
		else
		{
			return $this->sendError ( 'You are not the owner to perform this action' );
		}
	}

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/jobs/{id}",
     *      summary="Remove the specified Job from storage",
     *      tags={"Job"},
     *      description="Delete Job",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Job",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
	public function destroy($lang = 'en', $id)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		/** @var Job $job */
		$job = $this->jobRepository->findWithoutFail ( $id );
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		
		if ($job->profile_id == $profile->id)
		{
			$job->delete ();
			
			$jaList = DB::table('jobs_appliers')->where('job_id', $id )->get();
			foreach ($jaList as $ja)
			{
				ProfileToken::sendNotification($ja->profile_id, "The Job has been deleted", ProfileToken::$ACTION_TYPE_NONE, array());
			}
			
			return $this->sendResponse ( $id, 'Job deleted successfully' );
		}
		else
		{
			return $this->sendError ( 'You are not the owner to perform this action');
		}
	}
	
	public function forwardJob($lang = 'en', $job_id, $profile_id)
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
			return $profile;
		
		$job = $this->jobRepository->findWithoutFail ( $job_id );
		
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		else
		{
			DB::insert ( 'insert into jobs_forwards (job_id, profile_id, created_at ) values (' . $job_id . ', ' . $profile_id . ', now())' );
			
			$job->forwarded_count++;
			$job->update();
			
			ProfileToken::sendNotification($profile_id, "New Job Forwarded to you", ProfileToken::$ACTION_TYPE_OPEN_JOB, array($job_id));
			
			return $this->sendResponse ( array (
					'Data' => 'Success'
			), 'Forward Done' );
    	}
	}
	
	public function hideJob($lang = 'en', $job_id)
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
			return $profile;
			
			$job = $this->jobRepository->findWithoutFail ( $job_id );
			
			if (empty ( $job ))
			{
				return $this->sendError ( 'Job not found' );
			}
			else
			{
				DB::insert('insert into job_transaction (job_id, profile_id, status) values (' . $job->id . ', ' . $profile->id . ', ' . JobTransaction::$STATUS_HIDDEN . ') on duplicate key update status = ' . JobTransaction::$STATUS_HIDDEN);
				
				return $this->sendResponse ( array (
						'Data' => 'Success'
				), 'Chnages Done' );
			}
	}
	
	public function listAppliers($lang = 'en', $job_id)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$job = Job::findOrFail ( $job_id );
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		
		$profiles = Profile::whereIn ( 'id', function ($query) use ($job_id) 
		{
			$query->select ( 'profile_id' )
			->from ( 'jobs_appliers' )
			->where ( 'job_id', '=', $job_id );
		} )->get ();
		
		$profiles_new = array();
		foreach($profiles as $profile_new)
		{
			$c = new \stdClass();
			$c->id = $profile_new->id;
			$c->first_name = $profile_new->first_name;
			$c->last_name = $profile_new->last_name;
			$c->email = $profile_new->email;
			$c->mobile = $profile_new->mobile;
			$c->image = $profile_new->image;
			$c->status = $profile_new->status;
			
			if($c->status == 1)
			{
				$c->title = $profile_new->title;
				$c->company_name = $profile_new->company_name;
			}
			
			array_push($profiles_new, $c);
			
			// if the viewer is the owner of the job
			if($job->profile_id == $profile->id)
			{
				DB::insert('insert into job_transaction (job_id, profile_id, status) values (' . $job->id . ', ' . $c->id . ', ' . JobTransaction::$STATUS_SEEN . ') on duplicate key update status = ' . JobTransaction::$STATUS_SEEN . '');
			}
		}
		
		//DB::table ( 'jobs_appliers' )->where ( 'job_id', $job_id )->update ( array ('status' => 1) );
		return $this->sendResponse ( $profiles_new, 'Profiles retrieved successfully' );
	}
	
	public function getCounts()
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
		{
			return $profile;
		}
			
		$new_count = DB::select ( 'select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id where j.status != -1 and j.deleted_at is null and (group_id = 0 or group_id is null or j.id in (select job_id from group_jobs where group_id in (select group_id from group_users where profile_id = ' . $profile->id . ' and status = ' . GroupUser::$STATUS_JOINED . '))) order by j.created_at desc' );
		$refered_count = DB::select('select count(*) count from jobs_forwards where profile_id = ' . $profile->id . ' and deleted_at is null and job_id in (select id from jobs where deleted_at is null and status != -1)');
		$applied_count = DB::select('select count(*) count from jobs_appliers where profile_id = ' . $profile->id . ' and deleted_at is null and job_id in (select id from jobs where deleted_at is null and status != -1)');
		$published_count = DB::select('select count(*) count from jobs where profile_id = ' . $profile->id . ' and deleted_at is null and status != -1');
		$all_count = DB::select('select count(*) count from jobs where deleted_at is null and status != -1');
		
		$result = new \stdClass();
		$result->new_count = count($new_count);
		$result->refered_count = $refered_count[0]->count;
		$result->applied_count = $applied_count[0]->count;
		$result->published_count = $published_count[0]->count;
		$result->all_count = $all_count[0]->count;
		
		return $this->sendResponse ( $result, 'Success' );
	}
	
	public function repostjob($lang = 'en', $job_id)
	{
		$profile = $this->getAuthUser();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$job = $this->jobRepository->findWithoutFail ( $job_id );
		if (empty ( $job ))
		{
			return $this->sendError ( 'Job not found' );
		}
		else
		{
			if($job->profile_id == $profile->id)
			{
				Job::where ( 'id', $job_id )->update ( array ( 'created_at' => date('Y-m-d h:i:s') ) );
				return $this->sendResponse ( array ( 'Done' => 'True' ), 'Job Reposted Successfully' );
			}
			else
			{
				return $this->sendError ( 'You are not the owner to perform this action' );
			}
		}
	}
}
