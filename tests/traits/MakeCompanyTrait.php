<?php

use Faker\Factory as Faker;
use App\Models\Company;
use App\Repositories\CompanyRepository;

trait MakeCompanyTrait
{
    /**
     * Create fake instance of Company and save it in database
     *
     * @param array $companyFields
     * @return Company
     */
    public function makeCompany($companyFields = [])
    {
        /** @var CompanyRepository $companyRepo */
        $companyRepo = App::make(CompanyRepository::class);
        $theme = $this->fakeCompanyData($companyFields);
        return $companyRepo->create($theme);
    }

    /**
     * Get fake instance of Company
     *
     * @param array $companyFields
     * @return Company
     */
    public function fakeCompany($companyFields = [])
    {
        return new Company($this->fakeCompanyData($companyFields));
    }

    /**
     * Get fake data of Company
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCompanyData($companyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'region' => $fake->word,
            'country' => $fake->word,
            'city' => $fake->word,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $companyFields);
    }
}
