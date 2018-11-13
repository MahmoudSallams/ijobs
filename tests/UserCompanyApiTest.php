<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserCompanyApiTest extends TestCase
{
    use MakeUserCompanyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateUserCompany()
    {
        $userCompany = $this->fakeUserCompanyData();
        $this->json('POST', '/api/v1/userCompanies', $userCompany);

        $this->assertApiResponse($userCompany);
    }

    /**
     * @test
     */
    public function testReadUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $this->json('GET', '/api/v1/userCompanies/'.$userCompany->id);

        $this->assertApiResponse($userCompany->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $editedUserCompany = $this->fakeUserCompanyData();

        $this->json('PUT', '/api/v1/userCompanies/'.$userCompany->id, $editedUserCompany);

        $this->assertApiResponse($editedUserCompany);
    }

    /**
     * @test
     */
    public function testDeleteUserCompany()
    {
        $userCompany = $this->makeUserCompany();
        $this->json('DELETE', '/api/v1/userCompanies/'.$userCompany->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/userCompanies/'.$userCompany->id);

        $this->assertResponseStatus(404);
    }
}
