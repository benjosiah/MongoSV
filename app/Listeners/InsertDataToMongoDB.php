<?php

namespace App\Listeners;

use App\Events\MongoInsertion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Service\CsvtoMongoDB;
use App\Events\MongoInsertNotification;
// import Mail
//shouldQueue: true,
use Illuminate\Support\Facades\Mail;
class InsertDataToMongoDB
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\MongoInsertion  $event
     * @return void
     */
    public function handle(MongoInsertion $event)
    {
        $convert = new CsvtoMongoDB($event->data);
        $res = $convert->csvtoMongo($event->data['path']);
        $notification = new MongoInsertNotification($res);
        event(new MongoInsertNotification($res));


    }
}
