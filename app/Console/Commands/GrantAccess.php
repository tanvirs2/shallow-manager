<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GrantAccess extends Command
{
    protected $signature = 'app:grant-access
                            {--email= : ব্যবহারকারীর ইমেইল}
                            {--days= : কত দিন}
                            {--months= : কত মাস}
                            {--years= : কত বছর}
                            {--extend : বর্তমান মেয়াদের সাথে যোগ করো}
                            {--revoke : অ্যাক্সেস বাতিল করো}
                            {--list : সব user দেখো}';

    protected $description = 'Shallow owner-এর অ্যাক্সেস duration নিয়ন্ত্রণ করুন';

    public function handle()
    {
        // List all users
        if ($this->option('list')) {
            $users = User::where('is_admin', false)->get();
            $rows  = $users->map(fn($u) => [
                $u->id,
                $u->name,
                $u->email,
                $u->expires_at?->format('d/m/Y H:i') ?? '—',
                $u->isActive() ? '✅ সক্রিয়' : '❌ মেয়াদ শেষ',
                $u->isActive() ? $u->daysRemaining() . ' দিন বাকি' : '—',
            ]);
            $this->table(['ID', 'নাম', 'Email', 'Expires At', 'Status', 'বাকি'], $rows);
            return 0;
        }

        $email = $this->option('email') ?? $this->ask('ইমেইল লিখুন');
        $user  = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User পাওয়া যায়নি: $email");
            return 1;
        }

        // Revoke access
        if ($this->option('revoke')) {
            $user->update(['expires_at' => now()->subDay()]);
            $this->warn("❌ {$user->name} ({$user->email}) এর অ্যাক্সেস বাতিল করা হয়েছে।");
            return 0;
        }

        // Calculate new expiry
        $days   = (int)($this->option('days')   ?? 0);
        $months = (int)($this->option('months') ?? 0);
        $years  = (int)($this->option('years')  ?? 0);

        if ($days === 0 && $months === 0 && $years === 0) {
            $type = $this->choice('কত সময়ের জন্য?', ['দিন', 'মাস', 'বছর'], 1);
            $amount = (int)$this->ask('পরিমাণ লিখুন (সংখ্যা)');
            match($type) {
                'দিন'  => $days   = $amount,
                'মাস'  => $months = $amount,
                'বছর' => $years  = $amount,
            };
        }

        $extend = $this->option('extend');
        $base   = ($extend && $user->expires_at?->isFuture()) ? $user->expires_at : now();

        $newExpiry = $base->copy()
            ->addDays($days)
            ->addMonths($months)
            ->addYears($years);

        $user->update(['expires_at' => $newExpiry]);

        $this->info("✅ অ্যাক্সেস দেওয়া হয়েছে:");
        $this->table(['Field', 'Value'], [
            ['নাম',       $user->name],
            ['Email',    $user->email],
            ['Expires',  $newExpiry->format('d/m/Y H:i')],
            ['বাকি দিন', $user->fresh()->daysRemaining() . ' দিন'],
        ]);

        return 0;
    }
}
