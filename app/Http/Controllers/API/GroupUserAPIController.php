<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGroupUserAPIRequest;
use App\Http\Requests\API\UpdateGroupUserAPIRequest;
use App\Models\GroupUser;
use App\Repositories\GroupUserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class GroupUserController
 * @package App\Http\Controllers\API
 */

class GroupUserAPIController extends AppBaseController
{
    /** @var  GroupUserRepository */
    private $groupUserRepository;

    public function __construct(GroupUserRepository $groupUserRepo)
    {
        $this->groupUserRepository = $groupUserRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/groupUsers",
     *      summary="Get a listing of the GroupUsers.",
     *      tags={"GroupUser"},
     *      description="Get all GroupUsers",
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
     *                  @SWG\Items(ref="#/definitions/GroupUser")
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
        $this->groupUserRepository->pushCriteria(new RequestCriteria($request));
        $this->groupUserRepository->pushCriteria(new LimitOffsetCriteria($request));
        $groupUsers = $this->groupUserRepository->all();

        return $this->sendResponse($groupUsers->toArray(), 'Group Users retrieved successfully');
    }

    /**
     * @param CreateGroupUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/groupUsers",
     *      summary="Store a newly created GroupUser in storage",
     *      tags={"GroupUser"},
     *      description="Store GroupUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="GroupUser that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/GroupUser")
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
     *                  ref="#/definitions/GroupUser"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateGroupUserAPIRequest $request)
    {
        $input = $request->all();

        $groupUsers = $this->groupUserRepository->create($input);

        return $this->sendResponse($groupUsers->toArray(), 'Group User saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/groupUsers/{id}",
     *      summary="Display the specified GroupUser",
     *      tags={"GroupUser"},
     *      description="Get GroupUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupUser",
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
     *                  ref="#/definitions/GroupUser"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var GroupUser $groupUser */
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            return $this->sendError('Group User not found');
        }

        return $this->sendResponse($groupUser->toArray(), 'Group User retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateGroupUserAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/groupUsers/{id}",
     *      summary="Update the specified GroupUser in storage",
     *      tags={"GroupUser"},
     *      description="Update GroupUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupUser",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="GroupUser that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/GroupUser")
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
     *                  ref="#/definitions/GroupUser"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateGroupUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var GroupUser $groupUser */
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            return $this->sendError('Group User not found');
        }

        $groupUser = $this->groupUserRepository->update($input, $id);

        return $this->sendResponse($groupUser->toArray(), 'GroupUser updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/groupUsers/{id}",
     *      summary="Remove the specified GroupUser from storage",
     *      tags={"GroupUser"},
     *      description="Delete GroupUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupUser",
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
    public function destroy($id)
    {
        /** @var GroupUser $groupUser */
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            return $this->sendError('Group User not found');
        }

        $groupUser->delete();

        return $this->sendResponse($id, 'Group User deleted successfully');
    }
}
