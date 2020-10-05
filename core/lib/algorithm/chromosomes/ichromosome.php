<?php
namespace core\lib\algorithm\chromosomes;


use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\mutators\IMutator;


interface IChromosome {
    public function __construct(IFitness $fitness, IMutator $mutator, array $genList);
    public function calculateFitness();
    public function sex(IChromosome $chromosome): IChromosome;
    public function applyMutation(): void;
}
