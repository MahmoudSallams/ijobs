<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProfileAPIRequest;
use App\Http\Requests\API\UpdateProfileAPIRequest;
use App\User;
use App\Models\Profile;
use App\Models\Group;
use App\Models\Job;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use JWTAuth;
use JWTAuthException;
use App\Models\JobTransaction;
use App\Models\UserContact;
use Illuminate\Support\Facades\Log;
use App\Models\WorkExperience;
use App\Models\ProfileToken;
use App\Models\Notification;
use App\Models\MessageHandler;

/**
 * Class ProfileController
 * @package App\Http\Controllers\API
 */

class ProfileAPIController extends AppBaseController
{
    /** @var  ProfileRepository */
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepository = $profileRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/profiles",
     *      summary="Get a listing of the Profiles.",
     *      tags={"Profile"},
     *      description="Get all Profiles",
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
     *                  @SWG\Items(ref="#/definitions/Profile")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
    	$profile = $this->getAuthUser();
    	if(!isset($profile->id))
    		return $profile;
        $this->profileRepository->pushCriteria(new RequestCriteria($request));
        $this->profileRepository->pushCriteria(new LimitOffsetCriteria($request));
        $profiles = $this->profileRepository->all();

        return $this->sendResponse($profiles->toArray(), 'Profiles retrieved successfully');
    }

    /**
     * @param CreateProfileAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/profiles",
     *      summary="Store a newly created Profile in storage",
     *      tags={"Profile"},
     *      description="Store Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateProfileAPIRequest $request)
	{
		/* $input = $request->all ();
		
		$profiles = $this->profileRepository->create ( $input );
		
		return $this->sendResponse ( $profiles->toArray (), 'Profile saved successfully' ); */
		$this->sendError("Unkown URL");
	}

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/profiles/{id}",
     *      summary="Display the specified Profile",
     *      tags={"Profile"},
     *      description="Get Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
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
     *                  ref="#/definitions/Profile"
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
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		/** @var Profile $profile */
		//$profile = $this->profileRepository->findWithoutFail ( $id )->with('workExperience');
		$profiles = Profile::where ( 'id', '=', $id )->with('workExperience')->get();
		$profile = $profiles[0];
		
		if (empty ( $profile ))
		{
			return $this->sendError ( 'Profile not found' );
		}
		else
		{
			return $this->sendResponse ( $profile, 'Profile retrieved successfully' );
		}
	}

	public function showProfile($lang = 'en', $profile_id, $job_id)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		/** @var Profile $profile */
		$profile = Profile::findOrFail ( $profile_id );
		if (empty ( $profile ))
		{
			return $this->sendError ( 'Profile not found' );
		}
		else
		{
			DB::table ( 'jobs_appliers' )->where ( 'job_id', $job_id )->where ( 'profile_id', $profile_id )->update ( array (
					'status' => 1 
			) );
			return $this->sendResponse ( $profile->toArray (), 'Profile retrieved successfully' );
		}
	}

    /**
     * @param int $id
     * @param UpdateProfileAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/profiles/{id}",
     *      summary="Update the specified Profile in storage",
     *      tags={"Profile"},
     *      description="Update Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdateProfileAPIRequest $request)
	{
		$profileLoggedIn = $this->getAuthUser ();
		if(!isset($profileLoggedIn->id))
		{
			return $profileLoggedIn;
		}
		$input = $request->all ();
		
		/** @var Profile $profile */
		//$profile = $this->profileRepository->findWithoutFail ( $id )->with('workExperience');
		//$profiles = Profile::where ( 'id', '=', $id )->with('workExperience')->get();
		//$profile = $profiles[0];
		
		if (is_null ( $profile->mobile_verify_status ))
			$profile->mobile_verify_status = 0;
		if (empty ( $profile ))
		{
			return $this->sendError ( 'Profile not found' );
		}
		
		if($profileLoggedIn->id == $profile->id)
		{
			$profile = $this->profileRepository->update ( $input, $id );
			
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
			
			return $this->sendResponse ( $profile->toArray (), 'Profile updated successfully' );
		}
		else 
		{
			return $this->sendError ( 'Can\'t Update someone else profile' );
		}
	}
	
	
	public function editProfile(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		$input = $request->all ();
		
		/** @var Profile $profile */
		//$profile = $this->profileRepository->findWithoutFail ( $id )->with('workExperience');
		//$profiles = Profile::where ( 'id', '=', $id )->with('workExperience')->get();
		//$profile = $profiles[0];
		
		if (is_null ( $profile->mobile_verify_status ))
		{
			$profile->mobile_verify_status = 0;
		}
		
		if (empty ( $profile ))
		{
			return $this->sendError ( 'Profile not found' );
		}
		
		$profile = $this->profileRepository->update ( $input, $profile->id );
		
		if (is_null ( $profile->mobile_verify_status ))
		{
			$profile->mobile_verify_status = 0;
		}
			
		return $this->sendResponse ( $profile->toArray (), 'Profile updated successfully' );
	}

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/profiles/{id}",
     *      summary="Remove the specified Profile from storage",
     *      tags={"Profile"},
     *      description="Delete Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
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
		$profileLoggedIn = $this->getAuthUser ();
		if(!isset($profileLoggedIn->id))
		{
			return $profileLoggedIn;
		}
		/** @var Profile $profile */
		$profile = $this->profileRepository->findWithoutFail ( $id );
		
		if (empty ( $profile ))
		{
			return $this->sendError ( 'Profile not found' );
		}
		
		$profile->delete ();
		
		return $this->sendResponse ( $id, 'Profile deleted successfully' );
	}
	
	/**
	 *
	 * @param Request $request
	 * @return Response @SWG\Post(
	 *         path="/profiles/register",
	 *         summary="Register a newly created Profile in storage",
	 *         tags={"Profile"},
	 *         description="Register Profile",
	 *         produces={"application/json"},
	 *         @SWG\Parameter(
	 *	           	name="body",
	 *	           	in="body",
	 *	          	description="Profile that should be stored",
	 *	           	required=false,
	 *         		@SWG\Schema(ref="#/definitions/Profile")
	 *         ),
	 *         @SWG\Response(
	 *	           response=200,
	 *	           description="successful operation",
	 *	           @SWG\Schema(
	 *		           type="object",
	 *		           @SWG\Property(
	 *			           property="success",
	 *			           type="boolean"
	 *			       ),
	 *		           @SWG\Property(
	 *			           property="data",
	 *			           ref="#/definitions/Profile"
	 *			       ),
	 *		           @SWG\Property(
	 *			           property="message",
	 *		          	   type="string"
	 *         			)
	 *         		)
	 *         )
	 *  )
	 */
	
	public function register(Request $request)
	{
		
		$requestData = $request->all ();
		$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->get ();
		if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
		{
			$profile = Profile::create ( $requestData );
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		else
		{
			$profile = $getProfile [0];
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		
		$profile = $this->profileRepository->update ( $requestData, $profile->id );
		//$profile = $this->profileRepository->findWithoutFail ( $profile->id )->with('workExperience');
		$profiles = Profile::where ( 'id', '=', $profile->id )->with('workExperience')->get();
		$profile = $profiles[0];
		
		if (is_null ( $profile->mobile_verify_status ))
			$profile->mobile_verify_status = 0;
		
		// print_r($profile);die();
		if (isset ( $profile->mobile ) && ! empty ( $profile->mobile ))
		{
			$u = null;
			$uList = User::where ( 'mobile', '=', $profile->mobile )->get ();
			if($uList != null && count($uList) > 0)
			{
				$u = $uList[0];
			}
			
			if($u == null || !isset($u->id) || $u->id == 0)
			{
				User::create ( [ 
						'name' => $requestData ['mobile'],
						'mobile' => $requestData ['mobile'],
						'email' => $requestData ['mobile'] . '@ijbber.com',
						'password' => bcrypt ( $requestData ['mobile'] ) 
				] );
			}
			return $this->sendResponse ( $profile->toArray (), 'Profile retrieved successfully' );
		}
		else
		{
			return $this->sendError ( 'Error Ocured' );
		}
		
		// echo json_encode($returned_json);
		// return json_encode($returned_json);
	}
	
	/**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/profiles/login",
     *      summary="Login a newly created Profile in storage",
     *      tags={"Profile"},
     *      description="Login Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
	public function login(Request $request)
	{
		$requestData = $request->all ();
		$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->get ();
		
		//print_r($request->getLocale());
		//echo 'language: ' . $request->getLocale() . '\r\n';
		if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
		{
			$profile = Profile::create ( $requestData );
			
			User::create ( [ 
					'name' => $requestData ['mobile'],
					'mobile' => $requestData ['mobile'],
					'email' => $requestData ['mobile'] . '@ijober.com',
					'password' => bcrypt ( $requestData ['mobile'] ) 
			] );
			
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		else
		{
			$profile = $getProfile [0];
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		
		$profile = $this->profileRepository->update ( $requestData, $profile->id );
		//$profile = $this->profileRepository->findWithoutFail ( $profile->id )->with('workExperience');
		$profiles = Profile::where ( 'id', '=', $profile->id )->with('workExperience')->get();
		$profile = $profiles[0];
		
		if (is_null ( $profile->mobile_verify_status ))
		{
			$profile->mobile_verify_status = 0;
		}
		
		if (isset ( $profile->mobile ) && ! empty ( $profile->mobile ))
		{
			$credentials = array (
				'mobile' => $profile->mobile 
			);
			
			$token = null;
			try
			{
				if (! $token = JWTAuth::attempt ( $credentials ))
				{
					//var_dump($token);
					return $this->sendError('Invalid Mobile', 422 );
				
				}
			}
			catch ( \Exception $e )
			{
				/* $jsonreturn ['jwttoken'] = response ()->json ( [ 
						'failed_to_create_token' 
				], 500 ); */
				
				return $this->sendError('Error: ' . $e->getMessage(), 422 );
			}
			//$jsonreturn ['token'] = $token;
			//$jsonreturn ['profile'] = $profile;
			$profile ['token'] = $token;
            //return $this->sendResponse($jsonreturn, 'Returned Token');
            return $this->sendResponse($profile, 'Returned Token');
            
        }
        /* else if($profile->mobile_verify_status == 0 )
        {
            return $this->sendResponse($profile, 'Mobile not Verified');
        } */
        else
        {
            return $this->sendError('Error Ocured');
        }
   } 
   
   /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/profiles/verifyMobile",
     *      summary="verifyMobile Mobile for created Profile in storage",
     *      tags={"Profile"},
     *      description="verifyMobile Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
   public function verifyMobileRequest(Request $request)
	{
		$requestData = $request->all ();
		//$requestData ['mobile_verify_code'] = '5555';
		$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->get ();
		
		if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
		{
			return $this->sendError ( 'Verification Failed' );
		}
		
		$profile = $getProfile [0];
		if (isset ( $profile->id ) && isset ( $profile->mobile ))
		{
			$profile->mobile_verify_status = 0;
			$verificationCode = MessageHandler::generateRandomString();
			$profile->mobile_verify_code = $verificationCode;
			$profile->save();
			MessageHandler::sendSMS($profile->mobile, $verificationCode, $request->getLocale());
			return $this->sendResponse ( $profile, 'Message Sent' );
		}
		else
		{
			return $this->sendError ( 'Error Ocured' );
		}
	}
   
   public function verifyMobile(Request $request)
	{
		$requestData = $request->all ();
		//$requestData ['mobile_verify_code'] = '5555';
		//$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->get ();
		$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->where ( 'mobile_verify_code', '=', $requestData ['mobile_verify_code'] )->get ();
		
		if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
		{
			return $this->sendError ( 'Verification Failed' );
		}
		
		$profile = $getProfile [0];
		if (isset ( $profile->id ) && isset ( $profile->mobile ))
		{
			if($requestData ['mobile_verify_code'] == $profile->mobile_verify_code)
			//if(true)
			{
				$profile->mobile_verify_status = 1;
				$profile->save ();
				
				if (isset ( $profile->mobile ) && ! empty ( $profile->mobile ))
				{
					$credentials = array (
							'mobile' => $profile->mobile 
					);
					$token = null;
					try
					{
						if (! $token = JWTAuth::attempt ( $credentials ))
						{
							$jsonreturn ['jwttoken'] = response ()->json ( [ 
									'invalid_mobile' 
							], 422 );
						}
					}
					catch ( JWTAuthException $e )
					{
						$jsonreturn ['jwttoken'] = response ()->json ( [ 
								'failed_to_create_token' 
						], 500 );
					}
					$jsonreturn ['token'] = $token;
					$jsonreturn ['profile'] = $profile;
					$profile ['token'] = $token;
				
				}
				return $this->sendResponse ( $profile, 'Mobile Verified' );
			}
			else 
			{
				return $this->sendError ( 'Incorrect Verification Code' );
			}
		}
		else
		{
			return $this->sendError ( 'Error Ocured' );
		}
	}         
	
   	/**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/profiles/invite",
     *      summary="Invite Mobile for created Profile in storage",
     *      tags={"Profile"},
     *      description="Invite Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
   	public function inviteContact(Request $request)
	{
		$requestData = $request->all ();
		$loggedInProfile = $this->getAuthUser ();
		if(!isset($loggedInProfile->id))
			return $profileLoggedIn;
		
		$mobiles = json_decode($requestData['mobiles']);
		$profiles = Profile::whereIn ( 'mobile', $mobiles )->get ();
		
		foreach($profiles as $getProfile)
		{
			if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
			{
				// send invitation sms/email
			}
		}
		
		return $this->sendResponse ( array (
				'status' => 'done'
		), 'Invitation sent' );
	}

   public function changeMobile(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		$old_mobile = $profile->mobile;
		
		$profile = $this->profileRepository->findWithoutFail ( $profile->id );
		$profile->mobile_verify_status = 0;
		$profile->mobile = $requestData ['mobile'];
		$profile->mobile_verify_code = '';
		$profile->save ();
		
		$userList = User::where('mobile', '=', $old_mobile)->get();
		if($userList != null && count($userList) > 0)
		{
			$user = $userList[0];
			$user->mobile = $requestData ['mobile'];
			$user->save();
		}
		else 
		{
			User::create ( [
					'name' => $requestData ['mobile'],
					'mobile' => $requestData ['mobile'],
					'email' => $requestData ['mobile'] . '@ijbber.com',
					'password' => bcrypt ( $requestData ['mobile'] )
			] );
		}
		
		return $this->sendResponse ( $profile, 'Message Sent' );
	
	}
   
   public function friendsDetails(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		
		$getProfiles = Profile::where ( 'invited_id', '=', $profile->id )->get ();
		if (isset ( $requestData ['status'] ))
		{
			$getProfiles = Profile::where ( 'invited_id', '=', $profile->id )->where ( 'status', '=', $requestData ['status'] )->get ();
		}
		
		if (! isset ( $getProfiles [0] ) || empty ( $getProfiles [0] ))
		{
			return $this->sendResponse ( $getProfiles, 'Mobile Verified' );
		}
		else
		{
			return $this->sendError ( 'Empty List' );
		}
	}

   public function friendsGroups(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		// //SELECT GROUP_CONCAT(id) AS idList FROM tableA
		$friends = DB::select ( 'select GROUP_CONCAT(id) AS friendsList FROM profiles where invited_id=' . $profile->id )->get ();
		$groups = DB::select ( 'select GROUP_CONCAT(id) AS groupsList FROM group_users where profile_in in ' . $friends [0] ['friendsList'] )->get ();
		$grouplistarray = explode ( ',', $groups [0] ['groupsList'] );
		$getGroups = Group::whereIn ( 'id', $grouplistarray )->get ();
		
		if (! isset ( $getGroups [0] ) || empty ( $getGroups [0] ))
		{
			return $this->sendResponse ( $getGroups, 'Listing Friends Groups' );
		}
		else
		{
			return $this->sendError ( 'Empty List' );
		}
	}

   	public function changeStatus(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		$now = time ();
		$now_sql = date_format(new \DateTime(), 'Y-m-d H:i:s');
		if ($profile)
		{
			
			//$profile = $this->profileRepository->findWithoutFail ( $loggedInProfile->id );
			$profile->status = $requestData ['status'];
			
			// Change Company Name
			if (isset ( $requestData ['company_name'] ) && ! empty ( $requestData ['company_name'] ))
			{
				
				$company_updated_at = strtotime ( $profile->company_updated_at );
				$company_updated_diff = $now - $company_updated_at;
				$company_updated_daysdiff = round ( $company_updated_diff / (60 * 60 * 24) );
				if ($company_updated_daysdiff >= 10)
				{
					$profile->company_name = $requestData ['company_name'];
					$profile->company_updated_at = $now_sql;
				}
				else
				{
					return $this->sendError ( 'You can not change Company Name Now' );
				}
			}
			
			// Change Title
			if (isset ( $requestData ['title'] ) && ! empty ( $requestData ['title'] ))
			{
				
				$title_updated_at = strtotime ( $profile->title_updated_at );
				$title_updated_diff = $now - $title_updated_at;
				$title_updated_daysdiff = round ( $title_updated_diff / (60 * 60 * 24) );
				if ($title_updated_daysdiff >= 10)
				{
					$profile->title = $requestData ['title'];
					$profile->title_updated_at = $now_sql;
				}
				else
				{
					return $this->sendError ( 'You can not change Title Now' );
				}
			}
			
			// Change Region
			if (isset ( $requestData ['region_id'] ) && ! empty ( $requestData ['region_id'] ))
			{
				
				$region_updated_at = strtotime ( $profile->region_updated_at );
				$region_updated_diff = $now - $region_updated_at;
				$region_updated_daysdiff = round ( $region_updated_diff / (60 * 60 * 24) );
				if ($region_updated_daysdiff >= 3)
				{
					$profile->region_id = $requestData ['region_id'];
					$profile->region_updated_at = $now_sql;
				}
				else
				{
					return $this->sendError ( 'You can not change Region Now' );
				}
			}
			
			// Change Country
			if (isset ( $requestData ['country_id'] ) && ! empty ( $requestData ['country_id'] ))
			{
				
				$country_updated_at = strtotime ( $profile->country_updated_at );
				$country_updated_diff = $now - $country_updated_at;
				$country_updated_daysdiff = round ( $country_updated_diff / (60 * 60 * 24) );
				if ($country_updated_daysdiff >= 3)
				{
					$profile->country_id = $requestData ['country_id'];
					$profile->country_updated_at = $now_sql;
				}
				else
				{
					return $this->sendError ( 'You can not change Region Now' );
				}
			}
			
			$profile->save ();
			
			DB::insert ( 'insert into profile_transaction (profile_id, transaction_type) values (' . $profile->id . ', 1)' );
			
			return $this->sendResponse ( $profile, 'Chnages Done' );
		
		}
		else
		{
			return $this->sendError ( 'Error Ocured' );
		}
		
	}

   	public function listAppliedJobs(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}	
		
		/* $jobs = Job::whereIn ( 'id', function ($query) use ($profile) {
			$query->select ( 'job_id' )
			->from ( 'jobs_appliers' )
			->where ( 'profile_id', '=', $profile->id );
		} )->with ( 'profile' )->get (); */
		$jobs = DB::select('select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id and jt.profile_id = ' . $profile->id . ' inner join jobs_appliers js on j.id = js.job_id where j.status != -1 and js.profile_id = ' . $profile->id . ' order by j.created_at desc');
		$jobs = Job::filterJobs($jobs, true);
		
		return $this->sendResponse ( $jobs, 'Success' );
	}
	
	public function listForwardedJobs(Request $request)
	{
		$requestData = $request->all ();
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
			return $profile;
		
   		/* $jobs = Job::whereIn('id', function($query) use ($profile)
   		{
   			$query->select('job_id')
   			->from('jobs_forwards')
   			->where('profile_id', '=', $profile->id);
   		})->with('profile')->get(); */
		$jobs = DB::select('select j.*, IFNULL(jt.status, ' . JobTransaction::$STATUS_NEW . ') job_user_status from jobs j left join job_transaction jt on j.id = jt.job_id and jt.profile_id = ' . $profile->id . ' inner join jobs_forwards js on j.id = js.job_id where j.status != -1 and js.profile_id = ' . $profile->id . ' order by js.created_at desc');
		$jobs = Job::filterJobs($jobs, true);
   		
   		return $this->sendResponse ( $jobs, 'Success' );
   	}

   	public function Applyforjob($lang = 'en', $job_id)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$job = Job::findOrFail ( $job_id );
		if($job == null)
		{
			return $this->sendError("Job Doesn't exist", 200);
		}
		
		if($job->profile_id == $profile->id)
		{
			return $this->sendError("You are the owner", 200);
		}
		
		$count = DB::select('select count(*) count from jobs_appliers where job_id = ' . $job_id . ' and profile_id = ' . $profile->id);
		//Log::debug("hhhhhhhhhhhhe");
		//var_dump($count);
		if($count[0]->count == 0)
		{
			DB::insert ( 'insert into jobs_appliers (job_id, profile_id, status, created_at) values (' . $job_id . ', ' . $profile->id . ', 0, now() )' );
			DB::insert ( 'insert into job_transaction (job_id, profile_id, status) values (' . $job_id . ', ' . $profile->id . ', ' . JobTransaction::$STATUS_WAITING . ' ) on duplicate key update status = ' . JobTransaction::$STATUS_WAITING );
			
			$job->applied_count++;
			$job->update();
			
			try 
			{
				$message = $profile->first_name . " " . $profile->last_name . " has applied to a new job";
				ProfileToken::sendNotification($job->profile_id, $message, ProfileToken::$ACTION_TYPE_OPEN_JOB, array($job_id));
			}
			catch(\Exception $ex)
			{
				Log::error($ex->getMessage() . ": " . json_encode($ex));
			}
			
			return $this->sendResponse ( array (
					'Data' => 'Success' 
			), 'Job Applied Sucessfully' );
		}
		else 
		{
			return $this->sendError("You already applied to this job", 200);
		}
	}

    public function getProfileBymobile(Request $request)
    {
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
		$requestData = $request->all ();
		$getProfile = Profile::where ( 'mobile', '=', $requestData ['mobile'] )->get ();
		/* if (! isset ( $getProfile [0] ) || empty ( $getProfile [0] ))
		{
			$profile = Profile::create ( $requestData );
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		else
		{
			$profile = $getProfile [0];
			if (is_null ( $profile->mobile_verify_status ))
				$profile->mobile_verify_status = 0;
		}
		$profile = $this->profileRepository->findWithoutFail ( $profile->id );
		if (is_null ( $profile->mobile_verify_status ))
			$profile->mobile_verify_status = 0;
		*/
		if (isset ( $getProfile->mobile ) && ! empty ( $getProfile->mobile ))
		{
			return $this->sendResponse ( $getProfile->toArray (), 'Profile retrieved successfully' );
		}
		else
		{
			return $this->sendError ( 'No data Found' );
		} 
		//return $this->sendResponse ( $getProfile->toArray (), 'Profile retrieved successfully' );
    }

	public function syncContacts(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		try 
		{
			$requestData = $request->all ();
			$mobiles = json_decode($requestData['contacts']);
			$append = (isset($requestData['append']) && $requestData['append'] == "true" ? true : false);
			//$mobiles = str_replace("]", "", $mobiles);
			
			//$contacts = Profile::whereIn("mobile", $mobiles)->get();
			
			if(!$append)
			{
				DB::update('delete from user_contacts where profile_id = ' . $profile->id);
			}
			
			$contacts_new = array();
			//foreach($contacts as $contact)
			foreach($mobiles as $mobile)
			{
				//$contact = Profile::where("mobile", "like", "%$mobile%")->first();
				$contact = Profile::where("mobile", "=", "$mobile")->orWhere('local_mobile', '=', "$mobile")->first();
				
				if($contact == null && substr($mobile, 0, 2) === "05")
				{
					$contact = Profile::where("local_mobile", "=", substr($mobile, 1, strlen($mobile)))->first();
				}
				
				if($contact != null)
				{
					$c = new \stdClass();
					$c->id = $contact->id;
					$c->first_name = $contact->first_name;
					$c->last_name = $contact->last_name;
					$c->email = $contact->email;
					$c->mobile = $contact->mobile;
					$c->image = $contact->image;
					$c->status = $contact->status;
					
					array_push($contacts_new, $c);
					
					$uc = new UserContact();
					$uc->profile_id = $profile->id;
					$uc->user_id = $contact->id;
					$uc->save();
				}
			}
			
			//ProfileToken::sendNotification($profile->id, "sync done", ProfileToken::$ACTION_TYPE_OPEN_JOB, array(7));
			
			return $this->sendResponse($contacts_new, 'Success');
		}
		catch(\Exception $ex)
		{
			Log::error($ex->getMessage() . ": " . json_encode($ex));
			return $this->sendError('Error: ' . $ex->getMessage());
		}
	}
	
	public function changeToken(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		try
		{
			$requestData = $request->all ();
			$token = $requestData['token'];
			$device_type = $requestData['device_type'];
			
			$need_change = true;
			
			$pts = ProfileToken::where('profile_id', '=', $profile->id)->where('device_type', '=', $device_type)->where('is_active', '=', true)->get();
			if($pts != null && count($pts) > 0)
			{
				$pt = $pts[0];
				if($pt->token == $token)
				{
					$need_change = false;
				}
			}
			
			if($need_change)
			{
				DB::update('update profile_token set is_active = 0 where profile_id = ' . $profile->id . ' and device_type = ' . $device_type);
				
				$pt = new ProfileToken();
				
				$pt->profile_id = $profile->id;
				$pt->device_type = $device_type;
				$pt->token = $token;
				$pt->is_active = true;
				$pt->save();
			}
			
			return $this->sendResponse ( array (
					'Data' => 'Success'
			), 'Chnages Done' );
		}
		catch(\Exception $ex)
		{
			Log::error($ex->getMessage() . ": " . json_encode($ex));
			return $this->sendError('Error: ' . $ex->getMessage());
		}
	}
	
	public function getStatusHistory(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$profiles = Profile::whereIn ( 'id', function ($query) use ($profile)
		{
			$query->select ( 'user_id' )
			->from ( 'user_contacts' )
			->where('profile_id', '=', $profile->id);
		})
		->whereIn ( 'id', function ($query)
		{
			$query->select ( 'profile_id' )
			->from ( 'profile_transaction' )
			->where('transaction_type', '=', 1)
			->where('created_at', '>=', '(CURDATE() - INTERVAL 7 DAY)');
		})
		->get ();
		
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
		}
		
		return $this->sendResponse($profiles_new, 'Success');
	}
	
	public function getNewStatusContacts(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$profiles = Profile::whereIn ( 'id', function ($query) use ($profile)
		{
			$query->select ( 'user_id' )
			->from ( 'user_contacts' )
			->where('profile_id', '=', $profile->id);
		})
		->whereIn ( 'id', function ($query)
		{
			$query->select ( 'profile_id' )
			->from ( 'profile_transaction' )
			->where('transaction_type', '=', 1)
			->where('created_at', '>=', '(CURDATE() - INTERVAL 7 DAY)')
			->distinct();
		})
		->get ();
		
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
		}
		
		return $this->sendResponse($profiles_new, 'Success');
	}
	
	public function updateWorkExperience(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
		{
			return $profile;
		}
		
		$requestData = $request->all ();
		$workExperienceArr = json_decode($requestData['work_experience']);
		
		DB::update('delete from work_experience where profile_id = ' . $profile->id);
		
		foreach ($workExperienceArr as $we)
		{
			$we_ = new WorkExperience();
			$we_->profile_id = $profile->id;
			$we_->title = (isset($we->title) ? $we->title : '');
			$we_->company_name = (isset($we->company_name) ? $we->company_name : '');
			$we_->from = (isset($we->from) ? $we->from : null);
			$we_->to = (isset($we->to) ? $we->to : null);
			
			$we_->save();
		}
		
		return $this->sendResponse ( array (
				'Data' => 'Success'
		), 'Chnages Done' );
	}
	
	public function getCounts($lang = 'en')
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
		{
			return $profile;
		}
		
		$counts = DB::select('select count(*) count, status from profiles where id in ( select user_id from user_contacts where profile_id = ' . $profile->id . ') group by status');
		
		$result = new \stdClass();
		$result->working_count = 0;
		$result->looking_job_count = 0;
		$result->free_count = 0;
		
		$count_all = 0;
		foreach ($counts as $count)
		{
			if($count->status == 1)
			{
				$result->working_count = $count->count;
			}
			else if($count->status == 2)
			{
				$result->free_count = $count->count;
			}
			else if($count->status == 3)
			{
				$result->looking_job_count = $count->count;
			}
			$count_all += $count->count;
		}
		
		$new_status_count = DB::select('select count(distinct(profile_id)) count from profile_transaction where profile_id in (select user_id from user_contacts where profile_id = ' . $profile->id .') and transaction_type = 1 and created_at >= (CURDATE() - INTERVAL 7 DAY)');
		$result->new_status_count = $new_status_count[0]->count;
		
		$result->all_contacts = $count_all;
		return $this->sendResponse ( $result, 'Success' );
	}
	
	public function getNotifications($lang = 'en')
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
		{
			return $profile;
		}
		
		$notifications_db = Notification::where('profile_id', $profile->id)
		->where('success', 1)
		->orderBy('created_at', 'desc')
		->take(20)
		->get();
		
		$notifications = array();
		foreach ($notifications_db as $notification)
		{
			$n = new \stdClass();
			//$n->profile_id = $notification->profile_id;
			$n->body = $notification->body;
			$n->action_type = $notification->action_type;
			$n->action_value = json_decode($notification->action_value);
			$n->is_new = $notification->is_new;
			$n->created_at = strval($notification->created_at)	;
			
			array_push($notifications, $n);
		}
		
		DB::table ( 'notifications' )->where ( 'profile_id', $profile->id )->update ( array (
				'is_new' => false
		) );
		
		return $this->sendResponse ( $notifications, 'Success' );
	}
}
