<?php

namespace Lodestone\Entities;

use Lodestone\Modules\Logging\Benchmark;
use Lodestone\Modules\Validator;

/**
 * Class AbstractEntity
 *
 * @package Lodestone\Entities
 */
class AbstractEntity
{
    /**
     * @return mixed
     */
    public function isset()
    {
        return $this->id ?? false;
    }

    /**
     * Map all class attributes
     *
     * @return array
     */
    public function toArray($useTitleCase = false)
    {
        Benchmark::start(__METHOD__,__LINE__);
        $reflector = new \ReflectionClass(get_class($this));

        // get properties
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PROTECTED);

        // loop through properties
        $arr = [];
        foreach($properties as $property) {
            $propertyName = $property->name;
            $propertyIndex = $property->name;
            
            $doc = $reflector
                ->getProperty($propertyName)
                ->getDocComment();

            // parse fields
            $result = [];
            if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $doc, $matches)) {
                $result = array_combine($matches[1], $matches[2]);
            }
    
            // if to use title case index
            if ($useTitleCase && isset($result['index'])) {
                $propertyIndex = ucwords(trim($result['index']));
            }

            // only add those with a var type
            if (isset($result['var'])) {
                if (!$this->{$propertyName} && $this->{$propertyName} !== '0' && $this->{$propertyName} !== 0) {
                    $arr[$propertyIndex] = null;
                    continue;
                }

                $var = explode('|', $result['var'])[0];
                $var = strtolower(trim($var));

                // get base type
                switch($var) {
                    // basic
                    case 'string':
                    case 'string|null':
                    case 'int':
                    case 'int|null':
                    case 'integer':
                    case 'integer|null':
                    case 'bool':
                    case 'bool|null':
                    case 'float':
                    case 'float|null':
                        $arr[$propertyIndex] = $this->{$propertyName};
                        break;
    
                    case '\datetime':
                        $arr[$propertyIndex] = $this->{$propertyName}->format('U');
                        break;

                    // if array, need to loop through it
                    case 'array':
                        foreach($this->{$propertyName} as $i => $value) {
                            $arr[$propertyIndex][] = ($value instanceof AbstractEntity) ? $value->toArray($useTitleCase) : $value;
                        }
                        break;
                    
                    case 'object':
                        $arr[$propertyIndex] = (array)$this->{$propertyName};
                        foreach($this->{$propertyIndex} as $i => $value) {
                            $arr[$propertyIndex][$i] = (is_object($value)) ? (array)$value : $value;
                        }
                        break;
                    // assume a class, get its data
                    default:
                        $arr[$propertyIndex] = $this->{$propertyName}->toArray($useTitleCase);
                        break;
                }
            }
        }

        Benchmark::finish(__METHOD__,__LINE__);
        return $arr;
    }
}