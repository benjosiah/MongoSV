<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Service\CsvtoMongoDB;

class MongoDB extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_csv_upload()
    {
        $response = $this->post('/csv', [
            'csv' => 'required|mimes:csv,txt',
            'db' => 'required|string',
            'collection' => 'required|string',
        ]);

        $response->assertStatus(302);
    }

    public function test_mongodb_connection()
    {
        // 
        $path = storage_path('app/test.csv');
        $data = [
            'csv' => $path,
            'db' => 'test',
            'collection' => 'test',
        ];
        $response = new CsvtoMongoDB($data);
        $res = $response->csvtoMongo($path);
        $this->assertTrue($res instanceof \MongoDB\InsertManyResult);
    }

}


