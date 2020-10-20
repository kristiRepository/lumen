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
     * Undocumented function
     *
     * @return void
     */ 
    public function handle()
    {
         DB::table('customer_trip')
        ->join('trips','customer_trip.trip_id','=','trips.id')
        ->join('customers','customer_trip.customer_id','=','customers.id')
        ->where('due_date', '<', date('Y-m-d'))
        ->where('paid', '=', NULL)
        ->delete();
    }
}
