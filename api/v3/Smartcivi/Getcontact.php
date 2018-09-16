<?php

/**
 * smartcivi.Getcontact API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_smartcivi_Getcontact_spec(&$spec) {
  $spec['outlook']['api.required'] = 0;
}

/**
 * smartcivi.Getcontact API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_smartcivi_Getcontact($params) {

	createlog($params,$params['user'],$params['linkedcontactID'],'Getcontact');

	$sql = "SELECT * FROM civicrm_contact WHERE id = ".$params['linkedcontactID'];
	$dao = CRM_Core_DAO::executeQuery( $sql);
	CRM_Core_Error::debug_log_message( 'sql = '. print_r($sql,true), $out = false );
	
	$cnt = 1;
	if ($dao->fetch()){
		$values[$cnt]['first_name'] = $dao->first_name;
		$values[$cnt]['last_name'] = $dao->last_name;
		$values[$cnt]['contact_type'] = $dao->contact_type;
		$values[$cnt]['gender'] = $dao->gender_id;
		$values[$cnt]['image_URL'] = $dao->image_URL;
		
	}
	
	$sql = "SELECT * FROM civicrm_address WHERE is_primary = 1 AND contact_id = ".$params['linkedcontactID'];
	$dao = CRM_Core_DAO::executeQuery( $sql);
	
	if ($dao->fetch()){
	    $values[$cnt]['street_address'] = $dao->street_address;
		$values[$cnt]['supplemental_address_1'] = $dao->supplemental_address_1;
		$values[$cnt]['city'] = $dao->city;
		$values[$cnt]['postal_code'] = $dao->postal_code;
		
	}
	
	$sql = "SELECT * FROM civicrm_phone WHERE is_primary = 1 AND contact_id = ".$params['linkedcontactID'];
	$dao = CRM_Core_DAO::executeQuery( $sql);
	
	if ($dao->fetch()){
	 	$values[$cnt]['phone'] = $dao->phone;
		
	}
	
	
	$sql = "SELECT * FROM civicrm_email WHERE is_primary = 1 AND contact_id = ".$params['linkedcontactID'];
	$dao = CRM_Core_DAO::executeQuery( $sql);
	
	if ($dao->fetch()){
	    $values[$cnt]['email'] = $dao->email;
	}
	
			$returnvalue = array(
						'is_error' => 0,
						'version' => 3,
						'count' => $cnt,
						'values' => $values,
						);
			
	
	createlog($returnvalue,$params['user'],$params['linkedcontactID'],'Createconnection');
	return $returnvalue;
	
}

