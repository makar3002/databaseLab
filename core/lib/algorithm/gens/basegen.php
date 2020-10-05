<?php
namespace core\lib\algorithm\gens;

abstract class BaseGen implements IGen {
    protected $value;

    public function applyMutation($mutationValue) {
        $this->value += $mutationValue;
    }
}