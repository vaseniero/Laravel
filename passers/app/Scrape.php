<?php

namespace App;

use Goutte;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Scrape extends Model
{
    private $row;
    private $col;
    private $obj;
    
    public function __construct(array $attributes = [])
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('passers')->truncate();

        $this->row = 0;
        $this->col = 0;
        $this->obj = array();
    }

    public function scrape()
    {
        $crawler = Goutte::request('GET', env('PSHS_NCE_PASSERS_URL'));

        $crawler->filter('.container_list div .border_list')->each(function ($node) {
            $this->dump_scrape_and_save($node->text(),$this->col,$this->obj);
        });
    }

    private function dump_scrape_and_save($item,$col,$obj)
    {
        if (is_numeric($item)) {
            $col = 1;
        }
        else {
            switch ($col) {
                case 1:
                    $obj['name_of_examinee'] = $item;
                    $col++;
                    break;
                case 2:
                    $obj['campus_eligibility'] = $item;
                    $col++;
                    break;
                case 3:
                    $obj['school'] = $item;
                    $col++;
                    break;
                case 4:
                    $obj['division'] = $item;
                    $col++;
                    break;
                default:
                    break;
            }                
        }

        if ($col == 5) {
            DB::table('passers')->insert($obj);
            $this->col = 1;
            $this->obj = array();
        }
        else {
            $this->col = $col;
            $this->obj = $obj;
        }
    }
}
