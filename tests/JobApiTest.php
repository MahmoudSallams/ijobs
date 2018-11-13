<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobApiTest extends TestCase
{
    use MakeJobTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateJob()
    {
        $job = $this->fakeJobData();
        $this->json('POST', '/api/v1/jobs', $job);

        $this->assertApiResponse($job);
    }

    /**
     * @test
     */
    public function testReadJob()
    {
        $job = $this->makeJob();
        $this->json('GET', '/api/v1/jobs/'.$job->id);

        $this->assertApiResponse($job->toArray());
    }

    /**
     * @test
     */
    public function testUpdateJob()
    {
        $job = $this->makeJob();
        $editedJob = $this->fakeJobData();

        $this->json('PUT', '/api/v1/jobs/'.$job->id, $editedJob);

        $this->assertApiResponse($editedJob);
    }

    /**
     * @test
     */
    public function testDeleteJob()
    {
        $job = $this->makeJob();
        $this->json('DELETE', '/api/v1/jobs/'.$job->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/jobs/'.$job->id);

        $this->assertResponseStatus(404);
    }
}
