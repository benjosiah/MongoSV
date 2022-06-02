<?php
namespace App\Service;

use MongoDB\Driver\Manager;
use MongoDB\Collection;

class CsvtoMongoDB{
    protected $client;
    protected $collection;
    public function __construct($data){
        $this->client = new Manager(config('services.mongodb.host'));
        $this->collection = new Collection($this->client, $data['db'], $data['collection']);
    }

    public function csvtoMongo($path){
        $csv = file_get_contents($path);
        $lines = explode(PHP_EOL, $csv);
        $all = array();
        $head = explode(',',array_shift($lines));
        $header = true;
        $start = microtime(true);
        foreach ($lines as $line) {
            if($header){
                $header = false;
                continue;
            }
            set_time_limit(30);
            $data = explode(',', $line);
            if(!empty($data) && count($data) == count($head)){
                $document = array_combine($head, $data);
                array_push($all, $document);
                
            }
            
        }
        try{
            $insert = $this->collection->insertMany($all);
            return [
                "message"=>"Successfully inserted ".$insert->getInsertedCount()." documents",
                "code"=>200,
                "status"=>"success",
            ];
        }catch(\Exception $e){
            return [
                "messatge" => $e->getMessage(),
                "code" => $e->getCode(),
                'status' => 'failed',
            ];
        }
        
    }

   
}