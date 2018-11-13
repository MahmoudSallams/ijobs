<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobUserApiTest extends TestCase
{
    use MakeJobUserTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateJobUser()
    {
        $jobUser = $this->fakeJobUserData();
        $this->json('POST', '/api/v1/jobUsers', $jobUser);

        $this->assertApiResponse($jobUser);
    }

    /**
     * @test
     */
    public function testReadJobUser()
    {
        $jobUser = $this->makeJobUser();
        $this->json('GET', '/api/v1/jobUsers/'.$jobUser->id);

        $this->assertApiResponse($jobUser->toArray());
    }

    /**
     * @test
     */
    public function testUpdateJobUser()
    {
        $jobUser = $this->makeJobUser();
        $editedJobUser = $this->fakeJobUserData();

        $this->json('PUT', '/api/v1/jobUsers/'.$jobUser->id, $editedJobUser);

        $this->assertApiResponse($editedJobUser);
    }

    /**
     * @test
     */
    public function testDeleteJobUser()
    {
        $jobUser = $this->makeJobUser();
        $this->json('DELETE', '/api/v1/jobUsers/'.$jobUser->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/jobUsers/'.$jobUser->id);

        $this->assertResponseStatus(404);
    }
}
