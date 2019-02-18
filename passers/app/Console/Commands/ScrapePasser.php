<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapePasser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:passer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2018 PSHS NCE Passers';

    /**
     * The variable containing an array of the collection slugs collected from the URL.
     * 
     */
    protected $collections = [
        'examinee-id',
        'name-of-examinee',
        'campus-eligibility',
        'school',
        'division',
    ];

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
        //
        foreach ($collections as $collection) {
            $this->scrape($collection);
        }
    }

/**
 * For scraping data for the specified collection.
 *
 * @param  string $collection
 * @return boolean
 */
public static function scrape($collection)
{
    $crawler = Goutte::request('GET', env('FUNKO_POP_URL').'/'.$collection);

    $pages = ($crawler->filter('footer .pagination li')->count() > 0)
        ? $crawler->filter('footer .pagination li:nth-last-child(2)')->text()
        : 0
    ;

    for ($i = 0; $i < $pages + 1; $i++) {
        if ($i != 0) {
            $crawler = Goutte::request('GET', env('PSHS_NCE_PASSERS_URL').'/'.$collection.'?page='.$i);
        }

        $crawler->filter('.product-item')->each(function ($node) {
            $sku   = explode('#', $node->filter('.product-sku')->text())[1];
            $title = trim($node->filter('.title a')->text());

            print_r($sku.', '.$title);
        });
    }

    return true;
}
}
