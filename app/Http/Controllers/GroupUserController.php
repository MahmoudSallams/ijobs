<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupUserRequest;
use App\Http\Requests\UpdateGroupUserRequest;
use App\Repositories\GroupUserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GroupUserController extends AppBaseController
{
    /** @var  GroupUserRepository */
    private $groupUserRepository;

    public function __construct(GroupUserRepository $groupUserRepo)
    {
        $this->groupUserRepository = $groupUserRepo;
    }

    /**
     * Display a listing of the GroupUser.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->groupUserRepository->pushCriteria(new RequestCriteria($request));
        $groupUsers = $this->groupUserRepository->all();

        return view('group_users.index')
            ->with('groupUsers', $groupUsers);
    }

    /**
     * Show the form for creating a new GroupUser.
     *
     * @return Response
     */
    public function create()
    {
        return view('group_users.create');
    }

    /**
     * Store a newly created GroupUser in storage.
     *
     * @param CreateGroupUserRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupUserRequest $request)
    {
        $input = $request->all();

        $groupUser = $this->groupUserRepository->create($input);

        Flash::success('Group User saved successfully.');

        return redirect(route('groupUsers.index'));
    }

    /**
     * Display the specified GroupUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            Flash::error('Group User not found');

            return redirect(route('groupUsers.index'));
        }

        return view('group_users.show')->with('groupUser', $groupUser);
    }

    /**
     * Show the form for editing the specified GroupUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            Flash::error('Group User not found');

            return redirect(route('groupUsers.index'));
        }

        return view('group_users.edit')->with('groupUser', $groupUser);
    }

    /**
     * Update the specified GroupUser in storage.
     *
     * @param  int              $id
     * @param UpdateGroupUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupUserRequest $request)
    {
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            Flash::error('Group User not found');

            return redirect(route('groupUsers.index'));
        }

        $groupUser = $this->groupUserRepository->update($request->all(), $id);

        Flash::success('Group User updated successfully.');

        return redirect(route('groupUsers.index'));
    }

    /**
     * Remove the specified GroupUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $groupUser = $this->groupUserRepository->findWithoutFail($id);

        if (empty($groupUser)) {
            Flash::error('Group User not found');

            return redirect(route('groupUsers.index'));
        }

        $this->groupUserRepository->delete($id);

        Flash::success('Group User deleted successfully.');

        return redirect(route('groupUsers.index'));
    }
}
