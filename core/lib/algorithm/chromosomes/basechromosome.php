<?php
namespace core\lib\algorithm\chromosomes;


use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\gens\IGen;
use core\lib\algorithm\mutators\IMutator;


abstract class BaseChromosome implements IChromosome {
    protected string $genClass;
    protected int $chromosomeSize;
    protected array $genList = array();
    protected IMutator $mutator;
    protected IFitness $fitness;
    protected $fitnessValue;

    public function __construct(IFitness $fitness, IMutator $mutator, array $genList) {
        if (!$this->checkGenList($genList)) {
            throw new \InvalidArgumentException('Параметр $genList должен состоять из объектов класса, реализующего интерфейс IGen.');
        }

        $this->genList = $genList;
        $this->fitness = $fitness;
        $this->chromosomeSize = $fitness->getSize();
        $this->mutator = $mutator;
    }

    private function checkGenList(array $genList): bool {
        foreach ($genList as $gen) {
            if (!is_subclass_of($gen, IGen::class)) {
                return false;
            }
        }

        return true;
    }

    public function calculateFitness() {
        if (!isset($this->fitnessValue)) {
            $this->fitnessValue = $this->fitness->calculate($this->genList);
        }

        return $this->fitnessValue;
    }

    public function applyMutation(): void {
        $this->mutator->applyMutation($this->genList);
        unset($this->fitnessValue);
    }
}
