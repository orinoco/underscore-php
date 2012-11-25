<?php
use Underscore\Object;
use Underscore\Underscore;

class ObjectTest extends UnderscoreWrapper
{
  public function testCanObjectifyAnArray()
  {
    $object = Object::from(array('foo' => 'bar'));
    $this->assertEquals('bar', $object->foo);

    $object->bis = 'ter';
    $this->assertEquals('ter', $object->bis);

    $this->assertEquals(array('foo' => 'bar', 'bis' => 'ter'), $object->obtain());
  }

  public function testCanGetKeys()
  {
    $object = Object::keys($this->object);

    $this->assertEquals(array('foo', 'bis'), $object);
  }

  public function testCanGetValues()
  {
    $object = Object::Values($this->object);

    $this->assertEquals(array('bar', 'ter'), $object);
  }

  public function testCanPluckColumns()
  {
    $object = Object::pluck($this->objectMulti, 'foo');
    $matcher = (object) array('bar', 'bar', null);

    $this->assertEquals($matcher, $object);
  }

  public function testCanSetValues()
  {
    $object = (object) array('foo' => array('foo' => 'bar'), 'bar' => 'bis');
    $object = Object::set($object, 'foo.bar.bis', 'ter');

    $this->assertEquals('ter', $object->foo['bar']['bis']);
    $this->assertObjectHasAttribute('bar', $object);
  }

  public function testCanRemoveValues()
  {
    $array = Object::remove($this->objectMulti, '0.foo');
    $matcher = (array) $this->objectMulti;
    unset($matcher[0]->foo);

    $this->assertEquals((object) $matcher, $array);
  }

  public function testCanConvertToJson()
  {
    $under = Object::toJSON($this->object);

    $this->assertEquals('{"foo":"bar","bis":"ter"}', $under);
  }

  public function testCanSort()
  {
    $child = (object) array('sort' => 5);
    $child_alt = (object) array('sort' => 12);
    $object = (object) array('name' => 'foo', 'age' => 18, 'child' => $child);
    $object_alt = (object) array('name' => 'bar', 'age' => 21, 'child' => $child_alt);
    $collection = array($object, $object_alt);

    $under = Object::sort($collection, 'name', 'asc');
    $this->assertEquals(array($object_alt, $object), $under);

    $under = Object::sort($collection, 'child.sort', 'desc');
    $this->assertEquals(array($object_alt, $object), $under);

    $under = Object::sort($collection, function($value) {
      return $value->child->sort;
    }, 'desc');
    $this->assertEquals(array($object_alt, $object), $under);
  }

  public function testCanConvertToArray()
  {
    $object = Object::toArray($this->object);

    $this->assertEquals($this->array, $object);
  }
}
