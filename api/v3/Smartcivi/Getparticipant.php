<?php

/**
 * smartcivi.Getcontact API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_smartcivi_Getparticipant_spec(&$spec) {
  $spec['outlook']['api.required'] = 0;
}

/**
 * smartcivi.Getcontribution API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_smartcivi_Getparticipant($params) {

	createlog($params,$params['user'],$params['linkedcontactID'],'Getcontribution');

	
    
	  $sql_participant = "SELECT * FROM civicrm_participant where contact_id = ".$params['linkedcontactID'];
	  $dao_participant = CRM_Core_DAO::executeQuery( $sql_participant);
    
		$cnt = 1;
		while ($dao_participant->fetch()){
			$event_id = $dao_participant->event_id;
			
			$status_id = $dao_participant->status_id;
			
			//get participant status 
			$sql_participant_status = "select * from civicrm_participant_status_type where id = ".$status_id;
			$dao_participant_status = CRM_Core_DAO::executeQuery( $sql_participant_status);
			
			if ($dao_participant_status->fetch()){
				$participant_status = $dao_participant_status->label;
			}
			
			$values[$cnt]['status'] = $participant_status;
			
			
			
			$sql_event = "select * from civicrm_event where  id  = ".$event_id;
			$dao_event = CRM_Core_DAO::executeQuery( $sql_event);
			
			if ($dao_event->fetch()){
				$event_name = $dao_event->title;
				$values[$cnt]['event_name'] = $event_name;
			
				//event start date
				if ($dao_event->start_date){
					$date = new DateTime($dao_event->start_date);
					$start_date = $date->format('d-m-Y');
				}
				$values[$cnt]['start_date'] = $start_date;
			
				
			   //event end date
				if ($dao_event->end_date){
					$date = new DateTime($dao_event->end_date);
					$end_date = $date->format('d-m-Y');
				}
				$values[$cnt]['end_date'] = $end_date;
			
				//get event address
				$location_block_id =  $dao_event->loc_block_id;
			
				if ($location_block_id){
				
					$sql_location = "select * from civicrm_loc_block where id = ".$location_block_id;
					$dao_location = CRM_Core_DAO::executeQuery( $sql_location);
	
					if ($dao_location->fetch()){
						$address_id = $dao_location->address_id;
					}	
				}

				if ($address_id){
					
					$sql_address = "SELECT * FROM civicrm_address WHERE id = ".$address_id;
					$dao_address = CRM_Core_DAO::executeQuery( $sql_address);
	
					if ($dao_address->fetch()){
						$values[$cnt]['street_address'] = $dao_address->street_address;
						$values[$cnt]['supplemental_address_1'] = $dao_address->supplemental_address_1;
						$values[$cnt]['city'] = $dao_address->city;
						$values[$cnt]['postal_code'] = $dao_address->postal_code;
						$values[$cnt]['geo_code_1'] = $dao_address->geo_code_1;
						$values[$cnt]['geo_code_2'] = $dao_address->geo_code_2;
		
					}
				}					
			
			
			}
			
			
			
			
			
			/* $date = new DateTime($dao->receive_date);
			$values[$cnt]['receive_date'] = $date->format('d-m-Y');
			
			if ($dao->receipt_date){
				$date = new DateTime($dao->receipt_date);
				$values[$cnt]['receipt_date'] = $date->format('d-m-Y');
			
			} else {
				$values[$cnt]['receipt_date'] = $dao->receipt_date;
			} */
			
			
			$cnt = $cnt + 1;
		}	 
		
			$returnvalue = array(
						'is_error' => 0,
						'version' => 3,
						'count' => $cnt - 1,
						'values' => $values,
						);
			
			createlog($returnvalue,$params['user'],$params['linkedcontactID'],'Createconnection');
			return $returnvalue;
  
}

