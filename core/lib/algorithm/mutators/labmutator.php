<?php
namespace core\lib\algorithm\mutators;


use core\lib\algorithm\gens\IGen;


class LabMutator extends BaseMutator {
    protected $mutationParameter;

    public function setMutationParameter($mutationParameter) {
        if (!is_int($mutationParameter)) {
            $mutationParameter = intval($mutationParameter);
        }

        if ($mutationParameter <= 0) {
            throw new \InvalidArgumentException('Мутационный параметр не может быть меньше 0');
        }

        $this->mutationParameter = $mutationParameter;
    }

    public function applyMutation(array &$genList) {
        $mutatedGensCount = intval(count($genList) / 2);
        $mutatedGenKeyList = array_rand($genList, $mutatedGensCount);
        foreach ($mutatedGenKeyList as $genKey) {
            /** @var IGen $mutatatedGen */
            $mutatatedGen = &$genList[$genKey];
            $mutatatedGen->applyMutation(rand(1, -1) * rand($this->mutationParameter, 1));
        }
    }
}
