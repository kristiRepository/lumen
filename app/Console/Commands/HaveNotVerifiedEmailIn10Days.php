<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class HaveNotVerifiedEmailIn10Days extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "refresh:v_key";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Refresh the verification key of the users who have not verified their email in 10 days";


    /**
     * Undocumented function
     *
     * @return void
     */ 
    public function handle()
    {
         DB::table('users')
        ->where('verified', 0)
        ->where('created_at','<',date('Y-m-d',(strtotime ( '-10 day' , strtotime ( date('Y-m-d'))))))
        ->update(array('v_key'=> DB::raw('MD5(CONCAT(users.id, now()))')));
    }
}
