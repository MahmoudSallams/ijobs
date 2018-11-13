<?php

use App\Models\GroupJob;
use App\Repositories\GroupJobRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupJobRepositoryTest extends TestCase
{
    use MakeGroupJobTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var GroupJobRepository
     */
    protected $groupJobRepo;

    public function setUp()
    {
        parent::setUp();
        $this->groupJobRepo = App::make(GroupJobRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateGroupJob()
    {
        $groupJob = $this->fakeGroupJobData();
        $createdGroupJob = $this->groupJobRepo->create($groupJob);
        $createdGroupJob = $createdGroupJob->toArray();
        $this->assertArrayHasKey('id', $createdGroupJob);
        $this->assertNotNull($createdGroupJob['id'], 'Created GroupJob must have id specified');
        $this->assertNotNull(GroupJob::find($createdGroupJob['id']), 'GroupJob with given id must be in DB');
        $this->assertModelData($groupJob, $createdGroupJob);
    }

    /**
     * @test read
     */
    public function testReadGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $dbGroupJob = $this->groupJobRepo->find($groupJob->id);
        $dbGroupJob = $dbGroupJob->toArray();
        $this->assertModelData($groupJob->toArray(), $dbGroupJob);
    }

    /**
     * @test update
     */
    public function testUpdateGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $fakeGroupJob = $this->fakeGroupJobData();
        $updatedGroupJob = $this->groupJobRepo->update($fakeGroupJob, $groupJob->id);
        $this->assertModelData($fakeGroupJob, $updatedGroupJob->toArray());
        $dbGroupJob = $this->groupJobRepo->find($groupJob->id);
        $this->assertModelData($fakeGroupJob, $dbGroupJob->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $resp = $this->groupJobRepo->delete($groupJob->id);
        $this->assertTrue($resp);
        $this->assertNull(GroupJob::find($groupJob->id), 'GroupJob should not exist in DB');
    }
}
