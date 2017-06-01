<?php

namespace Lodestone\Entities;

use Lodestone\Modules\Benchmark;
use Lodestone\Validator\BaseValidator;

/**
 * Class AbstractEntity
 *
 * @package Lodestone\Entities
 */
class AbstractEntity
{
    /**
     * @var BaseValidator
     */
    protected $validator;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        $this->initializeValidator();
    }

    /**
     * @return $this
     */
    protected function initializeValidator()
    {
        $this->validator = new BaseValidator();
        return $this;
    }

    /**
     * Map all class attributes
     *
     * @return array
     */
    public function toArray()
    {
        Benchmark::start(__METHOD__,__LINE__);
        $reflector = new \ReflectionClass(get_class($this));

        // get properties
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);

        // loop through properties
        $arr = [];
        foreach($properties as $property) {
            $doc = $reflector->getProperty($property->name)->getDocComment();

            // parse fields
            $result = [];
            if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $doc, $matches)) {
                $result = array_combine($matches[1], $matches[2]);
            }

            // only add those with a var type
            if (isset($result['var'])) {
                // get base type
                switch(explode('|', $result['var'])[0]) {
                    // basic
                    case 'string':
                    case 'int':
                    case 'integer':
                    case 'bool':
                    case 'float':
                        $arr[$property->name] = $this->{$property->name};
                        break;

                    // if array, need to loop through it
                    case 'array':
                        foreach($this->{$property->name} as $i => $value) {
                            if (method_exists($value, 'toArray')) {
                                $arr[$property->name][] = $value->toArray();
                            } else {
                                $arr[$property->name] = $value;
                            }
                        }
                        break;

                    // assume a class, get its data
                    default:
                        $arr[$property->name] = $this->{$property->name}->toArray();
                        break;
                }
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return $arr;
    }
}