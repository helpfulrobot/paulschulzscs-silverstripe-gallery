<?php

class Gallery extends Page {

	public static $db = array(
	);

	public static $has_one = array(
		'defaultIndexImage' => 'Image',
		'ImageFolder' => 'Folder'
	);
	
	public static $prettyPhotoConfig = array();
	
	public static $defaultparent = 'bilder';
	
	public static $allowed_children = array();
		
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		if($this->ImageFolderID) {
			$fields->addFieldToTab('Root.Main', new LiteralField('Bilder hochladen', '<a href="admin/assets/show/'.$this->ImageFolderID.'">Zum Bilder-Ordner</a>'), 'Content');
		} 
		
		$fields->renameField('Title', 'Gallerie-Titel');
		
		$fields->addFieldToTab('Root.IndexBild', new UploadField('defaultIndexImage', 'Bild'));
		
		return $fields;
	}
	
	public function onBeforeWrite()
	{	
	  parent::onBeforeWrite();
	  		
		if(!$this->ImageFolderID && substr($this->URLSegment, 0, 4) != 'new-' && substr($this->URLSegment, 0, 5) != 'neue-') {
			$folder = Folder::find_or_make($this->URLSegment);
			$this->ImageFolderID = $folder->ID;
		}
		
	}
		
	public function getImages()
	{
		return Image::get()->filter('ParentID', $this->ImageFolderID);
	}

	public function getIndexImage()
	{
		if($this->defaultIndexImageID) {
			return $this->defaultIndexImage();
		}
		return Image::get()->filter('ParentID', $this->ImageFolderID)->First();
	}
		
	public function getRandomImage()
	{
		return Image::get()->filter('ParentID', $this->ImageFolderID)->sort('RAND()')->First();
	}
}

class Gallery_Controller extends Page_Controller {

	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();
		
		Requirements::javascript('prettyphoto/js/jquery-1.6.1.min.js');
		Requirements::javascript('prettyphoto/js/jjs/jquery.prettyPhoto.js');
		Requirements::css('prettyphoto/css/prettyPhoto.css');
		Requirements::css('gallery/css/gallery.css');
		
		$prettyPhotoConfigJson = json_encode($prettyPhotoConfig);
	
	    Requirements::customScript(<<<SCRIPT
	            $(document).ready(function(){
	                    $("a[rel^='gallery']").prettyPhoto($prettyPhotoConfigJson);
	            });
SCRIPT
	    );
		
	}

}