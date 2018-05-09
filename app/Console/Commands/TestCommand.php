<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Services\LogService;
use App\Jobs\StoreLogJob;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'just test';

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
     * @return mixed
     */
    public function handle()
    {
        StoreLogJob::dispatch('这个是未来测试');
    }
}
