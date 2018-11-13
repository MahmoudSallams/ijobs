<?php

use App\Models\UserCompany;
use App\Repositories\UserCompanyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCompanyRepositoryTest extends TestCase
{
    use MakeUserCompanyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserCompanyRepository
     */
    protected $userCompanyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->userCompanyRepo = App::make(UserCompanyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateUserCompany()
    {
        $userCompany = $this->fakeUserCompanyData();
        $createdUserCompany = $this->userCompanyRepo->create($userCompany);
        $createdUserCompany = $createdUserCompany->toArray();
        $this->assertArrayHasKey('id', $createdUserCompany);
        $this->assertNotNull($createdUserCompany['id'], 'Created UserCompany must have id specified');
        $this->assertNotNull(UserCompany::find($createdUserCompany['id']), 'UserCompany with given id must be in DB');
        $this->assertModelData($userCompany, $createdUserCompany);
    }

    /**
     * @test read
     */
    public function testReadUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $dbUserCompany = $this->userCompanyRepo->find($userCompany->id);
        $dbUserCompany = $dbUserCompany->toArray();
        $this->assertModelData($userCompany->toArray(), $dbUserCompany);
    }

    /**
     * @test update
     */
    public function testUpdateUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $fakeUserCompany = $this->fakeUserCompanyData();
        $updatedUserCompany = $this->userCompanyRepo->update($fakeUserCompany, $userCompany->id);
        $this->assertModelData($fakeUserCompany, $updatedUserCompany->toArray());
        $dbUserCompany = $this->userCompanyRepo->find($userCompany->id);
        $this->assertModelData($fakeUserCompany, $dbUserCompany->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $resp = $this->userCompanyRepo->delete($userCompany->id);
        $this->assertTrue($resp);
        $this->assertNull(UserCompany::find($userCompany->id), 'UserCompany should not exist in DB');
    }
}
