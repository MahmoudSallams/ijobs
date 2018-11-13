<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupJobRequest;
use App\Http\Requests\UpdateGroupJobRequest;
use App\Repositories\GroupJobRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GroupJobController extends AppBaseController
{
    /** @var  GroupJobRepository */
    private $groupJobRepository;

    public function __construct(GroupJobRepository $groupJobRepo)
    {
        $this->groupJobRepository = $groupJobRepo;
    }

    /**
     * Display a listing of the GroupJob.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->groupJobRepository->pushCriteria(new RequestCriteria($request));
        $groupJobs = $this->groupJobRepository->all();

        return view('group_jobs.index')
            ->with('groupJobs', $groupJobs);
    }

    /**
     * Show the form for creating a new GroupJob.
     *
     * @return Response
     */
    public function create()
    {
        return view('group_jobs.create');
    }

    /**
     * Store a newly created GroupJob in storage.
     *
     * @param CreateGroupJobRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupJobRequest $request)
    {
        $input = $request->all();

        $groupJob = $this->groupJobRepository->create($input);

        Flash::success('Group Job saved successfully.');

        return redirect(route('groupJobs.index'));
    }

    /**
     * Display the specified GroupJob.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            Flash::error('Group Job not found');

            return redirect(route('groupJobs.index'));
        }

        return view('group_jobs.show')->with('groupJob', $groupJob);
    }

    /**
     * Show the form for editing the specified GroupJob.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            Flash::error('Group Job not found');

            return redirect(route('groupJobs.index'));
        }

        return view('group_jobs.edit')->with('groupJob', $groupJob);
    }

    /**
     * Update the specified GroupJob in storage.
     *
     * @param  int              $id
     * @param UpdateGroupJobRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupJobRequest $request)
    {
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            Flash::error('Group Job not found');

            return redirect(route('groupJobs.index'));
        }

        $groupJob = $this->groupJobRepository->update($request->all(), $id);

        Flash::success('Group Job updated successfully.');

        return redirect(route('groupJobs.index'));
    }

    /**
     * Remove the specified GroupJob from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $groupJob = $this->groupJobRepository->findWithoutFail($id);

        if (empty($groupJob)) {
            Flash::error('Group Job not found');

            return redirect(route('groupJobs.index'));
        }

        $this->groupJobRepository->delete($id);

        Flash::success('Group Job deleted successfully.');

        return redirect(route('groupJobs.index'));
    }
}
