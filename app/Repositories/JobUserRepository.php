<?php

namespace App\Repositories;

use App\Models\JobUser;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class JobUserRepository
 * @package App\Repositories
 * @version January 26, 2018, 4:03 pm UTC
 *
 * @method JobUser findWithoutFail($id, $columns = ['*'])
 * @method JobUser find($id, $columns = ['*'])
 * @method JobUser first($columns = ['*'])
*/
class JobUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'job_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return JobUser::class;
    }
}
