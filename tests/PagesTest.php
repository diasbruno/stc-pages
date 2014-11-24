<?php

namespace STC\Test;

use STC\Config;
use STC\Files;
use STC\PageComponent;
use STC\PageRender;

class PagesTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    Config::bootstrap(dirname(__FILE__), 'data');
  }

  public function testUnits()
  {
    $this->assertTrue(new PageComponent() != null);
    $this->assertTrue(new PageRender() != null);
  }

  public function testBuildComponent()
  {
    $files = new Files();
    $files->load(dirname(__FILE__).'/data', 'page-data');

    $component = new PageComponent();
    $component->build($files);

    $this->assertTrue(count(Config::db()->retrieve('page_list')) > 0);
  }
}
