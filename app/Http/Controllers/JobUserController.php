<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobUserRequest;
use App\Http\Requests\UpdateJobUserRequest;
use App\Repositories\JobUserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class JobUserController extends AppBaseController
{
    /** @var  JobUserRepository */
    private $jobUserRepository;

    public function __construct(JobUserRepository $jobUserRepo)
    {
        $this->jobUserRepository = $jobUserRepo;
    }

    /**
     * Display a listing of the JobUser.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->jobUserRepository->pushCriteria(new RequestCriteria($request));
        $jobUsers = $this->jobUserRepository->all();

        return view('job_users.index')
            ->with('jobUsers', $jobUsers);
    }

    /**
     * Show the form for creating a new JobUser.
     *
     * @return Response
     */
    public function create()
    {
        return view('job_users.create');
    }

    /**
     * Store a newly created JobUser in storage.
     *
     * @param CreateJobUserRequest $request
     *
     * @return Response
     */
    public function store(CreateJobUserRequest $request)
    {
        $input = $request->all();

        $jobUser = $this->jobUserRepository->create($input);

        Flash::success('Job User saved successfully.');

        return redirect(route('jobUsers.index'));
    }

    /**
     * Display the specified JobUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            Flash::error('Job User not found');

            return redirect(route('jobUsers.index'));
        }

        return view('job_users.show')->with('jobUser', $jobUser);
    }

    /**
     * Show the form for editing the specified JobUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            Flash::error('Job User not found');

            return redirect(route('jobUsers.index'));
        }

        return view('job_users.edit')->with('jobUser', $jobUser);
    }

    /**
     * Update the specified JobUser in storage.
     *
     * @param  int              $id
     * @param UpdateJobUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateJobUserRequest $request)
    {
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            Flash::error('Job User not found');

            return redirect(route('jobUsers.index'));
        }

        $jobUser = $this->jobUserRepository->update($request->all(), $id);

        Flash::success('Job User updated successfully.');

        return redirect(route('jobUsers.index'));
    }

    /**
     * Remove the specified JobUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $jobUser = $this->jobUserRepository->findWithoutFail($id);

        if (empty($jobUser)) {
            Flash::error('Job User not found');

            return redirect(route('jobUsers.index'));
        }

        $this->jobUserRepository->delete($id);

        Flash::success('Job User deleted successfully.');

        return redirect(route('jobUsers.index'));
    }
}
