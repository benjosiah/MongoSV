<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service\CsvtoMongoDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Events\MongoInsertion;


class CSVController extends Controller
{

    protected $convert;

    public function __contruct(){
        $this->convert = new CsvtoMongoDB();
        parent::__construct();
    }

    public function index(Request $requst)
    {
        //validation
        $this->validate($requst, [
            'csv' => 'required|mimes:csv,txt',
            'db' => 'required|string',
            'collection' => 'required|string',
        ]);

        $file = $requst->file('csv');
        $ext = $file->extension();
        $file_name = $requst['first_name'].".".$ext ;
        $store=Storage::disk('local')->put($file_name, File::get($file));
        $path = Storage::path($file_name);
        $data = [
            'db' => $requst['db'],
            'collection' => $requst['collection'],
            'path' => $path,
        ];
        $event = new MongoInsertion($data);
        event(new MongoInsertion($data));
        return response()->json(['status' => "processing", 'message' => 'Data is being processed'], 200);

    }
}
