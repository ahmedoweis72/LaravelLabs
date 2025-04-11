<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PruneOldPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $cutoffDate = now()->subYears(2);
        $deletedCount = Post::where('created_at', '<', $cutoffDate)->delete();
        
        Log::info("Pruned {$deletedCount} old posts created before {$cutoffDate}");
    }
}

// php artisan schedule:work
// php artisan schedule:run