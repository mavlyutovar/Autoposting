<?php

namespace App\Console\Commands;

use App\Group;
use App\PostTime;
use App\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HourlyCheckPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postTime:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда отправляет все посты, которые в стилях подходят по времени';

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
        $nowTime = date("H");
        Log::info($nowTime);
        $postTimes = PostTime::where("time", $nowTime)->get();
        foreach ($postTimes as $generatePost) {
            $weak = (array) json_decode($generatePost->weak);
            if($weak[strtolower(date("D"))] == true){
                $infopost = $generatePost->sendPost();
                Log::info("(".$generatePost->id.") PostTime: ".$generatePost->time);
            }
            return true;
        }
    }
}
