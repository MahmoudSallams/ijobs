<?php

namespace App\Repositories;

use App\Models\Job;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class JobRepository
 * @package App\Repositories
 * @version January 24, 2018, 3:51 pm UTC
 *
 * @method Job findWithoutFail($id, $columns = ['*'])
 * @method Job find($id, $columns = ['*'])
 * @method Job first($columns = ['*'])
*/
class JobRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'image',
        'applied_count',
        'forwarded_count',
        'shared_count',
        'user_id',
        'group_id',
        'parent_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Job::class;
    }
    
    public function getActiveJobs()
    {
    	return $this->createQueryBuilder('job')
    	->andWhere('cat.status != :searchTerm')
    	->setParameter('searchTerm', -1)
    	->getQuery()
    	->execute();
    }
}
