<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'app:create-user
                            {--name= : ব্যবহারকারীর নাম}
                            {--email= : ইমেইল}
                            {--password= : পাসওয়ার্ড}';

    protected $description = 'নতুন shallow owner user তৈরি করুন';

    public function handle()
    {
        $name     = $this->option('name')     ?? $this->ask('নাম লিখুন');
        $email    = $this->option('email')    ?? $this->ask('ইমেইল লিখুন');
        $password = $this->option('password') ?? $this->secret('পাসওয়ার্ড লিখুন');

        if (User::where('email', $email)->exists()) {
            $this->error("এই ইমেইল আগে থেকে আছে: $email");
            return 1;
        }

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("✅ User তৈরি হয়েছে:");
        $this->table(['Field', 'Value'], [
            ['ID',       $user->id],
            ['নাম',      $user->name],
            ['Email',    $user->email],
            ['Password', $password],
        ]);

        return 0;
    }
}
