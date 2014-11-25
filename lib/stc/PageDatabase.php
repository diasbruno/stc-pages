<?php

namespace STC;

/**
 * @author Bruno Dias <dias.h.bruno@gmail.com>
 * @license MIT License (see LICENSE)
 */
class PageDatabase
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
   * Make the database from the loaded content.
   * @param $files Files | The Files component.
   * @return void
   */
  public function execute($files)
  {
    $files = $files->filter_by(array(&$this, 'filter_by_type'));

    Application::db()->store('page_list', $files);
  }
}
