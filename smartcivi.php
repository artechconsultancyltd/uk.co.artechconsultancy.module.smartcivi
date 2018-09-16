<?php

require_once 'smartcivi.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function smartcivi_civicrm_config(&$config) {
  _smartcivi_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function smartcivi_civicrm_xmlMenu(&$files) {
  _smartcivi_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function smartcivi_civicrm_install() {
	
	$sql = "CREATE TABLE IF NOT EXISTS `civicrm_value_smartcivi_connection` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`baseurl` varchar(255) NOT NULL,
			`apikey` varchar(255) NOT NULL,
			`sitekey` varchar(255) NOT NULL,
			`user` varchar(255) NOT NULL,
			`contact_id` int(10) NOT NULL,
			`userrole_id` int(10) DEFAULT 1 COMMENT 'FK civicrm_value_smartcivi_connection_userrole',
			`date_created` datetime DEFAULT NOW(),
			PRIMARY KEY (`id`)
			)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	
	CRM_Core_DAO::executeQuery($sql);
		
  _smartcivi_civix_civicrm_install();
}

/*
*
*Function to create logs
*
*/
function createlog($params,$user,$linked_contact,$entity){
	
	
	if (is_array ($params)){
		//reduce the text size to 100 for limiting the storeage 
		foreach ($params as $key => $values){
			$log_params[$key] = substr($values, 0, 100); 
		}	
	} else {
		$log_params = $params;
	}
	

	$sql = "INSERT INTO civicrm_value_smartcivi_log (user,linked_contact,entity,details)";
	$sql = $sql . " VALUES(";
	$sql = $sql . "'". $user ."',";
	$sql = $sql . "'". $linked_contact ."',";
	$sql = $sql . "'". $entity ."',";
	$sql = $sql . "'". serialize($log_params) ."')";
	
	CRM_Core_DAO::executeQuery($sql);
}



function smartcivi_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions)
{

  $permissions['smartcivi']['getcontact'] = array('access AJAX API');
  $permissions['smartcivi']['createconnection'] = array('access AJAX API');
  
}


/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function smartcivi_civicrm_uninstall() {
	
	smartcivi_civicrm_backup_drop_table('civicrm_value_smartcivi_connection');
	smartcivi_civicrm_backup_drop_table('civicrm_value_smartcivi_log');
		
  _smartcivi_civix_civicrm_uninstall();
}


/**
*
*Table a backup of the table and drop the table from database
*
*/

function smartcivi_civicrm_backup_drop_table($tablename){
	
	$today = date('YmdHis');
	$tablenamecopy = $tablename.'_copy'.$today;
	
	//drop the related table from DB
	$sql = "SHOW TABLES LIKE '{$tablename}'";
	$dao_tableExists = CRM_Core_DAO::executeQuery( $sql );
	
	If ($dao_tableExists->fetch()){
		
		$sql = "DROP TABLE IF EXISTS {$tablenamecopy}";
		CRM_Core_DAO::executeQuery( $sql );
		
		$sql = "create table {$tablenamecopy} select * from {$tablename}";
		CRM_Core_DAO::executeQuery( $sql );
		
		//drop the related table from DB
		$sql = "DROP Table IF EXISTS {$tablename}";
		CRM_Core_DAO::executeQuery( $sql );
		
	}
	
	return;
}


/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function smartcivi_civicrm_enable() {
  _smartcivi_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function smartcivi_civicrm_disable() {
  _smartcivi_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function smartcivi_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _smartcivi_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function smartcivi_civicrm_managed(&$entities) {
  _smartcivi_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function smartcivi_civicrm_caseTypes(&$caseTypes) {
  _smartcivi_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function smartcivi_civicrm_angularModules(&$angularModules) {
_smartcivi_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function smartcivi_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _smartcivi_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function smartcivi_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function smartcivi_civicrm_navigationMenu(&$menu) {
  _smartcivi_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'uk.co.nfpservices.module.smartcivi')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _smartcivi_civix_navigationMenu($menu);
} // */
