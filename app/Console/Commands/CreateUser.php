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
                            {--password= : পাসওয়ার্ড}
                            {--days= : কত দিন অ্যাক্সেস}
                            {--months= : কত মাস অ্যাক্সেস}
                            {--years= : কত বছর অ্যাক্সেস}
                            {--admin : Admin হিসেবে তৈরি করো (মেয়াদ নেই)}';

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

        $isAdmin   = $this->option('admin');
        $expiresAt = null;

        if (!$isAdmin) {
            $days   = (int)($this->option('days')   ?? 0);
            $months = (int)($this->option('months') ?? 0);
            $years  = (int)($this->option('years')  ?? 0);

            if ($days === 0 && $months === 0 && $years === 0) {
                $type   = $this->choice('কত সময়ের জন্য অ্যাক্সেস দেবেন?', ['দিন', 'মাস', 'বছর'], 1);
                $amount = (int)$this->ask('পরিমাণ লিখুন');
                match($type) {
                    'দিন'  => $days   = $amount,
                    'মাস'  => $months = $amount,
                    'বছর' => $years  = $amount,
                };
            }

            $expiresAt = now()->addDays($days)->addMonths($months)->addYears($years);
        }

        $user = User::create([
            'name'       => $name,
            'email'      => $email,
            'password'   => Hash::make($password),
            'expires_at' => $expiresAt,
            'is_admin'   => $isAdmin,
        ]);

        $this->info("✅ User তৈরি হয়েছে:");
        $this->table(['Field', 'Value'], [
            ['ID',      $user->id],
            ['নাম',     $user->name],
            ['Email',   $user->email],
            ['Password',$password],
            ['Type',    $isAdmin ? 'Admin (সীমাহীন)' : 'Owner'],
            ['Expires', $expiresAt?->format('d/m/Y H:i') ?? 'সীমাহীন'],
        ]);

        return 0;
    }
}
