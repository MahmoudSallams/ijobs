<?php

use Faker\Factory as Faker;
use App\Models\GroupUser;
use App\Repositories\GroupUserRepository;

trait MakeGroupUserTrait
{
    /**
     * Create fake instance of GroupUser and save it in database
     *
     * @param array $groupUserFields
     * @return GroupUser
     */
    public function makeGroupUser($groupUserFields = [])
    {
        /** @var GroupUserRepository $groupUserRepo */
        $groupUserRepo = App::make(GroupUserRepository::class);
        $theme = $this->fakeGroupUserData($groupUserFields);
        return $groupUserRepo->create($theme);
    }

    /**
     * Get fake instance of GroupUser
     *
     * @param array $groupUserFields
     * @return GroupUser
     */
    public function fakeGroupUser($groupUserFields = [])
    {
        return new GroupUser($this->fakeGroupUserData($groupUserFields));
    }

    /**
     * Get fake data of GroupUser
     *
     * @param array $postFields
     * @return array
     */
    public function fakeGroupUserData($groupUserFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'group_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word,
            'deleted_at' => $fake->word
        ], $groupUserFields);
    }
}
