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
        set_time_limit(0);
        $nowTime = date("H")+5;
        Log::info($nowTime);
        $postTimes = PostTime::where("time", $nowTime)->get();
        Log::info("Количество тем: ".sizeof($postTimes));
        foreach ($postTimes as $generatePost) {
            Log::info("posttime ((".$generatePost->id);
            $weak = (array) json_decode($generatePost->weak);

            Log::info("date ((".date("D"));
            if($weak[date("D")] === true){
                sleep(10);
                $infopost = $generatePost->sendPost();
                var_dump($infopost);
                Log::info("(".$generatePost->id.") PostTime: ".$generatePost->time);
            }
        }
        return true;
    }
}
