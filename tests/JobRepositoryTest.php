<?php

use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobRepositoryTest extends TestCase
{
    use MakeJobTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var JobRepository
     */
    protected $jobRepo;

    public function setUp()
    {
        parent::setUp();
        $this->jobRepo = App::make(JobRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateJob()
    {
        $job = $this->fakeJobData();
        $createdJob = $this->jobRepo->create($job);
        $createdJob = $createdJob->toArray();
        $this->assertArrayHasKey('id', $createdJob);
        $this->assertNotNull($createdJob['id'], 'Created Job must have id specified');
        $this->assertNotNull(Job::find($createdJob['id']), 'Job with given id must be in DB');
        $this->assertModelData($job, $createdJob);
    }

    /**
     * @test read
     */
    public function testReadJob()
    {
        $job = $this->makeJob();
        $dbJob = $this->jobRepo->find($job->id);
        $dbJob = $dbJob->toArray();
        $this->assertModelData($job->toArray(), $dbJob);
    }

    /**
     * @test update
     */
    public function testUpdateJob()
    {
        $job = $this->makeJob();
        $fakeJob = $this->fakeJobData();
        $updatedJob = $this->jobRepo->update($fakeJob, $job->id);
        $this->assertModelData($fakeJob, $updatedJob->toArray());
        $dbJob = $this->jobRepo->find($job->id);
        $this->assertModelData($fakeJob, $dbJob->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteJob()
    {
        $job = $this->makeJob();
        $resp = $this->jobRepo->delete($job->id);
        $this->assertTrue($resp);
        $this->assertNull(Job::find($job->id), 'Job should not exist in DB');
    }
}
