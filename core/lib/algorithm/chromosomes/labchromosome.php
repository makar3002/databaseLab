<?php
namespace core\lib\algorithm\chromosomes;

class LabChromosome extends BaseChromosome {
    public function sex(IChromosome $chromosome): IChromosome {
        if (!($chromosome instanceof LabChromosome)) {
            throw new \InvalidArgumentException('Скрещиваемая хромосома должна быль объектом класса LabChromosome.');
        }

        if ($this->fitness !== $chromosome->fitness) {
            throw new \InvalidArgumentException('Скрещиваемая хромосома имеет другую целевую функцию.');
        }

        $childGenList = array();
        $genFromCurrentChromosomeCount = rand($this->chromosomeSize, 0);
        for ($i = 0; $i < $genFromCurrentChromosomeCount; $i++) {
            $childGenList[$i] = clone $this->genList[$i];
        }
        for ($i = $genFromCurrentChromosomeCount; $i < $this->chromosomeSize; $i++) {
            $childGenList[$i] = clone $chromosome->genList[$i];
        }

        return new LabChromosome($this->fitness, $this->mutator, $childGenList);
    }

    public function dump() {
        echo 'x = ' . $this->genList[0]->getValue() . ';<br>';
        echo 'y = ' . $this->genList[1]->getValue() . ';<br>';
        echo 'z = ' . $this->genList[2]->getValue() . ';<br>';
        echo 't = ' . $this->genList[3]->getValue() . ';<br>';
    }
}
