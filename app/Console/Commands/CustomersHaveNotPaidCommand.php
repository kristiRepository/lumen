<?php


namespace App\Console\Commands;

use App\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class CustomersHaveNotPaidCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "delete:customers";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete all the customers who have not paid for the trip before due date";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trips=Trip::where('due_date','<',date('Y-m-d'))->get();
      
        if($trips != NULL){
            foreach($trips as $trip){
                DB::table('customer_trip')->where('trip_id','=',$trip->id)->where('paid','=',NULL)->delete();
            }
        }
     
    }
}