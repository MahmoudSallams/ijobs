<?php

use App\Models\GroupUser;
use App\Repositories\GroupUserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupUserRepositoryTest extends TestCase
{
    use MakeGroupUserTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var GroupUserRepository
     */
    protected $groupUserRepo;

    public function setUp()
    {
        parent::setUp();
        $this->groupUserRepo = App::make(GroupUserRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateGroupUser()
    {
        $groupUser = $this->fakeGroupUserData();
        $createdGroupUser = $this->groupUserRepo->create($groupUser);
        $createdGroupUser = $createdGroupUser->toArray();
        $this->assertArrayHasKey('id', $createdGroupUser);
        $this->assertNotNull($createdGroupUser['id'], 'Created GroupUser must have id specified');
        $this->assertNotNull(GroupUser::find($createdGroupUser['id']), 'GroupUser with given id must be in DB');
        $this->assertModelData($groupUser, $createdGroupUser);
    }

    /**
     * @test read
     */
    public function testReadGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $dbGroupUser = $this->groupUserRepo->find($groupUser->id);
        $dbGroupUser = $dbGroupUser->toArray();
        $this->assertModelData($groupUser->toArray(), $dbGroupUser);
    }

    /**
     * @test update
     */
    public function testUpdateGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $fakeGroupUser = $this->fakeGroupUserData();
        $updatedGroupUser = $this->groupUserRepo->update($fakeGroupUser, $groupUser->id);
        $this->assertModelData($fakeGroupUser, $updatedGroupUser->toArray());
        $dbGroupUser = $this->groupUserRepo->find($groupUser->id);
        $this->assertModelData($fakeGroupUser, $dbGroupUser->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteGroupUser()
    {
        $groupUser = $this->makeGroupUser();
        $resp = $this->groupUserRepo->delete($groupUser->id);
        $this->assertTrue($resp);
        $this->assertNull(GroupUser::find($groupUser->id), 'GroupUser should not exist in DB');
    }
}
