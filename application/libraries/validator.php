<?php

class Validator extends Laravel\Validator {

    public function validate_latmatch($attribute, $value, $parameters)
    {
        return Earth::latmatch($value);
        //return preg_match('#^-?\d{1,2}(\.\d{1,6})?$#', $value);
    }
    public function validate_lngmatch($attribute, $value, $parameters)
    {
        return Earth::lngmatch($value);
        //return preg_match('#^-?\d{1,3}(\.\d{1,6})?$#', $value);
    }

}
