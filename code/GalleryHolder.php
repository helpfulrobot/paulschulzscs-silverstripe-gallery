<?php
class GalleryHolder extends Page {

  private static $singular_name = 'Galerieübersicht';
  private static $description = 'Zeigt alle untergeordneten Galerien an.';
  private static $can_be_root = true;
  private static $allowed_children = ['GalleryPage'];
  private static $default_child = 'GalleryPage';
}

class GalleryHolder_Controller extends Page_Controller {

  private static $allowed_actions = [];

  public function init() {
    parent::init();
  }
}