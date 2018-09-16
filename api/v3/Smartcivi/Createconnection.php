<?php

/**
 * smartcivi.Createconnection API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_smartcivi_Createconnection_spec(&$spec) {
  
  $spec['baseurl']['api.required'] = 1;
  $spec['apikey']['api.required'] = 1;
  $spec['sitekey']['api.required'] = 1;
	
}

/**
 * smartcivi.Createconnection API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_smartcivi_Createconnection($params) {
	
	createlog($params,$params['user'],$params['linkedcontactID'],'Createconnection');
	
	$url = $params['baseurl'];
	$url = str_replace("\\","/",$url);
	$url_code = ord(substr($url,-1));
	
	if ($url_code <> 47){
		$url = $url . "/";
	}
	
	//add slash to end of the url 
	if ($params){	
		
		//we need only one 
		$sql = "DELETE FROM civicrm_value_smartcivi_connection where user = '".$params['user']."'";
		CRM_Core_DAO::executeQuery($sql);
		
		//get api_key linked contact_id
		$sql1 = "SELECT * FROM civicrm_contact WHERE api_key = '".$params['apikey']."'"; 	
		
		$dao1 = CRM_Core_DAO::executeQuery($sql1);	
		if ($dao1->fetch()){
			$contact_id = $dao1->id;
			$name = $dao1->display_name;
		}
		
		$userrole_id = 2;
		
		//CRM_Utils_Crypt::encrypt($formValues['smtpPassword']);
		$sql = "REPLACE INTO civicrm_value_smartcivi_connection ";
		$sql = $sql . " (baseurl,apikey,sitekey,user,contact_id,userrole_id,date_created)";
		$sql = $sql . " VALUES(";
		$sql = $sql . "'".$url."'";
		$sql = $sql . ",'".$params['apikey']."'";
		$sql = $sql . ",'".$params['sitekey']."'";
		$sql = $sql . ",'".$params['user']."'";
		$sql = $sql . ",".$contact_id;
		$sql = $sql . ",".$userrole_id;
		$sql = $sql . ",now())";
		
		$dao = CRM_Core_DAO::executeQuery($sql);
		
		if ($contact_id){
			
			$values[1] = array("contact_id" => $contact_id,
								"name" => $name,
								"organization" => "ARTECH Consultancy Ltd",
								"org_email" => "info@artechconsultancy.co.uk");
			
			$returnvalue = array(
						'is_error' => 0,
						'version' => 3,
						'count' => 1,
						'values' => $values,
						);
			
			createlog($returnvalue,$params['user'],$params['linkedcontactID'],'Createconnection');
			return $returnvalue;
		} 
		
		createlog($dao,$params['user'],$params['linkedcontactID'],'Createconnection');
		
		//Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
		return civicrm_api3_create_success($dao, $params, 'NewEntity', 'NewAction');
  } else {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
  }
	
	
}
