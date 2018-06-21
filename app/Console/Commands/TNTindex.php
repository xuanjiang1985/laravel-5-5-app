<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TeamTNT\TNTSearch\TNTSearch;

class TNTindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'created index for TNT engine';

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
        $tnt = new TNTSearch;

        $tnt->loadConfig([
            'driver'    => 'mysql',
            'host'      => env('DB_HOST'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'storage'   => storage_path()
        ]);

        $indexer = $tnt->createIndex('article.index');
        $indexer->query('SELECT id, title, author FROM article;');
        //$indexer->setLanguage('german');
        $indexer->run();
    }
}
