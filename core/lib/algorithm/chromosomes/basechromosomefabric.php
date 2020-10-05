<?php
namespace core\lib\algorithm\chromosomes;


use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\gens\IGen;
use core\lib\algorithm\mutators\IMutator;


abstract class BaseChromosomeFabric implements IChromosomeFabric {
    protected int $chromosomeSize;
    protected IMutator $mutator;
    protected IFitness $fitness;

    public function __construct(IFitness $fitness, IMutator $mutator) {
        $this->mutator = $mutator;
        $this->fitness = $fitness;
        $this->chromosomeSize = $fitness->getSize();
    }

    public function setMutator(IMutator $mutator) {
        $this->mutator = $mutator;
    }

    public function makeChromosome(): IChromosome {
        $genList = $this->generateGenList();
        return new LabChromosome($this->fitness, $this->mutator, $genList);
    }

    private function generateGenList(): array {
        $genList = array();
        for ($i = 0; $i < $this->chromosomeSize; $i++) {
            $genList[] = $this->generateGen();
        }

        return $genList;
    }

    abstract protected function generateGen(): IGen;
}
