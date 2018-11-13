<?php

namespace App\Repositories;

use App\Models\UserCompany;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserCompanyRepository
 * @package App\Repositories
 * @version January 26, 2018, 3:40 pm UTC
 *
 * @method UserCompany findWithoutFail($id, $columns = ['*'])
 * @method UserCompany find($id, $columns = ['*'])
 * @method UserCompany first($columns = ['*'])
*/
class UserCompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'company_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserCompany::class;
    }
}
