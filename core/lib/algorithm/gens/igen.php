<?php
namespace core\lib\algorithm\gens;

interface IGen {
    public function applyMutation($mutationValue);
    public function getValue();
}