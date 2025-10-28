<?php

namespace App\Jobs;

use App\Notifications\UpdatePassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;

class NotificationUpdatedPasswordJob implements ShouldQueue
{
    use Queueable;

    private int $userId;
    /**
     * Create a new job instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) return;

        $user->notify(new UpdatePassword());
    }
}
