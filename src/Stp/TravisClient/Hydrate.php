<?php

namespace Stp\TravisClient;

trait Hydrate
{
    public static function hydrate($data, $object)
    {
        foreach ($data as $key => $value) {
            $methodName = explode('_', $key);
            $methodName = 'set' . join(
                '',
                array_map(
                    function ($element) {
                        return ucfirst($element);
                    },
                    $methodName
                )
            );

            if (method_exists($object, $methodName)) {
                $object->$methodName($value);
            }
        }
    }
}
