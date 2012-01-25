<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
 
class WebTest extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://local.cistartup.com');
    }


    public function testHasCodeIgniter()
    {
        $this->open('http://local.cistartup.com');
        $this->assertElementContainsText('body', 'CodeIgniter');
    }

}
?>