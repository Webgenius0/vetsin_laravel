<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendProfileReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:profile-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send profile completion reminder notifications to users with incomplete profiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting profile completion reminders...');

        // Get users with incomplete profiles who have device tokens and notifications enabled
        $users = User::whereNotNull('device_token')
            ->where('notifications_enabled', true)
            ->where(function ($query) {
                $query->whereNull('date_of_birth')
                      ->orWhereNull('location')
                      ->orWhereNull('relationship_goal')
                      ->orWhereNull('about_me');
            })
            ->get();

        $count = 0;

        foreach ($users as $user) {
            NotificationService::sendProfileCompletionReminder($user);
            $count++;
        }

        $this->info("Profile completion reminders sent to {$count} users.");
    }
} 