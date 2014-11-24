<?php

namespace STC;

/**
 * @author Bruno Dias <dias.h.bruno@gmail.com>
 * @license MIT License (see LICENSE)
 */
class PageComponent
{
  /**
   * The type.
   * @var string
   */
  private $type;

  /**
   * @constructor
   */
  public function __construct()
  {
    $this->type = 'page';
  }

  /**
   * Filter a file by it type - page.
   * @param $file array | Json file as array.
   * @return bool
   */
  public function filter_by_type($file)
  {
    return $file['type'] == $this->type;
  }

  /**
   * Build.
   * @return void
   */
  public function build($files)
  {
    $pages = array();
    $files = $files->filter_by(array(&$this, 'filter_by_type'));

    foreach($files as $file) {
      $pages[] = $file;
    }

    Config::db()->store('page_list', $pages);
  }
}
