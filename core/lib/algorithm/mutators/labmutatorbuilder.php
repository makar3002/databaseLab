<?php
namespace core\lib\algorithm\mutators;


class LabMutatorBuilder implements IMutatorBuilder {
    protected int $mutationParameter;
    protected int $mutationSize;

    public function __construct() {
    }

    public function setMutationParameter($mutationParameter): IMutatorBuilder {
        if (!is_int($mutationParameter)) {
            $mutationParameter = intval($mutationParameter);
        }

        if ($mutationParameter <= 0) {
            throw new \InvalidArgumentException('Мутационный параметр не может быть меньше 0');
        }

        $this->mutationParameter = $mutationParameter;
        return $this;
    }

    public function setMutationSize(int $mutationSize): IMutatorBuilder {
        if ($mutationSize <= 0) {
            throw new \InvalidArgumentException('Размер мутации не может быть меньше 0');
        }

        $this->mutationSize = $mutationSize;
        return $this;
    }

    public function build(): IMutator {
        if (!$this->isMutationParameterAndSizeSet()) {
            throw new \Exception('Не задан мутационный параметр или размер мутации');
        }

        $mutator = new LabMutator($this->mutationSize);
        $mutator->setMutationParameter($this->mutationSize);
        return $mutator;
    }

    protected function isMutationParameterAndSizeSet(): bool {
        if (!isset($this->mutationParameter) || !isset($this->mutationSize)) {
            return false;
        }

        return true;
    }
}
