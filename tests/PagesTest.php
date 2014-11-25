<?php

namespace STC\Test;

use STC\Application;
use STC\Files;
use STC\PageDatabase;
use STC\PageWriter;

class PagesTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    Application::bootstrap(dirname(__FILE__), 'data');
  }

  public function testUnits()
  {
    $this->assertTrue(new PageDatabase() != null);
    $this->assertTrue(new PageWriter() != null);
  }

  public function testBuildComponent()
  {
    $files = new Files();
    $files->load(dirname(__FILE__).'/data', 'page-data');

    $component = new PageDatabase();
    $component->execute($files);

    $this->assertTrue(count(Application::db()->retrieve('page_list')) > 0);
  }
}
