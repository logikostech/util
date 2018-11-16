<?php


namespace LogikosTest\Util\Config;

use Logikos\Util\Config\ValidationResult as ConfigValidationResult;
use Logikos\Util\Validation\InvalidResult;
use Logikos\Util\Validation\ValidResult;

class ValidationResultTest extends TestCase {
  public function testCanAddFieldResult() {
    $cvr = new ConfigValidationResult;
    $fieldResult = new ValidResult();
    $cvr->addResult('age', $fieldResult);
    $this->assertSame($fieldResult, $cvr->result('age'));
  }

  public function testCanGetAllResultMessages() {
    $cvr = new ConfigValidationResult;
    $cvr->addResult('f1', new InvalidResult([
        'a',
        'b'
    ]));
    $cvr->addResult('f2', new ValidResult);
    $cvr->addResult('f3', new InvalidResult([
        'x',
        'y',
        'z'
    ]));
    $expected = [
        'f1' => ['a','b'],
        'f3' => ['x','y','z']
    ];
    $this->assertEquals(
        $expected,
        $cvr->getMessages()
    );
  }

  public function test_IsValidWithNoResults() {
    $cvr = new ConfigValidationResult();
    $this->assertTrue($cvr->isValid());
  }

  public function test_IsNotValidIfAnyResultIsNotValid() {
    $cvr = new ConfigValidationResult();
    $cvr->addResult('f1', new ValidResult());
    $cvr->addResult('F2', new InvalidResult([]));
    $this->assertFalse($cvr->isValid());
  }

}