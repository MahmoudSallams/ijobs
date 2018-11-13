<?php

use Faker\Factory as Faker;
use App\Models\GroupJob;
use App\Repositories\GroupJobRepository;

trait MakeGroupJobTrait
{
    /**
     * Create fake instance of GroupJob and save it in database
     *
     * @param array $groupJobFields
     * @return GroupJob
     */
    public function makeGroupJob($groupJobFields = [])
    {
        /** @var GroupJobRepository $groupJobRepo */
        $groupJobRepo = App::make(GroupJobRepository::class);
        $theme = $this->fakeGroupJobData($groupJobFields);
        return $groupJobRepo->create($theme);
    }

    /**
     * Get fake instance of GroupJob
     *
     * @param array $groupJobFields
     * @return GroupJob
     */
    public function fakeGroupJob($groupJobFields = [])
    {
        return new GroupJob($this->fakeGroupJobData($groupJobFields));
    }

    /**
     * Get fake data of GroupJob
     *
     * @param array $postFields
     * @return array
     */
    public function fakeGroupJobData($groupJobFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'group_id' => $fake->randomDigitNotNull,
            'job_id' => $fake->randomDigitNotNull,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word,
            'deleted_at' => $fake->word
        ], $groupJobFields);
    }
}
