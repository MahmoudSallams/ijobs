<?php

namespace App\Repositories;

use App\Models\Profile;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProfileRepository
 * @package App\Repositories
 * @version January 24, 2018, 3:39 pm UTC
 *
 * @method Profile findWithoutFail($id, $columns = ['*'])
 * @method Profile find($id, $columns = ['*'])
 * @method Profile first($columns = ['*'])
*/
class ProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'email',
        'age',
        'mobile',
        'other_mobile',
        'mobile_verify_code',
        'mobile_verify_status',
        'region_id',
        'country_id',
        'city_id',
        'gender',
        'brief',
        'photo',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Profile::class;
    }
}
