<?php
/**
 * Author: cistartup
 * Date: 1/23/12
 * Time: 11:01 PM
 * File: WelcomeControllerTest.php
 */

class WelcomeControllerTests extends CIUnit_TestCase
{
    public function setUp()
    {
        // Set the tested controller
        $this->CI->loader->_load_controller('welcome/welcome');
    }

    public function testRepeat()
    {
        $yell = "Hello, any one out there?";
        $returned  = $this->CI->repeat($yell);
        $this->assertEquals($returned, $yell);
    }
}