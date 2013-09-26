<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        // Add calls to Seeders here
        $this->call('UsersTableSeeder');
        $this->command->info('User Table Seeding');

        $this->call('PostsTableSeeder');
        $this->command->info('Posts Table Seeding');

        $this->call('CommentsTableSeeder');
        $this->command->info('Comments Table Seeding');

        $this->call('RolesTableSeeder');
        $this->command->info('Roles Table Seeding');

        $this->call('PermissionsTableSeeder');
        $this->command->info('Permissions Table Seeding');
    }

}