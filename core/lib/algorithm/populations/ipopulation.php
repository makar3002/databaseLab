<?php
namespace core\lib\algorithm\populations;


use core\lib\algorithm\chromosomes\IChromosomeFabric;
use core\lib\algorithm\crossingovers\ICrossingOver;
use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\mutators\IMutator;


interface IPopulation {
    public function __construct(
            IFitness $fitness,
            IMutator $mutator,
            ICrossingOver $crossingOver,
            IChromosomeFabric $chromosomeFabric,
            int $populationSize
    );
    public function setMutator(IMutator $mutator);
    public function setCrossingOver(ICrossingOver $crossingOver);
    public function applyCrossOver();
}
