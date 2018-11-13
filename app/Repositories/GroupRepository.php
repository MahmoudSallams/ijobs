<?php

namespace App\Repositories;

use App\Models\Group;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class GroupRepository
 * @package App\Repositories
 * @version January 24, 2018, 3:54 pm UTC
 *
 * @method Group findWithoutFail($id, $columns = ['*'])
 * @method Group find($id, $columns = ['*'])
 * @method Group first($columns = ['*'])
*/
class GroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'members_counts',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Group::class;
    }
}
