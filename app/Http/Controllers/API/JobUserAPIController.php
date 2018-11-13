<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateJobUserAPIRequest;
use App\Http\Requests\API\UpdateJobUserAPIRequest;
use App\Models\JobUser;
use App\Repositories\JobUserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class JobUserController
 * @package App\Http\Controllers\API
 */

class JobUserAPIController extends AppBaseController
{
    /** @var  JobUserRepository */
    private $jobUserRepository;

    public function __construct(JobUserRepository $jobUserRepo)
    {
        $this->jobUserRepository = $jobUserRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobUsers",
     *      summary="Get a listing of the JobUsers.",
     *      tags={"JobUser"},
     *      description="Get all JobUsers",
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
     *                  @SWG\Items(ref="#/definitions/JobUser")
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
        $this->jobUserRepository->pushCriteria(new RequestCriteria($request));
        $this->jobUserRepository->pushCriteria(new LimitOffsetCriteria($request));
        $jobUsers = $this->jobUserRepository->all();

        return $this->sendResponse($jobUsers->toArray(), 'Job Users retrieved successfully');
    }

    /**
     * @param CreateJobUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/jobUsers",
     *      summary="Store a newly created JobUser in storage",
     *      tags={"JobUser"},
     *      description="Store JobUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JobUser that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/JobUser")
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
     *                  ref="#/definitions/JobUser"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateJobUserAPIRequest $request)
    {
        $input = $request->all();

        $jobUsers = $this->jobUserRepository->create($input);

        return $this->sendResponse($jobUsers->toArray(), 'Job User saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/jobUsers/{id}",
     *      summary="Display the specified JobUser",
     *      tags={"JobUser"},
     *      description="Get JobUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of JobUser",
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
     *                  ref="#/definitions/JobUser"
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
        /** @var JobUser $jobUser */
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            return $this->sendError('Job User not found');
        }

        return $this->sendResponse($jobUser->toArray(), 'Job User retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateJobUserAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/jobUsers/{id}",
     *      summary="Update the specified JobUser in storage",
     *      tags={"JobUser"},
     *      description="Update JobUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of JobUser",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JobUser that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/JobUser")
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
     *                  ref="#/definitions/JobUser"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateJobUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var JobUser $jobUser */
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            return $this->sendError('Job User not found');
        }

        $jobUser = $this->jobUserRepository->update($input, $id);

        return $this->sendResponse($jobUser->toArray(), 'JobUser updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/jobUsers/{id}",
     *      summary="Remove the specified JobUser from storage",
     *      tags={"JobUser"},
     *      description="Delete JobUser",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of JobUser",
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
        /** @var JobUser $jobUser */
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            return $this->sendError('Job User not found');
        }

        $jobUser->delete();

        return $this->sendResponse($id, 'Job User deleted successfully');
    }
}
