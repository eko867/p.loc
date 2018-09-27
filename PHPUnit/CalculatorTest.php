<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.18
 * Time: 13:14
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../vendor/autoload.php';
require __DIR__.'/Calculator.php'; //тестируемый класс

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp()//вызывать перед КАЖДЫМ методом теста
    {
        $this->calculator = new Calculator();
    }

    protected function tearDown() //вызывать после КАжДОГО теста
    {
        $this->calculator = NULL;
    }

    /*
    public function testAdd() //метод для теста add()
    {
        $result = $this->calculator->add(1, 2);
        $this->assertEquals(3, $result); //проверка правильное значение (3) вернул тестируемый метод

    }
    */

    public function addDataProvider()
    {
        return [
          [1,2,3],
          [0,0,0],
          [-1,-1,-2]
        ];
    }

    //аннотация, какую функцию использовать в качестве датаПровайдера для этого теста
    /**
     * @dataProvider addDataProvider
     */
    public function testAdd($a, $b, $expected)
    {
        $result = $this->calculator->add($a, $b);
        $this->assertEquals($expected, $result);
    }
}