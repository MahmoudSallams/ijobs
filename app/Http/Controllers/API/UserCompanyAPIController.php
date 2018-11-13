<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserCompanyAPIRequest;
use App\Http\Requests\API\UpdateUserCompanyAPIRequest;
use App\Models\UserCompany;
use App\Repositories\UserCompanyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class UserCompanyController
 * @package App\Http\Controllers\API
 */

class UserCompanyAPIController extends AppBaseController
{
    /** @var  UserCompanyRepository */
    private $userCompanyRepository;

    public function __construct(UserCompanyRepository $userCompanyRepo)
    {
        $this->userCompanyRepository = $userCompanyRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/userCompanies",
     *      summary="Get a listing of the UserCompanies.",
     *      tags={"UserCompany"},
     *      description="Get all UserCompanies",
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
     *                  @SWG\Items(ref="#/definitions/UserCompany")
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
        $this->userCompanyRepository->pushCriteria(new RequestCriteria($request));
        $this->userCompanyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $userCompanies = $this->userCompanyRepository->all();

        return $this->sendResponse($userCompanies->toArray(), 'User Companies retrieved successfully');
    }

    /**
     * @param CreateUserCompanyAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/userCompanies",
     *      summary="Store a newly created UserCompany in storage",
     *      tags={"UserCompany"},
     *      description="Store UserCompany",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="UserCompany that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/UserCompany")
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
     *                  ref="#/definitions/UserCompany"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserCompanyAPIRequest $request)
    {
        $input = $request->all();

        $userCompanies = $this->userCompanyRepository->create($input);

        return $this->sendResponse($userCompanies->toArray(), 'User Company saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/userCompanies/{id}",
     *      summary="Display the specified UserCompany",
     *      tags={"UserCompany"},
     *      description="Get UserCompany",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserCompany",
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
     *                  ref="#/definitions/UserCompany"
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
        /** @var UserCompany $userCompany */
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            return $this->sendError('User Company not found');
        }

        return $this->sendResponse($userCompany->toArray(), 'User Company retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateUserCompanyAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/userCompanies/{id}",
     *      summary="Update the specified UserCompany in storage",
     *      tags={"UserCompany"},
     *      description="Update UserCompany",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserCompany",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="UserCompany that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/UserCompany")
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
     *                  ref="#/definitions/UserCompany"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateUserCompanyAPIRequest $request)
    {
        $input = $request->all();

        /** @var UserCompany $userCompany */
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            return $this->sendError('User Company not found');
        }

        $userCompany = $this->userCompanyRepository->update($input, $id);

        return $this->sendResponse($userCompany->toArray(), 'UserCompany updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/userCompanies/{id}",
     *      summary="Remove the specified UserCompany from storage",
     *      tags={"UserCompany"},
     *      description="Delete UserCompany",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of UserCompany",
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
        /** @var UserCompany $userCompany */
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            return $this->sendError('User Company not found');
        }

        $userCompany->delete();

        return $this->sendResponse($id, 'User Company deleted successfully');
    }
}
