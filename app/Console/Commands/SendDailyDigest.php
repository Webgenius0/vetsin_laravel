<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendDailyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:daily-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily digest notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily digest notifications...');

        $users = User::whereNotNull('device_token')
            ->where('notifications_enabled', true)
            ->get();
        $count = 0;

        foreach ($users as $user) {
            // Calculate new activity counts for the last 24 hours
            $yesterday = Carbon::now()->subDay();

            $newMatches = $user->favoritedBy()
                ->where('created_at', '>=', $yesterday)
                ->count();

            $newMessages = $user->conversations()
                ->withCount(['messages' => function ($query) use ($yesterday) {
                    $query->where('created_at', '>=', $yesterday)
                          ->where('sendable_id', '!=', $user->id);
                }])
                ->get()
                ->sum('messages_count');

            $newFavorites = $user->favoritedBy()
                ->where('created_at', '>=', $yesterday)
                ->count();

            // Only send if there's activity
            if ($newMatches > 0 || $newMessages > 0 || $newFavorites > 0) {
                NotificationService::sendDailyDigest($user, $newMatches, $newMessages, $newFavorites);
                $count++;
            }
        }

        $this->info("Daily digest sent to {$count} users.");
    }
} 