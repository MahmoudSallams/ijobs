<?php

namespace App\Repositories;

use App\Models\GroupUser;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GroupUserRepository
 * @package App\Repositories
 * @version January 26, 2018, 3:48 pm UTC
 *
 * @method GroupUser findWithoutFail($id, $columns = ['*'])
 * @method GroupUser find($id, $columns = ['*'])
 * @method GroupUser first($columns = ['*'])
*/
class GroupUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GroupUser::class;
    }
}
