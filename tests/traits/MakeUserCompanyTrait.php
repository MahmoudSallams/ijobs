<?php

use Faker\Factory as Faker;
use App\Models\UserCompany;
use App\Repositories\UserCompanyRepository;

trait MakeUserCompanyTrait
{
    /**
     * Create fake instance of UserCompany and save it in database
     *
     * @param array $userCompanyFields
     * @return UserCompany
     */
    public function makeUserCompany($userCompanyFields = [])
    {
        /** @var UserCompanyRepository $userCompanyRepo */
        $userCompanyRepo = App::make(UserCompanyRepository::class);
        $theme = $this->fakeUserCompanyData($userCompanyFields);
        return $userCompanyRepo->create($theme);
    }

    /**
     * Get fake instance of UserCompany
     *
     * @param array $userCompanyFields
     * @return UserCompany
     */
    public function fakeUserCompany($userCompanyFields = [])
    {
        return new UserCompany($this->fakeUserCompanyData($userCompanyFields));
    }

    /**
     * Get fake data of UserCompany
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUserCompanyData($userCompanyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'company_id' => $fake->randomDigitNotNull,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word,
            'deleted_at' => $fake->word
        ], $userCompanyFields);
    }
}
