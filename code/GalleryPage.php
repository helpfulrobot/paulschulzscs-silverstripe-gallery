<?php
class GalleryPage extends Page {

    private static $singular_name = 'Galerie';
    private static $description = 'Eine Bildergalerie';
    private static $can_be_root = false;
    private static $allowed_children = [];
    private static $default_child = '';

    private static $db = [
        'Date' => 'Date',
        'Location' => 'Text',
    ];

    private static $many_many = [
        'Images' => 'Image',
    ];

    private static $many_many_extraFields = [
        'Images' => ['SortOrder' => 'Int'],
    ];

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            DateField::create('Date', 'Datum'),
            TextareaField::create('Location', 'Ort'),
        ], 'Content');

        $fields->insertAfter(Tab::create('Images', 'Bilder'), 'Main');
        $fields->addFieldsToTab('Root.Images', [
            SortableUploadField::create('Images', 'Bilder')
                ->setFolderName('galleries/' . $this->ID)
                ->setDisplayFolderName('galleries/' . $this->ID)
        ]);

        return $fields;
    }
}

class GalleryPage_Controller extends Page_Controller {

    private static $allowed_actions = [];

    public function init() {
        parent::init();
    }
}