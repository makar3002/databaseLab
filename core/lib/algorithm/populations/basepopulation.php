<?php
namespace core\lib\algorithm\populations;


use core\lib\algorithm\chromosomes\IChromosomeFabric;
use core\lib\algorithm\crossingovers\ICrossingOver;
use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\mutators\IMutator;


abstract class BasePopulation implements IPopulation {
    protected int $populationSize;
    protected IMutator $mutator;
    protected IFitness $fitness;
    protected ICrossingOver $crossingOver;
    protected IChromosomeFabric $chromosomeFabric;

    public function __construct(IFitness $fitness, IMutator $mutator, ICrossingOver $crossingOver, IChromosomeFabric $chromosomeFabric, int $populationSize) {
        if ($populationSize <= 0) {
            throw new \InvalidArgumentException('Популяция не может иметь отрицательный размер.');
        }
        $this->populationSize = $populationSize;

        $this->fitness = $fitness;
        $this->mutator = $mutator;
        $this->crossingOver = $crossingOver;
        $this->chromosomeFabric = $chromosomeFabric;
    }

    public function setMutator(IMutator $mutator) {
        $this->mutator = $mutator;
    }

    public function setCrossingOver(ICrossingOver $crossingOver) {
        $this->crossingOver = $crossingOver;
    }
}
