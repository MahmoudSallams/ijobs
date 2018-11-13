<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupUserApiTest extends TestCase
{
    use MakeGroupUserTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateGroupUser()
    {
        $groupUser = $this->fakeGroupUserData();
        $this->json('POST', '/api/v1/groupUsers', $groupUser);

        $this->assertApiResponse($groupUser);
    }

    /**
     * @test
     */
    public function testReadGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $this->json('GET', '/api/v1/groupUsers/'.$groupUser->id);

        $this->assertApiResponse($groupUser->toArray());
    }

    /**
     * @test
     */
    public function testUpdateGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $editedGroupUser = $this->fakeGroupUserData();

        $this->json('PUT', '/api/v1/groupUsers/'.$groupUser->id, $editedGroupUser);

        $this->assertApiResponse($editedGroupUser);
    }

    /**
     * @test
     */
    public function testDeleteGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $this->json('DELETE', '/api/v1/groupUsers/'.$groupUser->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/groupUsers/'.$groupUser->id);

        $this->assertResponseStatus(404);
    }
}
