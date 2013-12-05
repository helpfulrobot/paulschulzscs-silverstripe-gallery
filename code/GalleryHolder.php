<?php

class GalleryHolder extends Page {

	private static $db = array(
	);

	private static $has_one = array(
	);
	
	private static $allowed_children = array('Gallery');        
		
	public function getCMSFields() {
		$fields = parent::getCMSFields();
				
		return $fields;
	}
    
    public function Galleries()
    {
        $galleries = Gallery::get()->filter('ParentID', $this->ID);
        
        if(Config::inst()->get('GalleryHolder', 'HideEmptyGalleries')) {
            foreach($galleries as $g) {
	            if($g->numOfImages() == 0) {
	            	$galleries->remove($g);
	            }
            }
        }                    
        return $galleries;
    }
    
    public function Children()
    {
        if(Config::inst()->get('GalleryHolder', 'ShowGalleriesInSubmenu')) {
            return $this->Galleries();
        }
        return false;
    }

}

class GalleryHolder_Controller extends Page_Controller {

	private static $allowed_actions = array (
	);

	public function init() {
		parent::init();
		
		Requirements::themedCSS('gallery', 'gallery');
	}

}