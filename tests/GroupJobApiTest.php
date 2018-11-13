<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupJobApiTest extends TestCase
{
    use MakeGroupJobTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateGroupJob()
    {
        $groupJob = $this->fakeGroupJobData();
        $this->json('POST', '/api/v1/groupJobs', $groupJob);

        $this->assertApiResponse($groupJob);
    }

    /**
     * @test
     */
    public function testReadGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $this->json('GET', '/api/v1/groupJobs/'.$groupJob->id);

        $this->assertApiResponse($groupJob->toArray());
    }

    /**
     * @test
     */
    public function testUpdateGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $editedGroupJob = $this->fakeGroupJobData();

        $this->json('PUT', '/api/v1/groupJobs/'.$groupJob->id, $editedGroupJob);

        $this->assertApiResponse($editedGroupJob);
    }

    /**
     * @test
     */
    public function testDeleteGroupJob()
    {
        $groupJob = $this->makeGroupJob();
        $this->json('DELETE', '/api/v1/groupJobs/'.$groupJob->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/groupJobs/'.$groupJob->id);

        $this->assertResponseStatus(404);
    }
}
