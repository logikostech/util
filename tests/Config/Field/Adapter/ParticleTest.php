<?php

namespace LogikosTest\Util\Config\Field\Adapter;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\Adapter\Particle;
use LogikosTest\Util\Config\Field\TestCase;
use Particle\Validator as ParticleValidator;

class ParticleTest extends TestCase {

  public function testRequiredField() {
    $field = Particle::required("first_name");
    $this->assertInstanceOf(Field::class, $field);
  }

  public function testGetName() {
    $name = "first_name";
    $field = Particle::required($name);
    $this->assertEquals($name, $field->getName());
  }

  public function testChainIsAccessable() {
    $field = Particle::required("first_name");
    $this->assertInstanceOf(Particle\Chain::class, $field->chain());
    $this->assertInstanceOf(ParticleValidator\Chain::class, $field->chain());
  }

  public function testApplyValidationRules() {
    $field = Particle::required("first_name");
    $field->chain()->alpha();
    $this->assertFieldIsValid($field, 'fred');
    $this->assertFieldIsNotValid($field, 'abc123', 1);
  }

  public function testMultipleValidationRules() {
    $field = Particle::required('first_name');
    $field->chain()->alpha();
    $field->chain()->lengthBetween(3, 20);
    $this->assertFieldIsValid($field, 'fred');
    $this->assertFieldIsNotValid($field, 'ab', 1);
    $this->assertFieldIsNotValid($field, 'abc123', 1);
    $this->assertFieldIsNotValid($field, 'a0', 2);
  }


  public function testOptionalField() {
    $field = Particle::optional("age");
    $this->assertInstanceOf(Field::class, $field);
  }
}