<?php

namespace STC;

use Cocur\Slugify\Slugify;

/**
 * @author Bruno Dias <dias.h.bruno@gmail.com>
 * @license MIT License (see LICENSE)
 */
class PageWriter
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
   * Check if it has 'index' in the filename.
   * @param $file string | The filename.
   * @return bool
   */
  private function is_index($filename = '')
  {
    $pattern = '/(^index).+$/';
    return preg_match($pattern, $filename);
  }

  /**
   * Make the page slug.
   * @param $file array | Raw file data.
   * @param $tmpl array | Reference to the new file data.
   * @return void
   */
  private function make_slug($file, &$tmpl)
  {
    return $this->is_index($file['file']) ?
      '' :
      $this->slugify->slugify($file['title']);
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

    $t = Application::templates()->template($file['template']);

    $tmpl = $file;
    $tmpl['slug'] = $this->make_slug($file, $tmpl);

    $data_folder = Application::data_folder();
    $template_name = $data_folder . '/templates/' . $t;
    $content_template = $data_folder . '/' . $file['content'];

    $render_content_with = Application::renders()->select($content_template);
    $render_with = Application::renders()->select($template_name);

    $tmpl['html'] = $render_with->render($template_name, [
      'content' => $render_content_with->render($content_template, [
        'post'=> $file,
      ]),
      'post'=> $file,
    ]);

    if ($this->is_index($file['file'])) {
      $this->log_current_page('index', '');
    } else {
      $this->log_current_page($file['title'], $tmpl['slug']);
    }

    return $tmpl;
  }

  /**
   * Render function.
   * @param $files array | A list of all available entries.
   * @return void
   */
  public function execute($files)
  {
    printLn('=> PageWriter.');
    $pages = Application::db()->retrieve('page_list');

    $writer = new DataWriter();

    foreach($pages as $page) {
      $tmpl = $this->make_data($page);
      $writer->write($tmpl['slug'], 'index.html', $tmpl['html']);
    }
  }

  /**
   * Log current page.
   * @param $title string | The title of the page.
   * @param $slug string | The slug.
   * @return void
   */
  private function log_current_page($title, $slug)
  {
    printLn('==> Current page: ' . $title . ': /' . $slug);
  }
}
