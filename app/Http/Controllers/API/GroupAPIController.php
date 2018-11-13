<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGroupAPIRequest;
use App\Http\Requests\API\UpdateGroupAPIRequest;
use App\Models\Group;
use App\Repositories\GroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use App\Models\GroupUser;
use PhpParser\Node\Stmt\GroupUse;
use App\Models\Profile;
use App\Models\ProfileToken;

/**
 * Class GroupController
 * @package App\Http\Controllers\API
 */

class GroupAPIController extends AppBaseController
{
    /** @var  GroupRepository */
    private $groupRepository;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepository = $groupRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/groups",
     *      summary="Get a listing of the Groups.",
     *      tags={"Group"},
     *      description="Get all Groups",
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
     *                  @SWG\Items(ref="#/definitions/Group")
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
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
    	
        $this->groupRepository->pushCriteria ( new RequestCriteria ( $request ) );
		$this->groupRepository->pushCriteria ( new LimitOffsetCriteria ( $request ) );
		
		$groups = Group::whereIn ( 'id', function ($query) use ($profile) 
		{
			$query->select ( 'group_id' )
			->from ( 'group_users' )
			->where(function ($query_2) use ($profile)
			{
				$query_2->whereIn ( 'profile_id', function ($query_1) use ($profile)
				{
					$query_1->select ( 'user_id' )
					->from ( 'user_contacts' )
					->where ( 'profile_id', '=', $profile->id );
				})
				->orWhere('profile_id', '=', $profile->id);
			})
			->where ( 'status', '=', GroupUser::$STATUS_JOINED );
		})
		->where ( 'status', '!=', -1 )->get ();
		
		//$groups = Group::where ( 'status', '!=', - 1 )->get ();
		
		$isAdmin = false;
		foreach($groups as $g)
		{
			foreach ($g->groupUsers as $gu)
			{
				if($gu->profile_id == $profile->id && $gu->is_admin == 1)
				{
					$isAdmin = true;
				}	
			}
			$g->isAdmin = $isAdmin;
			unset($g->groupUsers);
		}
		
		return $this->sendResponse ( $groups, 'Groups retrieved successfully' );
	}
	
	public function search(Request $request)
	{
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		$requestData = $request->all ();
		$this->groupRepository->pushCriteria ( new RequestCriteria ( $request ) );
		$this->groupRepository->pushCriteria ( new LimitOffsetCriteria ( $request ) );
		
		if (isset ( $requestData['search']) && !empty($requestData['search']))
		{
            $groups = Group::where('name', 'like', '%'.$requestData['search'].'%')->get();
        }
        else
        {
            $groups = Group::get();
        }
        

        return $this->sendResponse($groups->toArray(), 'Groups retrieved successfully');
    }

    /**
     * @param CreateGroupAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/groups",
     *      summary="Store a newly created Group in storage",
     *      tags={"Group"},
     *      description="Store Group",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Group that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Group")
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
     *                  ref="#/definitions/Group"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateGroupAPIRequest $request)
    {
    	$input = $request->all();
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
        $group = $this->groupRepository->create($input);
        
        $gu = new GroupUser();
        $gu->group_id = $group->id;
        $gu->profile_id = $profile->id;
        $gu->is_admin = true;
        $gu->status = GroupUser::$STATUS_JOINED;
        $gu->save();
        
        $group->members_counts = 1;
        $group->save();

        return $this->sendResponse($group, 'Group saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/groups/{id}",
     *      summary="Display the specified Group",
     *      tags={"Group"},
     *      description="Get Group",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Group",
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
     *                  ref="#/definitions/Group"
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
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
        $group = Group::with('members')->findOrFail($id);

        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }
        
        $isAdmin = false;
        foreach ($group->groupUsers as $gu)
        {
        	if($gu->profile_id == $profile->id && $gu->is_admin == 1)
        	{
        		$isAdmin = true;
        	}
        }
        $group->isAdmin = $isAdmin;
        //unset($group->groupUsers);

        return $this->sendResponse($group->toArray(), 'Group retrieved successfully');
    }

    public function addMemberToGroup($lang = 'en', $group_id, Request $request)
    {
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
    	
        $group = $this->groupRepository->findWithoutFail($group_id);
        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }
        else
        {
        	$requestData = $request->all();
        	$contacts = json_decode($requestData['contacts']);
        	
        	foreach ($contacts as $contact)
        	{
        		$gu = GroupUser::where('group_id', '=', $group_id)->where('profile_id', '=', $contact)->first();
        		
        		//var_dump($gu);
        		if($gu == null)
        		{
        			$gu = new GroupUser();
        			$gu->group_id = $group_id;
        			$gu->profile_id = $contact;
        			$gu->is_admin = false;
        			$gu->status = GroupUser::$STATUS_JOINED;
        			$gu->save();
	            	
	            	$group->members_counts = $group->members_counts + 1;
	            	$group->save();
        		}
        		else 
        		{
        			if($gu->status != GroupUser::$STATUS_JOINED)
        			{
	        			$gu->status = GroupUser::$STATUS_JOINED;
	        			$gu->save();
	        			
	        			$group->members_counts = $group->members_counts + 1;
	        			$group->save();
        			}
        		}
        		
        		ProfileToken::sendNotification($contact, "You have been added to a group", ProfileToken::$ACTION_TYPE_OPEN_GROUP, array($group_id));
        	}
            
        	
            return $this->sendResponse(array('Done'=>'True'), 'Members Added Successfully');
        }
    }
    
    public function requestJoinGroup($lang = 'en', $group_id, Request $request)
    {
    	$requestData = $request->all ();
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
    	
    	$group = $this->groupRepository->findWithoutFail($group_id);
    	if($group == null)
    	{
    		return $this->sendError('Group not found');
    	}
    	
    	$gu = GroupUser::where('group_id', '=', $group_id)->where('profile_id', '=', $profile->id)->first();
    	
    	if($gu == null)
    	{
	    	//DB::insert ( 'insert into group_users (group_id , profile_id ,status) values (' . $group_id . ', ' . $profile->id . ', ' . GroupUser::$STATUS_PENDING_APPROVAL . ')' );
	    	$gu = new GroupUser();
	    	$gu->group_id = $group_id;
	    	$gu->profile_id = $profile->id;
	    	$gu->is_admin = false;
	    	$gu->status = GroupUser::$STATUS_PENDING_APPROVAL;
	    	$gu->save();
    	}
    	else 
    	{
    		$gu->status = GroupUser::$STATUS_PENDING_APPROVAL;
    		$gu->save();
    	}
    	
    	ProfileToken::sendNotification($group->profile_id, "New Join Request", ProfileToken::$ACTION_TYPE_OPEN_GROUP, array($group_id));
    	
    	return $this->sendResponse ( array (
    			'Data' => 'Success'
    	), 'Join Request Sent Successfully' );
    }
    
    public function leaveGroup($lang = 'en', $group_id)
    {
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
    		
    	
    	$group = $this->groupRepository->findWithoutFail($group_id);
    	if($group == null)
    	{
    		return $this->sendError('Group not found');
    	}
    	
    	DB::table ( 'group_users' )->where ( 'group_id', $group_id )->where ( 'profile_id', $profile->id )->delete ();
    	
    	return $this->sendResponse ( array (
    			'Done' => 'True'
    	), 'Group Left Successfully' );
    }
    
    public function getJoinRequests($lang = 'en', $group_id)
    {
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    	{
    		return $profile;
    	}
    	
    	echo "groupId: $group_id <br />";
    		
    	$group = $this->groupRepository->findWithoutFail($group_id);
    	if($group == null)
    	{
    		return $this->sendError('Group not found');
    	}
    	
    	$profiles = array();
    	$guList = GroupUser::where('status', '=', GroupUser::$STATUS_PENDING_APPROVAL)->where('updated_at', '<=', 'now() - INTERVAL 1 DAY')->get();
    	
    	foreach($guList as $gu)
    	{
    		$profile_ = Profile::where('id', '=', $gu->profile_id)->first();
    		if($profile_ != null)
    		{
    			array_push($profiles, $profile_);
    		}
    	}
    	
    	return $this->sendResponse ( $profiles, 'Success' );
    }
    
    public function approveJoinRequest($lang = 'en', $group_id, $profile_id)
    {
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
    		
    	$group = $this->groupRepository->findWithoutFail($group_id);
    	if($group == null)
    	{
    		return $this->sendError('Group not found');
		}
		
		$gu = GroupUser::where ( 'group_id', '=', $group_id )->where ( 'profile_id', '=', $profile_id )->first ();
		
		// var_dump($gu);
		$gu->status = GroupUser::$STATUS_JOINED;
		$gu->save ();
		
		$group->members_counts = $group->members_counts + 1;
		$group->save ();
		
		ProfileToken::sendNotification($profile_id, "Request to join group is approved", ProfileToken::$ACTION_TYPE_OPEN_GROUP, array($group_id));
		
		return $this->sendResponse ( array('Done' => 'True'), 'Success' );
	}
	
	public function rejectJoinRequest($lang = 'en', $group_id, $profile_id)
	{
		$profile = $this->getAuthUser ();
		if (! isset ( $profile->id ))
			return $profile;
		
		$group = $this->groupRepository->findWithoutFail ( $group_id );
		if ($group == null)
		{
			return $this->sendError ( 'Group not found');
    	}
    	
    	//$gu = GroupUser::where('group_id', '=', $group_id)->where('profile_id', '=', $profile_id)->first();
    	//$gu->delete();
    	DB::table ( 'group_users' )->where ( 'group_id', $group_id )->where ( 'profile_id', $profile_id )->delete ();
    	
    	ProfileToken::sendNotification($profile_id, "Request to join group is rejected", ProfileToken::$ACTION_TYPE_NONE, array());
    	
    	return $this->sendResponse ( array('Done' => 'True'), 'Success' );
    }
    
    public function blockMember($lang = 'en', $group_id,$profile_id)
    {
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
        $group = $this->groupRepository->findWithoutFail($group_id);
        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }
        else
        {
            DB::table('group_users')->where('group_id', $group_id )->where ( 'profile_id', $profile_id )->update ( array (
					'status' => - 1 
			) );
			
			$group->members_counts = $group->members_counts - 1;
			$group->save ();
			
			return $this->sendResponse ( array (
					'Done' => 'True' 
			), 'Invitation Blocked' );
		}
	}
	
	public function deactivategroup($lang = 'en', $group_id)
	{
		/** @var Group $group */
		$profile = $this->getAuthUser ();
		if(!isset($profile->id))
			return $profile;
		$group = $this->groupRepository->findWithoutFail ( $group_id );
		if (empty ( $group ))
		{
			return $this->sendError ( 'Group not found' );
        }
        else
        {
            DB::table('groups')->where('id', $group_id)->update(array('status' => -1));
            
            return $this->sendResponse(array('Done'=>'True'), 'Group Closed Successfully');
        }
    }

    public function reinviteMember($lang = 'en', $group_id,$profile_id)
    {
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;
        $group = $this->groupRepository->findWithoutFail($group_id);
        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }
        else
        {
            //DB::table('group_users')->where('group_id',$group_id)->where('profile_id',$profile_id)->update(array('status'=>0));
            return $this->sendResponse(array('Done'=>'True'), 'Re Invitation Sent');
        }
    }


    /**
     * @param int $id
     * @param UpdateGroupAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/groups/{id}",
     *      summary="Update the specified Group in storage",
     *      tags={"Group"},
     *      description="Update Group",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Group",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Group that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Group")
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
     *                  ref="#/definitions/Group"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($lang = 'en', $id, UpdateGroupAPIRequest $request)
    {
    	$input = $request->all();
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    		return $profile;

        /** @var Group $group */
        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }

        $group = $this->groupRepository->update($input, $id);
		
        return $this->sendResponse($group->toArray(), 'Group updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/groups/{id}",
     *      summary="Remove the specified Group from storage",
     *      tags={"Group"},
     *      description="Delete Group",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Group",
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
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    	{
    		return $profile;
    	}
        
    	$group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) 
        {
            return $this->sendError('Group not found');
        }

        $group->delete();
        
        $guList = DB::table('group_users')->where('group_id', $id )->get();
        foreach ($guList as $gu)
        {
        	if(!$gu->is_admin)
        	{
        		ProfileToken::sendNotification($gu->profile_id, "The group has been closed", ProfileToken::$ACTION_TYPE_NONE, array());
        	}
        }

        return $this->sendResponse($id, 'Group deleted successfully');
    }
    
    
    public function addSubAdmin($lang = 'en', $group_id, $profile_id)
    {
    	/** @var Group $group */
    	$profile = $this->getAuthUser ();
    	if(!isset($profile->id))
    	{
    		return $profile;
    	}
    	
    	$group = $this->groupRepository->findWithoutFail($group_id);
    	if (empty($group))
    	{
    		return $this->sendError('Group not found');
    	}
    	else
    	{
    		$isAdmin = false;
    		foreach ($group->groupUsers as $gu)
    		{
    			if($gu->profile_id == $profile->id && $gu->is_admin == 1)
    			{
    				$isAdmin = true;
    			}
    		}
    		
    		if($isAdmin)
    		{
	    		DB::table('group_users')->where('group_id', $group_id )->where ( 'profile_id', $profile_id )->update ( array (
	    				'is_admin' => 2
	    		));
	    		
	    		ProfileToken::sendNotification($profile_id, "You have been added as subadmin", ProfileToken::$ACTION_TYPE_OPEN_GROUP, array($group_id));
	    		
	    		return $this->sendResponse ( array (
	    				'Done' => 'True'
	    		), 'Member Added Successfully' );
    		}
    		else 
    		{
    			return $this->sendError('You are not an Admin');
    		}
    	}
    }
}
