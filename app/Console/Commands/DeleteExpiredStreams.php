<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stream;

class DeleteExpiredStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the streams after they expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deleteStream = Stream::where('date_expiration', '<', now())->delete();
        $this->info("Deleted {$deleteStream} expired streams.");
        return 0;

    }
}
