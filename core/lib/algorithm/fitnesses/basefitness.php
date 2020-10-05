<?php
namespace core\lib\algorithm\fitnesses;


use core\lib\algorithm\gens\IGen;
use core\util\exceptions\NotImplementedException;


abstract class BaseFitness implements IFitness {
    public function getSize(): int {
        throw new NotImplementedException();
    }

    public function calculate(array $genList) {
        foreach ($genList as $gen) {
            if (!is_subclass_of($gen, IGen::class)) {
                throw new \InvalidArgumentException();
            }
        }

        $valueList = array_map(function (IGen $gen) {
            $genValue = $gen->getValue();
            if (!is_int($genValue)) {
                $genValue = intval($genValue);
            }

            return $genValue;
        }, $genList);

        return call_user_func_array(array($this, 'objectiveFunction'), $valueList);
    }

    abstract protected function objectiveFunction(...$params);
}