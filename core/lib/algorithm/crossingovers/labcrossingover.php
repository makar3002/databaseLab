<?php
namespace core\lib\algorithm\crossingovers;


use core\lib\algorithm\chromosomes\IChromosome;


class LabCrossingOver extends BaseCrossingOver {
    public function crossOver(array $chromosomeList): array {
        if (!$this->checkChromosomes($chromosomeList)) {
            throw new \InvalidArgumentException();
        }

        $absoluteChromosomeCount = count($chromosomeList);
        $mostFitChromosomeCount = $this->getMostFitChromosomeCount($absoluteChromosomeCount);
        $mostUnfitChromosomeCount = $absoluteChromosomeCount - $mostFitChromosomeCount;

        usort($chromosomeList, function (IChromosome $chromosome1, IChromosome $chromosome2) {
            return $chromosome1->calculateFitness() > $chromosome2->calculateFitness();
        });

        for ($i = 0; $i < $mostUnfitChromosomeCount; $i++) {
            array_pop($chromosomeList);
        }

        return $this->reproduceChromosomes($chromosomeList, $mostUnfitChromosomeCount);
    }

    private function reproduceChromosomes(array $mostFitChromosomeList, $neededCount): array {
        $newChromosomeList = array();
        for ($i = 0; $i < $neededCount; $i++) {
             $coupleChromosomeKeyList = array_rand($mostFitChromosomeList, 2);
             $newChromosomeList[] = $this->reproduceChromosome(
                     $mostFitChromosomeList[$coupleChromosomeKeyList[0]],
                     $mostFitChromosomeList[$coupleChromosomeKeyList[1]],
             );
        }

        return array_merge($mostFitChromosomeList, $newChromosomeList);
    }

    private function reproduceChromosome(IChromosome $chromosome1, IChromosome $chromosome2) {
        return $chromosome1->sex($chromosome2);
    }

    private function checkChromosomes(array $chromosomeList): bool {
        foreach ($chromosomeList as $chromosome) {
            if (!is_subclass_of($chromosome, IChromosome::class)) {
                return false;
            }
        }

        return true;
    }

    private function getMostFitChromosomeCount($absoluteChromosomeCount) {
        return intval($absoluteChromosomeCount / 2);
    }
}