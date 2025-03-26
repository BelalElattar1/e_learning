<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Subscribe;
use Illuminate\Console\Command;

class ExpireTeacherSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a teacher whose subscription has been terminated so that their courses are not displayed on the platform to students.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $expired_subscriptions = Subscribe::where('status', 'active')->where('end', '<', Carbon::now())->get();
        $expired_subscriptions->each->update(['status' => 'expired']);

        $expired_subscriptions->each(function ($subscription) {
            if (!Subscribe::where('teacher_id', $subscription->teacher_id)->where('status', 'active')->exists()) {
                $subscription->teacher->update(['is_subscriber' => 0]);
            }
        });

    }
}
