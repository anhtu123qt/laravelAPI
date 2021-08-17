<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class insert_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
          [
              'name' => 'tuanh1',
              'email' => 'traicodon_timgaichuachong@yahoo.com',
              'password' => bcrypt('123456')
          ],
          [
              'name' => 'tuanh2',
              'email' => 'boycuteno1@yahoo.com',
              'password' => bcrypt('123456')
          ]
        ];
        User::insert($users);
    }
}
