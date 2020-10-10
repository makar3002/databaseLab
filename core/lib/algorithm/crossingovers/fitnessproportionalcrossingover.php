<?php
namespace core\lib\algorithm\crossingovers;

use core\lib\algorithm\chromosomes\IChromosome;


class FitnessProportionalCrossingOver extends LabCrossingOver {
    protected function reproduceChromosomes(array $mostFitChromosomeList, $neededCount): array {
        $newChromosomeList = array();
        $fitnessList = array_map(function (IChromosome $chromosome) {
            return $chromosome->calculateFitness();
        }, $mostFitChromosomeList);
        $fitnessSum = array_sum($fitnessList);

        while (count($newChromosomeList) < $neededCount) {
            $firstChromosome = $this->chooseChromosome($mostFitChromosomeList, $fitnessSum, $fitnessList);
            do {
                $secondChromosome = $this->chooseChromosome($mostFitChromosomeList, $fitnessSum, $fitnessList);
            } while ($firstChromosome === $secondChromosome);

            $newChromosomeList[] = $firstChromosome->sex($secondChromosome);
        }

        return array_merge($mostFitChromosomeList, $newChromosomeList);
    }

    private function chooseChromosome(array $chromosomeList, $fitnessSum, $fitnessList): IChromosome {
        $randChromosomeFitness = rand($fitnessSum, 0);
        for ($i = 0, $currentFitness = 0; $i < count($chromosomeList); $i++, $currentFitness += $fitnessList[$i-1]) {
            if ($currentFitness < $randChromosomeFitness) {
                continue;
            }

            return $chromosomeList[$i];
        }
        return $chromosomeList[$i - 1];
    }
}
