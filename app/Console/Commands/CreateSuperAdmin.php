<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin';

    protected $description = 'Create the first Super Admin account';

    public function handle(): int
    {
        $role = Role::where('slug', 'super-admin')->first();

        if (!$role) {
            $this->error('Super Admin role was not found.');
            $this->line('Please run the RoleSeeder first.');

            return self::FAILURE;
        }

        if (User::where('role_id', $role->id)->exists()) {
            $this->error('A Super Admin account already exists.');

            return self::FAILURE;
        }

        $name = trim($this->ask('Enter Super Admin name', ''));

        if ($name === '') {
            $this->error('Name is required.');

            return self::FAILURE;
        }

        $email = strtolower(trim($this->ask('Enter Super Admin email', '')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please enter a valid email address.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('This email address is already registered.');

            return self::FAILURE;
        }

        $password = $this->secret('Enter a strong password');
        $passwordConfirmation = $this->secret('Confirm the password');

        if (!$password || $password !== $passwordConfirmation) {
            $this->error('The passwords are empty or do not match.');

            return self::FAILURE;
        }

        if (strlen($password) < 12) {
            $this->error('Use a password with at least 12 characters.');

            return self::FAILURE;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role_id = $role->id;
        $user->is_active = true;
        $user->save();

        $this->info('Super Admin account created successfully.');

        return self::SUCCESS;
    }
}
