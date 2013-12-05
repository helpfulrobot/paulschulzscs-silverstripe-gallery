<?php

class Gallery extends Page {

	private static $db = array(
	);

	private static $has_one = array(
		'defaultIndexImage' => 'Image',
		'ImageFolder' => 'Folder'
	);
    
	private static $allowed_children = array();    
	
    
	public static $prettyPhotoConfig = array();	
	public static $defaultparent = 'bilder';	
		
    public function getCMSFields() 
    {
        $fields = parent::getCMSFields();
                        
        if($this->ImageFolderID) {
                
            $gridFieldConfig = GridFieldConfig::create()->addComponents(
                    new GridFieldToolbarHeader(),
                    new GridFieldSortableHeader(),
                    new GridFieldFilterHeader(),                    
                    new GridFieldDataColumns(),
                    new GridFieldPaginator(15),
                    new GridFieldEditButton(),
                    new GridFieldDeleteAction(),
                    new GridFieldDetailForm()
            );
        
            $gridField = GridField::create('File', "Bilder", File::get()->where('ParentID='.$this->ImageFolderID), $gridFieldConfig);
            $columns = $gridField->getConfig()->getComponentByType('GridFieldDataColumns');
            $columns->setDisplayFields(array(
                    'StripThumbnail' => '',
                    // 'Parent.FileName' => 'Folder',
                    'Title' => _t('File.Name'),
                    'Created' => _t('AssetAdmin.CREATED', 'Date'),
                    'Size' => _t('AssetAdmin.SIZE', 'Size'),
            ));
            $columns->setFieldCasting(array(
                    'Created' => 'Date->Nice'
            ));
            $gridField->setAttribute(
                    'data-url-folder-template', 
                    Controller::join_links($this->Link('show'), '%s')
            );
        
            $uploadBtn = new LiteralField(
                    'UploadButton', 
                    sprintf(
                            '<a class="ss-ui-button ss-ui-action-constructive cms-panel-link" data-pjax-target="Content" data-icon="drive-upload" href="%s">%s</a>',
                            Controller::join_links(singleton('Gallery_UploadController')->Link(), '?ID=' . $this->ImageFolderID.'&GalleryID='.$this->ID),
                            _t('Folder.UploadFilesButton', 'Upload')
                    )
            );      
    
            $actionButtonsComposite = CompositeField::create()->addExtraClass('cms-actions-row');
            $actionButtonsComposite->push($uploadBtn);

            // List view
            $fields->addFieldsToTab('Root.Bilder', array(
                    $actionsComposite = CompositeField::create(
                            $actionButtonsComposite
                    )->addExtraClass('cms-content-toolbar field'),
                    $gridField
            ));
        } 
                
        $fields->renameField('Title', 'Gallerie-Titel');        
        $fields->addFieldToTab('Root.Main', new UploadField('defaultIndexImage', 'Index-Bild'), 'Content');
                
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
	
	public function getThumbnails()
	{
		$images = $this->getImages();		
		$thumbnails = new ArrayList();
		
		foreach($images as $image) {
			$thumbnails->push(new ArrayData(array(
				'Thumbnail' => call_user_func_array(array(
			            $image, Config::inst()->get('Gallery', 'ImageResizeFunction')), 
			            Config::inst()->get('Gallery', 'ImageResizeAttributes')
			        ),
			    'Image' => $image			     
			)));
		}
		return $thumbnails;
	}

	public function getIndexImage()
	{
		if($this->defaultIndexImageID) {
			$image =  $this->defaultIndexImage();
		}
		else {
            $image = Image::get()->filter('ParentID', $this->ImageFolderID)->First();
        }
        
        $function = Config::inst()->get('GalleryHolder', 'ImageResizeFunction');
                        
        return call_user_func_array(array(
            $image, Config::inst()->get('GalleryHolder', 'ImageResizeFunction')), 
            Config::inst()->get('GalleryHolder', 'ImageResizeAttributes')
        );
	}
		
	public function getRandomImage()
	{
		return Image::get()->filter('ParentID', $this->ImageFolderID)->sort('RAND()')->First();
	}
    
    public function numOfImages()
    {
        return $this->getImages()->Count();
    }
}

class Gallery_Controller extends Page_Controller {

	private static $allowed_actions = array (
	);

	public function init() {
		parent::init();
		
		Requirements::javascript('framework/thirdparty/jquery/jquery.min.js');
		Requirements::javascript('prettyphoto/js/jquery.prettyPhoto.js');
		Requirements::css('prettyphoto/css/prettyPhoto.css');
        
		Requirements::themedCSS('gallery', 'gallery');
		
		$prettyPhotoConfigJson = json_encode(Gallery::$prettyPhotoConfig);
	
	    Requirements::customScript(<<<SCRIPT
	            $(document).ready(function(){
	                    $("a[rel^='gallery']").prettyPhoto($prettyPhotoConfigJson);
	            });
SCRIPT
	    );
		
	}

}