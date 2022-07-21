<?php

namespace LebedevSoft\UisSync\Http\Controllers;

use LebedevSoft\Uis\Libs\Uis;
use LebedevSoft\UisSync\Models\UisCallsReports;
use LebedevSoft\UisSync\Models\UisCallLegsReports;
use LebedevSoft\UisSync\Models\UisEmployees;
use LebedevSoft\UisSync\Models\UisTags;
use LebedevSoft\UisSync\Models\UisStatuses;
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
    function loadUisCallsReport($date_range = null)
    {
        print_r("Start uis calls_report synchronization\n");
        $db_calls_report = new UisCallsReports();
       
            if (isset($date_range)) {
				if ($date_range==1){
                $date_till = date("Y-m-d 00:00:00");
				$date_from = date("Y-m-d 00:00:00", strtotime($date_till.'- '.$date_range.' days'));
				$days=1;
				}
				else
				{			    
				$date_from = date("Y-m-d 00:00:00", strtotime(explode("/",$date_range)[0]));
				$date_till = date("Y-m-d 00:00:00", strtotime(explode("/",$date_range)[1]));
				$diff = abs(strtotime($date_till) - strtotime($date_from)); 
				$years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				}
            }

            print_r("$date_from - $date_till\n");
            $get = true;
            $total_calls = 0;
			
			for($i=1;$i<=$days;$i++)
			{
			  	
             for($j=1;$j<=24;$j++)
			  {			
				$date_end=date("Y-m-d H:i:s", strtotime($date_from.'+ '.$j.' hours'));
                $date_beg = date("Y-m-d H:i:s", strtotime($date_end.'- 1 hours'));
				
				
                $uis_calls = $this->uis->getCallsReport($date_beg, $date_end);
                if (!empty($uis_calls["result"]["data"])) {
                    $call_list = null;
                    $total_calls += count($uis_calls["result"]["data"]);
                    foreach ($uis_calls["result"]["data"] as $call) {
                        $tmp_call = [
                            "id" => $call["id"],
                            "source" => $call["source"],
                            "is_lost" => $call["is_lost"],
							"direction" => $call["direction"],
                            "start_time" => $call["start_time"],
							"finish_time" => $call["finish_time"],
							"call_records" => empty($call["call_records"]) ? null : $call["call_records"][0],
							"cpn_region_id" => $call["cpn_region_id"],
							"talk_duration" => $call["talk_duration"],
							"wait_duration" => $call["wait_duration"],
							"total_duration" => $call["total_duration"],
							"cpn_region_name" => $call["cpn_region_name"],
							"communication_id" => $call["communication_id"],
							"communication_type" => $call["communication_type"],
							"contact_phone_number" => $call["contact_phone_number"],
							"virtual_phone_number" => $call["virtual_phone_number"],
							"finish_reason" => $call["finish_reason"] 
							
    

                        ];
                        $call_list[] = $tmp_call;
                    }
                    $db_calls_report->upsert($call_list, ['id'], ['source', 'is_lost', 'direction', 'start_time',
                        'finish_time', 'call_records', 'cpn_region_id', 'talk_duration', 'wait_duration', 'total_duration', 'cpn_region_name',
                        'cpn_region_name', 'communication_id', 'communication_type', 'contact_phone_number', 'virtual_phone_number', 'finish_reason']);
                }
                 print_r("$date_beg - $date_end, calls_report number - $total_calls\n");
          
			 }
		 $date_from = date("Y-m-d 00:00:00", strtotime($date_from.'+ 1 days')); 
		}
        print_r("End uis calls_report synchronization\n");
    }
	public
    function loadUisCallLegsReport($date_range = null)
    {
        print_r("Start uis call_legs_report synchronization\n");
        $db_calls = new UisCallLegsReports();

             if (isset($date_range)) {
				if ($date_range==1){
                $date_till = date("Y-m-d 00:00:00");
				$date_from = date("Y-m-d 00:00:00", strtotime($date_till.'- '.$date_range.' days'));
				$days=1;
				}
				else
				{			    
				$date_from = date("Y-m-d 00:00:00", strtotime(explode("/",$date_range)[0]));
				$date_till = date("Y-m-d 00:00:00", strtotime(explode("/",$date_range)[1]));
				$diff = abs(strtotime($date_till) - strtotime($date_from)); 
                $years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				}
            }
			
			print_r("$date_from - $date_till\n");
            $total_calls = 0;
			
			for($i=1;$i<=$days;$i++)
			 {
              for($j=1;$j<=24;$j++)
			  {
				$date_end=date("Y-m-d H:i:s", strtotime($date_from.'+ '.$j.' hours'));
                $date_beg = date("Y-m-d H:i:s", strtotime($date_end.'- 1 hours'));
				
            
                $uis_calls = $this->uis->getCallLegsReport($date_beg, $date_end);
                if (!empty($uis_calls["result"]["data"])) {
                    $call_list = null;
                    $total_calls += count($uis_calls["result"]["data"]);
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
                            "start_time" => $call["start_time"],
							"action_name" => $call["action_name"],
							"employee_id" => $call["employee_id"],
							"call_session_id" => $call["call_session_id"],
							"total_duration" => $call["total_duration"],
							"finish_reason" => $call["finish_reason"],
							"calling_phone_number" => $call["calling_phone_number"],
							"virtual_phone_number" => $call["virtual_phone_number"]
                        ];
                        $call_list[] = $tmp_call;
                    }
                    $db_calls->upsert($call_list, ['id'], ['duration', 'group_id', 'is_coach', 'action_id',
                        'direction', 'is_failed', 'group_name', 'start_time', 'action_name', 'employee_id', 'call_session_id',
                        'total_duration', 'finish_reason', 'calling_phone_number', 'virtual_phone_number']);
                }
                print_r("$date_beg - $date_end, call_legs_report number - $total_calls\n");
		       	    
            }
		  $date_from = date("Y-m-d 00:00:00", strtotime($date_from.'+ 1 days')); 
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
            $tmp_employee = [
                "id" => $employee["id"],
                "full_name" => $employee["full_name"],
			    "email" => empty($employee["email"]) ? null : $employee["email"],
                "group_id" => empty($employee["groups"]) ? null : $employee["groups"][0]["group_id"],
                "group_name" => empty($employee["groups"]) ? null : $employee["groups"][0]["group_name"],
                "status_id" => $employee["status_id"],
				"extension_phone_number" => empty($employee["extension"]) ? null : $employee["extension"]["extension_phone_number"],
				"phone_number" => empty($employee["phone_numbers"]) ? null : $employee["phone_numbers"][0]["phone_number"],
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
                "rating" => $tag["rating"],
                "is_system" => $tag["is_system"]
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
				"priority" => $status["priority"],
                "is_deleted" => $status["is_deleted"],
                "description" => $status["description"],
				"is_worktime" => $status["is_worktime"]				
            ];

            $statuses_list[] = $tmp_status;
        }
      
        $db_statuses = new UisStatuses();
        $db_statuses->upsert($statuses_list, ['id'], ['name', 'color', 'priority', 'is_deleted', 'description', 'is_worktime']);
		 }
        print_r("End uis statuses synchronization\n");
    }

}
