<?php
namespace core\lib\algorithm;


use core\lib\algorithm\chromosomes\IChromosomeFabric;
use core\lib\algorithm\chromosomes\LabChromosomeFabric;
use core\lib\algorithm\crossingovers\ICrossingOver;
use core\lib\algorithm\crossingovers\LabCrossingOver;
use core\lib\algorithm\fitnesses\IFitness;
use core\lib\algorithm\fitnesses\LabFitness;
use core\lib\algorithm\mutators\IMutator;
use core\lib\algorithm\mutators\LabMutatorBuilder;
use core\lib\algorithm\populations\IPopulation;
use core\lib\algorithm\populations\LabPopulation;


class GeneticAlgorithm {
    private IPopulation $population;
    private IMutator $mutator;
    private IFitness $fitness;
    private ICrossingOver $crossingOver;
    private IChromosomeFabric $chromosomeFabric;
    private bool $needDump;

    public function __construct(bool $needDump) {
        $this->needDump = $needDump;
        $this->fitness = new LabFitness();

        $mutatorBulder = new LabMutatorBuilder();
        $this->mutator =
                $mutatorBulder
                        ->setMutationSize(100)
                        ->setMutationParameter(2)
                        ->build();

        $this->crossingOver = new LabCrossingOver();

        $this->chromosomeFabric = new LabChromosomeFabric($this->fitness, $this->mutator);

        $this->population = new LabPopulation($this->fitness, $this->mutator, $this->crossingOver, $this->chromosomeFabric, 2000);
    }

    public function calculate() {
        for ($i = 0; $i < 200; $i++) {
            $this->population->applyCrossOver();
            $this->population->applyMutation();
            $minChromosome = $this->population->getMinChromosome();
            if ($this->needDump) {
                echo 'Номер итерации: ' . $i . ';<br>';
                $minChromosome->dump();
                echo '<hr>';
            }
        }

        $minChromosome = $this->population->getMinChromosome();
        echo '<hr>';
        $minChromosome->dump();
        echo '<hr>';

        echo 'Найденный минимум: ' . $minChromosome->calculateFitness();
    }
}