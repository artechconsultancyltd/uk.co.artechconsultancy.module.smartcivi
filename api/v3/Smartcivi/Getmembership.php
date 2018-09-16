<?php

/**
 * smartcivi.Getcontact API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_smartcivi_Getmembership_spec(&$spec) {
  $spec['outlook']['api.required'] = 0;
}

/**
 * smartcivi.Getmembership API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_smartcivi_Getmembership($params) {

	createlog($params,$params['user'],$params['linkedcontactID'],'Getmembership');
	  
	  $sql = "SELECT * FROM civicrm_membership where contact_id = ".$params['linkedcontactID'];
	  
	  $dao = CRM_Core_DAO::executeQuery( $sql);
    
		$cnt = 1;
		while ($dao->fetch()){
			
			$sql_mem_type = "select * from civicrm_membership_type where id = ".$dao->membership_type_id;
			$dao_mem_type = CRM_Core_DAO::executeQuery( $sql_mem_type);
			
			if ($dao_mem_type->fetch()){
				$membership_name = $dao_mem_type->name;
			}
			$values[$cnt]['membership'] = $membership_name;
			$date = new DateTime($dao->join_date);
			$values[$cnt]['join_date'] = $date->format('d-m-Y');
			
			$date = new DateTime($dao->start_date);
			$values[$cnt]['start_date'] = $date->format('d-m-Y');
			
			
			$date = new DateTime($dao->end_date);
			$values[$cnt]['end_date'] = $date->format('d-m-Y');
			
			$sql_mem_status = "select * from civicrm_membership_status where id = ".$dao->status_id;
			$dao_mem_status = CRM_Core_DAO::executeQuery( $sql_mem_status);
			
			if ($dao_mem_status->fetch()){
				$status = $dao_mem_status->name;
			}
			
			$values[$cnt]['status'] = $status;
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

