<?php
namespace core\lib\algorithm\crossingovers;

interface ICrossingOver {
    public function crossOver(array $chromosomeList): array;
}