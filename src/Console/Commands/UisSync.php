<?php

namespace LebedevSoft\UisSync\Console\Commands;

use LebedevSoft\UisSync\Http\Controllers\UisSyncController;
use Illuminate\Console\Command;

class UisSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uis:sync
                                {--all : For sync all data}
                                {--hours : For sync all data hours} 								
								{--calls_report : For sync calls}
                                {--call_legs_report : For sync call legs}
								{--full : For sync data from all time}
                                {--time_range=1 : Set day number for data sync (integer)}
                                {--statuses : For sync statuses}
								{--employees : For sync employees}
								{--tags : For sync tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uis sync';

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
     * @return int
     */
    public function handle()
    {
        $options = $this->options();
        $uis = new UisSyncController();
        if ($options["full"]) {
            $date_range = null;
        } else {
            $date_range = $options["time_range"];
        }
        if ($options["employees"]) {
           $uis->loadUisEmployees();
        } elseif ($options["tags"]) {
            $uis->loadUisTags();
		} elseif ($options["statuses"]) {
            $uis->loadUisStatuses();	
		}elseif ($options["calls_report"]) {
           $uis->loadUisCallsReport($date_range);
		}elseif ($options["call_legs_report"]) {
           $uis->loadUisCallLegsReport($date_range);
		}elseif ($options["hours"]) {
            $uis->loadUisCallsReport(0);		
			$uis->loadUisCallLegsReport(0);  
		} elseif ($options["all"]) {
            $uis->loadUisEmployees();
            $uis->loadUisTags();
            $uis->loadUisStatuses();
			$uis->loadUisCallsReport($date_range);		
			$uis->loadUisCallLegsReport($date_range);
			
        } else {
            print_r("Not supported command\n");
        }
        return 0;
    }
}
