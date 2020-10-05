<?php
namespace core\lib\algorithm\mutators;

interface IMutator {
    public function __construct(int $mutationSize);
    public function setMutationSize(int $mutationSize);
    public function getMutationSize(): int;
    public function applyMutation(array &$genList);
}
