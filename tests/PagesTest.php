<?php

namespace STC\Test;

use STC\Application;
use STC\Files;
use STC\PageDatabase;
use STC\PageWriter;

class PagesTest extends \PHPUnit_Framework_TestCase
{
  private $files;

  public function setup()
  {
    Application::bootstrap(dirname(__FILE__), 'data');

    $this->files = new Files();
    $this->files->load(dirname(__FILE__).'/data', 'page-data');
  }

  public function testUnits()
  {
    $this->assertTrue(new PageDatabase() != null);
    $this->assertTrue(new PageWriter() != null);
  }

  public function testBuildComponent()
  {
    $component = new PageDatabase();
    $component->execute($this->files);

    $this->assertTrue(count(Application::db()->retrieve('page_list')) > 0);
  }

  public function testWriter()
  {
    $writer = new PageWriter();
    $writer->execute($this->files);

    $this->assertTrue(file_exists('tests/web/index.html'));
    $this->assertTrue(file_exists('tests/web/test/index.html'));
  }
}
