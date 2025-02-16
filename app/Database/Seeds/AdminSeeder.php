<?php

namespace App\Database\Seeds;

use App\Modules\Infrastructure\Traits\TransactionTrait;
use CodeIgniter\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $db = db_connect();
            $db->transBegin();

            $hasAdmin = (new GroupModel())->where('group', 'admin')->first();

            if ($hasAdmin) {
                return;
            }

            $user = new User();
            $userId = $user->insert([
                'username'    => env('ADMIN_USERNAME', 'admin'),
                'last_active' => Carbon::now(),
                'active'      => 1,
            ]);

            if (!$userId) {
                throw new RuntimeException('Failed to insert user');
            }

            $userIdentity = new UserIdentityModel();
            $passwords = service('passwords');
            $userIdentityAttributes = [
                'user_id' => $userId,
                'type'    => Session::ID_TYPE_EMAIL_PASSWORD,
                'secret'  => env('ADMIN_EMAIL', 'admin@example.com'),
                'secret2' => $passwords->hash(env('ADMIN_PASSWORD', 'admin1234')),
            ];

            if (!$userIdentity->insert($userIdentityAttributes)) {
                throw new RuntimeException('Failed to insert user identity');
            }


            $groupUser = new GroupModel();
            $groupUserAttributes = [
                'user_id' => $userId,
                'group'   => 'admin',
            ];

            if (!$groupUser->insert($groupUserAttributes)) {
                throw new RuntimeException('Failed to insert group-user');
            }

            $profile = new Profile();
            $profileAttributes = [
                'user_id' => $userId
            ];

            if (!$profile->insert($profileAttributes)) {
                throw new RuntimeException('Failed to insert profile');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            echo "AdminSeeder failed: " . $e->getMessage() . "\n";
        }
    }
}
