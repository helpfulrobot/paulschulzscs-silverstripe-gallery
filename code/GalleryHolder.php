<?php

class GalleryHolder extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);
	
	public static $allowed_children = array('Gallery');
		
	public function getCMSFields() {
		$fields = parent::getCMSFields();
				
		return $fields;
	}


}

class GalleryHolder_Controller extends Page_Controller {

	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();
		
		Requirements::themedCSS('gallery');
	}

}