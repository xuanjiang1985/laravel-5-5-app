<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

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
    protected $description = 'make hash value test';

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
        // $start = 201804;
        // dd($start.'00');
        $start = strtotime('20180401');
        $end = strtotime('20180401'.'+1 month -1 day');
       dd($start, date('Ymd H:i:s',$start), $end, date('Ymd H:i:s', $end));
    }
}
