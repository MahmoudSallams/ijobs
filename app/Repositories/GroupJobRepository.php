<?php

namespace App\Repositories;

use App\Models\GroupJob;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GroupJobRepository
 * @package App\Repositories
 * @version January 26, 2018, 3:52 pm UTC
 *
 * @method GroupJob findWithoutFail($id, $columns = ['*'])
 * @method GroupJob find($id, $columns = ['*'])
 * @method GroupJob first($columns = ['*'])
*/
class GroupJobRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_id',
        'job_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GroupJob::class;
    }
}
