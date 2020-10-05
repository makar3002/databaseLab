<?php
namespace core\lib\algorithm\gens;

class LabGen extends BaseGen {
    private const MAX_VALUE = 10000;
    private const MIN_VALUE = 1;

    public function __construct() {
        $this->value = rand(LabGen::MAX_VALUE, LabGen::MIN_VALUE);
    }

    public function applyMutation($mutationValue) {
        parent::applyMutation($mutationValue);

        if ($this->value > LabGen::MAX_VALUE) {
            $this->value = LabGen::MAX_VALUE;
        }

        if ($this->value < LabGen::MIN_VALUE) {
            $this->value = LabGen::MIN_VALUE;
        }
    }

    public function getValue(): int {
        return $this->value;
    }
}