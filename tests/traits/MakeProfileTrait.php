<?php

use Faker\Factory as Faker;
use App\Models\Profile;
use App\Repositories\ProfileRepository;

trait MakeProfileTrait
{
    /**
     * Create fake instance of Profile and save it in database
     *
     * @param array $profileFields
     * @return Profile
     */
    public function makeProfile($profileFields = [])
    {
        /** @var ProfileRepository $profileRepo */
        $profileRepo = App::make(ProfileRepository::class);
        $theme = $this->fakeProfileData($profileFields);
        return $profileRepo->create($theme);
    }

    /**
     * Get fake instance of Profile
     *
     * @param array $profileFields
     * @return Profile
     */
    public function fakeProfile($profileFields = [])
    {
        return new Profile($this->fakeProfileData($profileFields));
    }

    /**
     * Get fake data of Profile
     *
     * @param array $postFields
     * @return array
     */
    public function fakeProfileData($profileFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'first_name' => $fake->word,
            'last_name' => $fake->word,
            'email' => $fake->word,
            'age' => $fake->randomDigitNotNull,
            'mobile' => $fake->randomDigitNotNull,
            'other_mobile' => $fake->randomDigitNotNull,
            'mobile_verify_code' => $fake->word,
            'mobile_verify_status' => $fake->randomDigitNotNull,
            'region_id' => $fake->randomDigitNotNull,
            'country_id' => $fake->randomDigitNotNull,
            'city_id' => $fake->randomDigitNotNull,
            'gender' => $fake->word,
            'brief' => $fake->text,
            'photo' => $fake->word,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $profileFields);
    }
}
