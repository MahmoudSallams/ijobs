<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserCompanyRequest;
use App\Http\Requests\UpdateUserCompanyRequest;
use App\Repositories\UserCompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class UserCompanyController extends AppBaseController
{
    /** @var  UserCompanyRepository */
    private $userCompanyRepository;

    public function __construct(UserCompanyRepository $userCompanyRepo)
    {
        $this->userCompanyRepository = $userCompanyRepo;
    }

    /**
     * Display a listing of the UserCompany.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userCompanyRepository->pushCriteria(new RequestCriteria($request));
        $userCompanies = $this->userCompanyRepository->all();

        return view('user_companies.index')
            ->with('userCompanies', $userCompanies);
    }

    /**
     * Show the form for creating a new UserCompany.
     *
     * @return Response
     */
    public function create()
    {
        return view('user_companies.create');
    }

    /**
     * Store a newly created UserCompany in storage.
     *
     * @param CreateUserCompanyRequest $request
     *
     * @return Response
     */
    public function store(CreateUserCompanyRequest $request)
    {
        $input = $request->all();

        $userCompany = $this->userCompanyRepository->create($input);

        Flash::success('User Company saved successfully.');

        return redirect(route('userCompanies.index'));
    }

    /**
     * Display the specified UserCompany.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            Flash::error('User Company not found');

            return redirect(route('userCompanies.index'));
        }

        return view('user_companies.show')->with('userCompany', $userCompany);
    }

    /**
     * Show the form for editing the specified UserCompany.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            Flash::error('User Company not found');

            return redirect(route('userCompanies.index'));
        }

        return view('user_companies.edit')->with('userCompany', $userCompany);
    }

    /**
     * Update the specified UserCompany in storage.
     *
     * @param  int              $id
     * @param UpdateUserCompanyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserCompanyRequest $request)
    {
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            Flash::error('User Company not found');

            return redirect(route('userCompanies.index'));
        }

        $userCompany = $this->userCompanyRepository->update($request->all(), $id);

        Flash::success('User Company updated successfully.');

        return redirect(route('userCompanies.index'));
    }

    /**
     * Remove the specified UserCompany from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $userCompany = $this->userCompanyRepository->findWithoutFail($id);

        if (empty($userCompany)) {
            Flash::error('User Company not found');

            return redirect(route('userCompanies.index'));
        }

        $this->userCompanyRepository->delete($id);

        Flash::success('User Company deleted successfully.');

        return redirect(route('userCompanies.index'));
    }
}
