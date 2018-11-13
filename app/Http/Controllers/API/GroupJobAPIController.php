<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGroupJobAPIRequest;
use App\Http\Requests\API\UpdateGroupJobAPIRequest;
use App\Models\GroupJob;
use App\Repositories\GroupJobRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class GroupJobController
 * @package App\Http\Controllers\API
 */

class GroupJobAPIController extends AppBaseController
{
    /** @var  GroupJobRepository */
    private $groupJobRepository;

    public function __construct(GroupJobRepository $groupJobRepo)
    {
        $this->groupJobRepository = $groupJobRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/groupJobs",
     *      summary="Get a listing of the GroupJobs.",
     *      tags={"GroupJob"},
     *      description="Get all GroupJobs",
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
     *                  @SWG\Items(ref="#/definitions/GroupJob")
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
        $this->groupJobRepository->pushCriteria(new RequestCriteria($request));
        $this->groupJobRepository->pushCriteria(new LimitOffsetCriteria($request));
        $groupJobs = $this->groupJobRepository->all();

        return $this->sendResponse($groupJobs->toArray(), 'Group Jobs retrieved successfully');
    }

    /**
     * @param CreateGroupJobAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/groupJobs",
     *      summary="Store a newly created GroupJob in storage",
     *      tags={"GroupJob"},
     *      description="Store GroupJob",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="GroupJob that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/GroupJob")
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
     *                  ref="#/definitions/GroupJob"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateGroupJobAPIRequest $request)
    {
        $input = $request->all();

        $groupJobs = $this->groupJobRepository->create($input);

        return $this->sendResponse($groupJobs->toArray(), 'Group Job saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/groupJobs/{id}",
     *      summary="Display the specified GroupJob",
     *      tags={"GroupJob"},
     *      description="Get GroupJob",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupJob",
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
     *                  ref="#/definitions/GroupJob"
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
        /** @var GroupJob $groupJob */
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            return $this->sendError('Group Job not found');
        }

        return $this->sendResponse($groupJob->toArray(), 'Group Job retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateGroupJobAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/groupJobs/{id}",
     *      summary="Update the specified GroupJob in storage",
     *      tags={"GroupJob"},
     *      description="Update GroupJob",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupJob",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="GroupJob that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/GroupJob")
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
     *                  ref="#/definitions/GroupJob"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateGroupJobAPIRequest $request)
    {
        $input = $request->all();

        /** @var GroupJob $groupJob */
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            return $this->sendError('Group Job not found');
        }

        $groupJob = $this->groupJobRepository->update($input, $id);

        return $this->sendResponse($groupJob->toArray(), 'GroupJob updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/groupJobs/{id}",
     *      summary="Remove the specified GroupJob from storage",
     *      tags={"GroupJob"},
     *      description="Delete GroupJob",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of GroupJob",
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
        /** @var GroupJob $groupJob */
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            return $this->sendError('Group Job not found');
        }

        $groupJob->delete();

        return $this->sendResponse($id, 'Group Job deleted successfully');
    }
}
