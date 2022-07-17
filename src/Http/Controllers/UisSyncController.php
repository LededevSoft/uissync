<?php

namespace Adminka\UisSync\Http\Controllers;

use Adminka\AmoCRM\Libs\Uis;
use Adminka\AmoSync\Models\UisCalls_report;
use Adminka\AmoSync\Models\UisCall_legs_report;
use Adminka\AmoSync\Models\UisEmployees;
use Adminka\AmoSync\Models\UisTags;
use Adminka\AmoSync\Models\UisStatuses;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UisSyncController extends Controller
{
    private $uis;

    public function __construct()
    {
        $app_id = config("uis.app_id");
        $this->uis = new Uis($app_id);
    }

    public
    function loadUisCalls_report($date_range = null)
    {
        print_r("Start uis calls_report synchronization\n");
        $db_calls_report = new UisCalls_report();

            if ($date_range) {
                $date_till = date("Y-m-d 00:01:00");
				$date_from=date("Y-m-d 23:59:00", strtotime($date_till.'- '.$date_range.' days'));

            }
			else
			{
				$date_till = date("Y-m-d 00:01:00");
				$date_from=date("Y-m-d 23:59:00", strtotime($date_till.'- 1000 days'));
			}
			
            $get = true;
            $page = 0;
            $total_calls = 0;
            while ($get) {
                $uis_calls = $this->amo->getCalls_report($date_from, $date_till, $page);
                if (!empty($uis_calls)) {
                    $call_list = null;
                    $total_calls += sizeof($uis_calls["result"]["data"]);
                    foreach ($uis_calls["result"]["data"] as $call) {
                        $tmp_call = [
                            "id" => $call["id"],
                            "source" => $call["source"],
                            "is_lost" => $call["is_lost"],
							"direction" => $call["direction"],
                            "start_time" => strtotime($call["start_time"]),
							"finish_time" => strtotime($call["finish_time"]),
							"call_records" => $call["call_records"][0],
							"cpn_region_id" => $call["cpn_region_id"],
							"talk_duration" => $call["talk_duration"],
							"wait_duration" => $call["wait_duration"],
							"total_duration" => $call["total_duration"],
							"cpn_region_name" => $call["cpn_region_name"],
							"communication_id" => $call["communication_id"],
							"communication_type" => $call["communication_type"],
							"contact_phone_number" => $call["contact_phone_number"],
							"virtual_phone_number" => $call["virtual_phone_number"]
    
                           // "phone" => empty($call["params"]["phone"]) ? null : $call["params"]["phone"],

                        ];
                        $call_list[] = $tmp_call;
                    }
                    $db_calls_report->upsert($call_list, ['id'], ['source', 'is_lost', 'direction', 'start_time',
                        'finish_time', 'call_records', 'cpn_region_id', 'talk_duration', 'wait_duration', 'total_duration', 'cpn_region_name',
                        'cpn_region_name', 'communication_id', 'communication_type', 'contact_phone_number', 'virtual_phone_number']);
                
                print_r("Page - $page, calls_report number - $total_calls\n");
                if (isset($uis_calls["result"]["data"])) {
                    $page++;
                } else {
                    $get = false;
                }
             }
        }
        print_r("End uis calls_report synchronization\n");
    }
	public
    function loadUisCall_legs_report($date_range = null)
    {
        print_r("Start uis call_legs_report synchronization\n");
        $db_calls_report = new UisCall_legs_report();

            if ($date_range) {
                $date_till = date("Y-m-d 00:01:00");
				$date_from=date("Y-m-d 23:59:00", strtotime($date_till.'- '.$date_range.' days'));

            }
			else
			{
				$date_till = date("Y-m-d 00:01:00");
				$date_from=date("Y-m-d 23:59:00", strtotime($date_till.'- 1000 days'));
			}
			
            $get = true;
            $page = 0;
            $total_calls = 0;
            while ($get) {
                $uis_calls = $this->amo->getCall_legs_report($date_from, $date_till, $page);
                if (!empty($uis_calls)) {
                    $call_list = null;
                    $total_calls += sizeof($uis_calls["result"]["data"]);
                    foreach ($uis_calls["result"]["data"] as $call) {
                        $tmp_call = [
                            "id" => $call["id"],
                            "duration" => $call["duration"],
                            "group_id" => $call["group_id"],
							"is_coach" => $call["is_coach"],
							"action_id" => $call["action_id"],
							"direction" => $call["direction"],
							"is_failed" => $call["is_failed"],
							"group_name" => $call["group_name"],
                            "start_time" => strtotime($call["start_time"]),
							"action_name" => $call["action_name"],
							"employee_id" => $call["employee_id"],
							"call_session_id" => $call["call_session_id"],
							"total_duration" => $call["total_duration"],
							"finish_reason" => $call["finish_reason"],
							"calling_phone_number" => $call["calling_phone_number"],
							"virtual_phone_number" => $call["virtual_phone_number"]

                           // "phone" => empty($call["params"]["phone"]) ? null : $call["params"]["phone"],

                        ];
                        $call_list[] = $tmp_call;
                    }
                    $db_calls_report->upsert($call_list, ['id'], ['duration', 'group_id', 'is_coach', 'action_id',
                        'direction', 'is_failed', 'group_name', 'start_time', 'action_name', 'employee_id', 'call_session_id',
                        'total_duration', 'finish_reason', 'calling_phone_number', 'virtual_phone_number']);
                }
                print_r("Page - $page, call_legs_report number - $total_calls\n");
                if (isset($uis_calls["result"]["data"])) {
                    $page++;
                } else {
                    $get = false;
                }
            }
        
        print_r("End uis call_legs_report synchronization\n");
    }
	public
    function loadUisEmployees()
    {
        print_r("Start uis employees synchronization\n");
		
        $uis_employees = $this->uis->getEmployees();
        $employees_list = null;
         if (!empty($uis_employees["result"]["data"])) {    
        foreach ($uis_employees["result"]["data"] as $employee) {
            $tmp_employees = [
                "id" => $employee["id"],
                "full_name" => $employee["full_name"],
			    "email" => $employee["email"],
                "group_id" => $employee["groups"]["group_id"],
                "group_name" => $employee["groups"]["group_name"],
                "status_id" => $employee["status_id"],
				"extension_phone_number" => $employee["extension"]["phone_number"],
				"phone_number" => $employee["phone_numbers"]["phone_number"]
            ];

            $employees_list[] = $tmp_employee;
        }
      
        $db_employees = new UisEmployees();
        $db_employees->upsert($employees_list, ['id'], ['full_name', 'email', 'group_id', 'group_name', 'status_id', 'extension_phone_number', 'phone_number']);
		 }
        print_r("End uis employees synchronization\n");
    }
    public
    function loadUisTags()
    {
        print_r("Start uis tags synchronization\n");
		
        $uis_tags = $this->uis->getTags();
        $tags_list = null;
         if (!empty($uis_tags["result"]["data"])) {    
        foreach ($uis_tags["result"]["data"] as $tag) {
            $tmp_tag = [
                "id" => $tag["id"],
                "name" => $tag["name"],
                "rating" => $user["rating"],
                "is_system" => $user["is_system"]
            ];

            $tags_list[] = $tmp_tag;
        }
      
        $db_tags = new UisTags();
        $db_tags->upsert($tags_list, ['id'], ['name', 'rating', 'is_system']);
		 }
        print_r("End uis tags synchronization\n");
    }
	public
    function loadUisStatuses()
    {
        print_r("Start uis statuses synchronization\n");
		
        $uis_statuses = $this->uis->getStatuses();
        $statuses_list = null;
         if (!empty($uis_statuses["result"]["data"])) {    
        foreach ($uis_statuses["result"]["data"] as $status) {
            $tmp_status = [
                "id" => $status["id"],
                "name" => $status["name"],
                "color" => $status["color"],
                "mnemonic" => $status["mnemonic"],
				"priority" => $status["priority"],
                "is_deleted" => $status["is_deleted"],
                "description" => $status["description"],
				"is_worktime" => $status["is_worktime"]				
            ];

            $Statuses_list[] = $tmp_status;
        }
      
        $db_statuses = new UisStatuses();
        $db_statuses->upsert($statuses_list, ['id'], ['name', 'color', 'mnemonic', 'priority', 'is_deleted', 'description', 'is_worktime']);
		 }
        print_r("End uis statuses synchronization\n");
    }

}
