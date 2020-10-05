<?php
namespace core\lib\algorithm\populations;


use core\lib\algorithm\chromosomes\IChromosome;
use core\lib\algorithm\chromosomes\IChromosomeFabric;
use core\lib\algorithm\crossingovers\ICrossingOver;
use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\mutators\IMutator;


class LabPopulation extends BasePopulation {
    private array $chromosomeList = array();

    public function __construct(IFitness $fitness, IMutator $mutator, ICrossingOver $crossingOver, IChromosomeFabric $chromosomeFabric, int $populationSize) {
        parent::__construct($fitness, $mutator, $crossingOver, $chromosomeFabric, $populationSize);
        $this->generate();
    }

    protected function generate() {
        $this->chromosomeList = array();
        for ($i = 0; $i < $this->populationSize; $i++) {
            $this->chromosomeList[] = $this->chromosomeFabric->makeChromosome();
        }
    }

    public function applyCrossOver() {
        $this->chromosomeList = $this->crossingOver->crossOver($this->chromosomeList);
    }

    public function applyMutation() {
        $mutationCount = $this->mutator->getMutationSize();
        $mutatedChromosomeKeyList = array_rand($this->chromosomeList, $mutationCount);
        foreach ($mutatedChromosomeKeyList as $chromosomeKey) {
            /** @var IChromosome $mutatedChromosome */
            $mutatedChromosome = &$this->chromosomeList[$chromosomeKey];
            $mutatedChromosome->applyMutation();
        }
    }

    public function getMinChromosome() {
        $this->applyCrossOver();
        return $this->chromosomeList[0];
    }
}
