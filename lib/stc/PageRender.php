<?php

namespace STC;

use Cocur\Slugify\Slugify;

/**
 * @author Bruno Dias <dias.h.bruno@gmail.com>
 * @license MIT License (see LICENSE)
 */
class PageRender
{
  private $slugify;

  /**
   * @constructor
   */
  public function __construct()
  {
    $this->slugify = new Slugify();
  }

  /**
   * Make the page slug.
   * @param $file array | Raw file data.
   * @param $tmpl array | Reference to the new file data.
   * @return void
   */
  private function make_slug($file, &$tmpl)
  {
    $tmpl['slug'] = array_key_exists('is_index', $file) ? 
      '' : $this->slugify->slugify($file['title']);
  }

  /**
   * Format a file to be rendered.
   * @param $template Template | A Template.
   * @param $file array | Json file as array.
   * @return array
   */
  private function make_data($file)
  {
    if (!array_key_exists('template', $file)) {
      throw new Exception('x> Current page: ' . $file['title'] . ' does not have a template.');
    }

    $t = Config::templates()->template($file['template']);

    $tmpl = $file;
    $tmpl['slug'] = $this->make_slug($file, $tmpl);

    $data_folder = Config::data_folder();
    $template_name = $data_folder . '/templates/' . $t;
    $content_template = $data_folder . '/' . $file['content'];

    $tmpl['html'] = view($template_name, [
      'content' => view($content_template),
      'post'=> $file,
    ]);

    printLn('==> Current page: ' . $file['title'] . ': ' . $tmpl['slug']);

    return $tmpl;
  }

  /**
   * Render function.
   * @param $files array | A list of all available entries.
   * @return void
   */
  public function render($files)
  {
    printLn('=> PageRender.');
    $pages = Config::db()->retrieve('page_list');

    $t = Config::templates()->templates_path() . '/';

    $writer = new DataWriter();

    foreach($pages as $page) {
      $tmpl = $this->make_data($page);
      $writer->write($tmpl['slug'], 'index.html', $tmpl['html']);
    }
  }
}
