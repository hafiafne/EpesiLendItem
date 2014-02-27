<?php
defined("_VALID_ACCESS") || die();
 
class Custom_LendItemInstall extends ModuleInstall {

	public function install() {

		Utils_CommonDataCommon::new_array('custom/lenditem/status', array( '0'=>_M('Items Lent'), '1'=>_M('Returned Partially'), '2'=>_M('Returned Completely'), '3'=>_M('Items Sold') ), true, true); //This created the Common Data for the Status Field
		Utils_CommonDataCommon::new_array('custom/lenditem/priority', array( '0'=>_M('Low'), '1'=>_M('Medium'), '2'=>_M('High')), true, true); //This created the Common Data for the Priority Field
		
		
		Base_ThemeCommon::install_default_theme('Custom/LendItem');
				
		Utils_RecordBrowserCommon::install_new_recordset('custom_lenditem', array(
				array(	
						'name' => _M('Title'),
						'type'=>'text', 
						'required'=>true, 
						'param'=>'255', 
						'extra'=>false, 
						'visible'=>true, 
						'display_callback'=>array('Custom_LendItemCommon','display_title')
				 ),
				array(	
						'name' => _M('Customer'),				//Name of the Field
						'type' => 'crm_company_contact', //Type of Field
						'visible' => true,			//shows on the Table View
						'filter' => false,			//Can you Filter it?
						'param'=>array('field_type'=>'select'),
						'required' => true
				 ),
				 array(	
						'name' => _M('Items'),				//Name of the Field
						'type' => 'long text',			//Type of Field
						'visible' => false,			//shows on the Table View
						'filter' => false,			//Can you Filter it?
						'required' => true
				 ),
				 array(	
						'name' => _M('Status'),				//Name of the Field
						'type' => 'commondata',			//Type of Field
						'visible' => true,			//shows on the Table View
						'filter' => false,			//Can you Filter it?
						'param' => array('order_by_key'=>true, 'custom/lenditem/status'),
						'required' => true
				 ),
				 array(	
						'name' => _M('Priority'),				//Name of the Field
						'type' => 'commondata',			//Type of Field
						'visible' => true,			//shows on the Table View
						'filter' => false,			//Can you Filter it?
						'param' => array('order_by_key'=>true, 'custom/lenditem/priority')
				 ),
				 array(	
						'name' => _M('Limit Date'),				//Name of the Field
						'type' => 'date',			//Type of Field
						'visible' => true,			//shows on the Table View
						'filter' => true,			//Can you Filter it?
						'required' => true
				 ),
				 array(
						'name' => _M('Employees'),
						'type'=>'crm_contact',
						'param'=>array(
										'field_type'=>'multiselect',
										'crits'=>array('Custom_LendItemCommon','employees_crits'), 
										'format'=>array('CRM_ContactsCommon','contact_format_no_company')
									), 
						'display_callback'=>array('Custom_LendItemCommon','display_employees'), 
						'required'=>true, 
						'extra'=>false, 
						'visible'=>false, 
						'filter'=>false
				 )
			
			)
		);
		
		Utils_RecordBrowserCommon::set_caption('custom_lenditem', _M('Lend Item'));  //Creates the Name of the module for users to see
		Utils_RecordBrowserCommon::set_icon('custom_lenditem', Base_ThemeCommon::get_template_filename('Custom/LendItem', 'icon.png'));
		Utils_RecordBrowserCommon::register_processing_callback('custom_lenditem', 'Custom_LendItemCommon::process_request'); // method called on insert to generate alert
		Utils_RecordBrowserCommon::enable_watchdog('custom_lenditem', array('Custom_LendItemCommon','watchdog_label')); //Watchdog for those Managers to track changes
		
		Utils_RecordBrowserCommon::add_access('custom_lenditem', 'view',   'ACCESS:employee', array('(!permission'=>2, '|employees'=>'USER'));
		Utils_RecordBrowserCommon::add_access('custom_lenditem', 'add',    'ACCESS:employee');
		Utils_RecordBrowserCommon::add_access('custom_lenditem', 'edit',   'ACCESS:employee', array('(permission'=>0, '|employees'=>'USER', '|customers'=>'USER'));
		Utils_RecordBrowserCommon::add_access('custom_lenditem', 'delete', 'ACCESS:employee', array(':Created_by'=>'USER_ID'));
		Utils_RecordBrowserCommon::add_access('custom_lenditem', 'delete',  array('ACCESS:employee','ACCESS:manager'));

		
		/* Add-Ons */
		Utils_AttachmentCommon::new_addon('custom_lenditem');
		//Utils_RecordBrowserCommon::new_addon('custom_lenditem', 'Custom/LendItem', 'messanger_addon', _M('Alerts'));
		CRM_CalendarCommon::new_event_handler(_M('Lend Item'), array('Custom_LendItemCommon', 'crm_calendar_handler'));
		
		return true; //False on failure
		
	}
 
	public function uninstall() {
	
		CRM_CalendarCommon::delete_event_handler(_M('Lend Item'));
		//Utils_RecordBrowserCommon::delete_addon('custom_lenditem', 'Custom/LendItem', 'messanger_addon');
		Utils_AttachmentCommon::delete_addon('custom_lenditem');
		
		Utils_RecordBrowserCommon::unregister_processing_callback('custom_lenditem', 'Custom_LendItemCommon::process_request');
		Utils_RecordBrowserCommon::uninstall_recordset('custom_lenditem'); //remove DB and Module
		
		return true;
	}
 
	public function info() {
		return array('Author'=>'<a href="mailto:zumiani@marcomweb.it">Alberto Zumiani @ Marcom S.r.l.</a>',
					'License'=>'GNU GPL v3.0',
					'Description'=>'Module for Lent Items management');
	}
 
	public function simple_setup() {
		return array('package' => _M('Lend Item'), 'version'=>'1.0');
	}
 
	public function requires($v) {
		return array(
			array('name'=>'Utils/RecordBrowser', 'version'=>0),
			array('name'=>'Utils/Attachment', 'version'=>0),
			array('name'=>'CRM/Common', 'version'=>0),
			array('name'=>'CRM/Contacts', 'version'=>0),
			array('name'=>'CRM/Calendar', 'version'=>0),
			array('name'=>'Base/Lang', 'version'=>0),
			array('name'=>'Base/Acl', 'version'=>0),
			array('name'=>'CRM/Filters','version'=>0),
			array('name'=>'Libs/QuickForm','version'=>0),
			array('name'=>'Base/Theme','version'=>0)
		);
	}
 
	public function version() {
		return array('1.0');
	}
}

?>
