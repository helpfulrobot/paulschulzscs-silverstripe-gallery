<?php

class Gallery_UploadController extends LeftAndMain {
        
    private static $menu_title = false;
    private static $url_segment = 'gallery/upload';
    private static $url_priority = 60;
    private static $required_permission_codes = 'CMS_ACCESS';
    
    /*private static $allowed_actions = array (
            'upload'
    );*/
    
    public function currentPage() {
        $id = $this->currentPageID();
        if($id && is_numeric($id) && $id > 0) {
            $folder = DataObject::get_by_id('Folder', $id);
            if($folder && $folder->exists()) {
                return $folder;
            }
        }
        return new Folder();
    }

    public function currentPageID() {
        if(is_numeric($this->request->requestVar('ID')))        {
            return $this->request->requestVar('ID');
        } elseif (is_numeric($this->urlParams['ID'])) {
            return $this->urlParams['ID'];
        } 
        return 0;               
    }
    
    public function GalleryID() {
        if(is_numeric($this->request->requestVar('GalleryID'))) {
            return $this->request->requestVar('GalleryID');
        }         
        if($this->currentPageID) {        
            $gallery = Gallery::get()->filter('ImageFolderID', $this->currentPageID)->First();        
            return $gallery->ID;
        }
        return 0;          
    }
    
    
    public function getEditForm($id = null, $fields = null) {
    
        Requirements::javascript(FRAMEWORK_DIR . '/javascript/AssetUploadField.js');
        Requirements::css(FRAMEWORK_DIR . '/css/AssetUploadField.css');

        $folder = $this->currentPage();
        $folder->GalleryID = $this->GalleryID();

        $uploadField = UploadField::create('AssetUploadField', '');
        $uploadField->setConfig('previewMaxWidth', 40);
        $uploadField->setConfig('previewMaxHeight', 30);
        $uploadField->setConfig('changeDetection', false);
        $uploadField->addExtraClass('ss-assetuploadfield');
        $uploadField->removeExtraClass('ss-uploadfield');
        $uploadField->setTemplate('AssetUploadField');

        $path = preg_replace('/^' . ASSETS_DIR . '\//', '', $folder->getFilename());
        $uploadField->setFolderName($path);             

        $exts = $uploadField->getValidator()->getAllowedExtensions();
        asort($exts);
        
        $uploadField->Extensions = implode(', ', $exts);

        $form = CMSForm::create( $this, 'EditForm', new FieldList($uploadField, new HiddenField('ID'), new HiddenField('GalleryID')), new FieldList())
            ->setHTMLID('Form_EditForm');
        
        $form->setResponseNegotiator($this->getResponseNegotiator());
        $form->addExtraClass('center cms-edit-form ' . $this->BaseCSSClasses());
        
        // Don't use AssetAdmin_EditForm, as it assumes a different panel structure
        $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
        $form->Fields()->push(new LiteralField('BackLink',
            sprintf('<a href="%s" class="backlink ss-ui-button cms-panel-link" data-icon="back">%s</a>',
                'admin/pages/edit/show/'.$this->GalleryID(),
                'Zurück zur Gallerie'
        )));
        $form->loadDataFrom($folder);

        return $form;
    }	
    
    public function init()
    {
	    parent::init();
	    
	    CMSMenu::remove_menu_item('Gallery_UploadController');
	    $items = $this->MainMenu();
	    
	    $pagesLink = $items->find('Link', 'admin/pages/');
	    $pagesLink->LinkingMode = 'current';
    }
    
}