<?php

use Faker\Factory as Faker;
use App\Models\JobUser;
use App\Repositories\JobUserRepository;

trait MakeJobUserTrait
{
    /**
     * Create fake instance of JobUser and save it in database
     *
     * @param array $jobUserFields
     * @return JobUser
     */
    public function makeJobUser($jobUserFields = [])
    {
        /** @var JobUserRepository $jobUserRepo */
        $jobUserRepo = App::make(JobUserRepository::class);
        $theme = $this->fakeJobUserData($jobUserFields);
        return $jobUserRepo->create($theme);
    }

    /**
     * Get fake instance of JobUser
     *
     * @param array $jobUserFields
     * @return JobUser
     */
    public function fakeJobUser($jobUserFields = [])
    {
        return new JobUser($this->fakeJobUserData($jobUserFields));
    }

    /**
     * Get fake data of JobUser
     *
     * @param array $postFields
     * @return array
     */
    public function fakeJobUserData($jobUserFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'job_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word,
            'deleted_at' => $fake->word
        ], $jobUserFields);
    }
}
