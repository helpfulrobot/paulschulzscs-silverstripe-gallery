<?php
class GalleryPage extends Page {

    private static $singular_name = 'Galerie';
    private static $description = 'Eine Bildergalerie';
    private static $can_be_root = false;
    private static $allowed_children = [];
    private static $default_child = '';

    private static $db = [
        'Date' => 'Date',
        'Location' => 'Varchar(255)',
    ];

    public function getCMSFields() {
      $fields = parent::getCMSFields();
      $fields->addFieldsToTab('Root.Main', [
          DateField::create('Date', 'Datum'),
          TextField::create('Location', 'Ort')
      ], 'Content');

      return $fields;
    }
}

class GalleryPage_Controller extends Page_Controller {

    private static $allowed_actions = [];

    public function init() {
        parent::init();
    }
}