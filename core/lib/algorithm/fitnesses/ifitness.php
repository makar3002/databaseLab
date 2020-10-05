<?php
namespace core\lib\algorithm\fitnesses;

interface IFitness {
    public function calculate(array $genList);
    public function getSize(): int;
}