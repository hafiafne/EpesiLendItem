<?php
 
defined("_VALID_ACCESS") || die('Direct access forbidden'); // - security feature
 
class Custom_LendItem extends Module { // - notice how the class name represents its path
	private $rb;
	
	// display module render
	public function body() { // - modules main code
	
		$me = CRM_ContactsCommon::get_my_record();
		$this->rb = $this->init_module('Utils/RecordBrowser', 'custom_lenditem', 'custom_lenditem');
		$this->rb->set_defaults(array('limit_date'=>date("Y-m-d"), 'status'=>0, 'priority'=>1, 'employees'=>array($me['id'])));
		$this->rb->set_default_order(array('limit_date'=>'DESC'));
		$this->display_module($this->rb);
	}
	
	// set window title
	public function caption() {
        if(isset($this->rb)) return $this->rb->caption();
    }
}
 
?>