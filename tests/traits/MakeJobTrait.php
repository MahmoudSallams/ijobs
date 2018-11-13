<?php

use Faker\Factory as Faker;
use App\Models\Job;
use App\Repositories\JobRepository;

trait MakeJobTrait
{
    /**
     * Create fake instance of Job and save it in database
     *
     * @param array $jobFields
     * @return Job
     */
    public function makeJob($jobFields = [])
    {
        /** @var JobRepository $jobRepo */
        $jobRepo = App::make(JobRepository::class);
        $theme = $this->fakeJobData($jobFields);
        return $jobRepo->create($theme);
    }

    /**
     * Get fake instance of Job
     *
     * @param array $jobFields
     * @return Job
     */
    public function fakeJob($jobFields = [])
    {
        return new Job($this->fakeJobData($jobFields));
    }

    /**
     * Get fake data of Job
     *
     * @param array $postFields
     * @return array
     */
    public function fakeJobData($jobFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'description' => $fake->word,
            'image' => $fake->word,
            'applied_count' => $fake->randomDigitNotNull,
            'forwarded_count' => $fake->randomDigitNotNull,
            'shared_count' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'group_id' => $fake->randomDigitNotNull,
            'parent_id' => $fake->randomDigitNotNull,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $jobFields);
    }
}
