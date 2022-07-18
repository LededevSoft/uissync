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
								{--calls_report : For sync chats}
                                {--call_legs_report : For sync contacts}
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
        if ($options["calls"]) {
            $amo->loadAmoCalls($date_range);
        } elseif ($options["contacts"]) {
            $amo->loadAmoContacts($date_range);
		}elseif ($options["notes"]) {
            $amo->loadAmoNotesLeads($date_range);
			$amo->loadAmoNotesContacts($date_range);
		} elseif ($options["all"]) {
            $uis->loadUisEmployees();
            $uis->loadUisTags();
            $uis->loadUisStatuses();
			$uis->loadUisCalls_report($date_range);
			$uis->loadUisCall_legs_report($date_range);
        } else {
            print_r("Not supported command\n");
        }
        return 0;
    }
}
