<?php

namespace App\Database\Seeds;

use App\Models\GroupModel;
use App\Models\ProfileModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Faker\Factory;
use RuntimeException;
use Throwable;

class UsersSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $db = db_connect();
        $db->transBegin();
        $faker = Factory::create('ru_RU');

        try {
            $userModel = new UserModel();
            $identityModel = new UserIdentityModel();
            $groupModel = new GroupModel();
            $profileModel = new ProfileModel();

            $passwords = service('passwords');
            $createdUsers = 0;

            while ($createdUsers < 50) {
                $userId = $userModel->insert([
                    'username' => $faker->unique()->userName(),
                    'last_active' => $faker->dateTimeBetween('-10 days', 'now')->format('Y-m-d H:i:s'),
                    'active' => 1,
                ]);

                if (!$userId) {
                    throw new RuntimeException("Error creating user - {$createdUsers}");
                }

                $identityModelId = $identityModel->insert([
                    'user_id' => $userId,
                    'type' => Session::ID_TYPE_EMAIL_PASSWORD,
                    'secret' => $faker->unique()->email(),
                    'secret2' => $passwords->hash('password123'),
                ]);

                if (!$identityModelId) {
                    throw new RuntimeException("Error creating user identity - {$createdUsers}");
                }

                $groupModelId = $groupModel->insert([
                    'user_id' => $userId,
                    'group' => 'user',
                ]);

                if (!$groupModelId) {
                    throw new RuntimeException("Error creating user group - {$createdUsers}");
                }

                $profile = $profileModel->where('user_id', $userId)->first();

                $profile->fill([
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'description' => $faker->sentence(10),
                ]);

                if (!$profileModel->update($profile->id, $profile->toArray())) {
                    throw new RuntimeException("Error when inserting profile for user - {$createdUsers}");
                }

                $createdUsers++;

                if ($createdUsers % 10 == 0) {
                    echo "Created {$createdUsers} users...\n";
                }
            }

            $db->transCommit();
            echo "50 test users successfully created!\n";
        } catch (Throwable $e) {
            $db->transRollback();
            echo "Error UsersSeeder: " . $e->getMessage() . "\n";
        }
    }
}