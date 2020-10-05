<?php
namespace core\lib\algorithm\mutators;

abstract class BaseMutator implements IMutator {
    protected int $mutationSize;

    public function __construct(int $mutationSize) {
        $this->setMutationSize($mutationSize);
    }

    public function setMutationSize(int $mutationSize) {
        if ($mutationSize <= 0) {
            throw new \InvalidArgumentException('Размер мутации не может быть меньше 0.');
        }

        $this->mutationSize = $mutationSize;
    }

    public function getMutationSize(): int {
        return $this->mutationSize;
    }
}
