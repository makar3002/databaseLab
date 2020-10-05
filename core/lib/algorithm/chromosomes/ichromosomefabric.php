<?php
namespace core\lib\algorithm\chromosomes;


use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\mutators\IMutator;


interface IChromosomeFabric {
    public function __construct(IFitness $fitness, IMutator $mutator);
    public function setMutator(IMutator $mutator);
    public function makeChromosome(): IChromosome;
}
