<?php

/**
 * smartcivi.Getcontact API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_smartcivi_Getcontribution_spec(&$spec) {
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
function civicrm_api3_smartcivi_Getcontribution($params) {

	createlog($params,$params['user'],$params['linkedcontactID'],'Getcontribution');

	
    
	  $sql = "SELECT * FROM civicrm_contribution where contact_id = ".$params['linkedcontactID'];
	  
	  $dao = CRM_Core_DAO::executeQuery( $sql);
    
		$cnt = 1;
		while ($dao->fetch()){
			$values[$cnt]['total_amount'] = $dao->total_amount;
			
			$sql_mem_type = "select * from civicrm_option_value where option_group_id = 10 and value = ".$dao->payment_instrument_id;
			$dao_mem_type = CRM_Core_DAO::executeQuery( $sql_mem_type);
			
			if ($dao_mem_type->fetch()){
				$payment_instrument = $dao_mem_type->name;
			}
			
			$values[$cnt]['payment_instrument'] = $payment_instrument;
			$date = new DateTime($dao->receive_date);
			$values[$cnt]['receive_date'] = $date->format('d-m-Y');
			
			if ($dao->receipt_date){
				$date = new DateTime($dao->receipt_date);
				$values[$cnt]['receipt_date'] = $date->format('d-m-Y');
			
			} else {
				$values[$cnt]['receipt_date'] = $dao->receipt_date;
			}
			
			$values[$cnt]['contribution_status'] = 'completed';
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

