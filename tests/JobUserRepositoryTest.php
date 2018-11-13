<?php

use App\Models\JobUser;
use App\Repositories\JobUserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobUserRepositoryTest extends TestCase
{
    use MakeJobUserTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var JobUserRepository
     */
    protected $jobUserRepo;

    public function setUp()
    {
        parent::setUp();
        $this->jobUserRepo = App::make(JobUserRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateJobUser()
    {
        $jobUser = $this->fakeJobUserData();
        $createdJobUser = $this->jobUserRepo->create($jobUser);
        $createdJobUser = $createdJobUser->toArray();
        $this->assertArrayHasKey('id', $createdJobUser);
        $this->assertNotNull($createdJobUser['id'], 'Created JobUser must have id specified');
        $this->assertNotNull(JobUser::find($createdJobUser['id']), 'JobUser with given id must be in DB');
        $this->assertModelData($jobUser, $createdJobUser);
    }

    /**
     * @test read
     */
    public function testReadJobUser()
    {
        $jobUser = $this->makeJobUser();
        $dbJobUser = $this->jobUserRepo->find($jobUser->id);
        $dbJobUser = $dbJobUser->toArray();
        $this->assertModelData($jobUser->toArray(), $dbJobUser);
    }

    /**
     * @test update
     */
    public function testUpdateJobUser()
    {
        $jobUser = $this->makeJobUser();
        $fakeJobUser = $this->fakeJobUserData();
        $updatedJobUser = $this->jobUserRepo->update($fakeJobUser, $jobUser->id);
        $this->assertModelData($fakeJobUser, $updatedJobUser->toArray());
        $dbJobUser = $this->jobUserRepo->find($jobUser->id);
        $this->assertModelData($fakeJobUser, $dbJobUser->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteJobUser()
    {
        $jobUser = $this->makeJobUser();
        $resp = $this->jobUserRepo->delete($jobUser->id);
        $this->assertTrue($resp);
        $this->assertNull(JobUser::find($jobUser->id), 'JobUser should not exist in DB');
    }
}
