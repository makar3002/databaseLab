<?php
namespace core\lib\algorithm\chromosomes;


use core\lib\algorithm\gens\IGen;
use core\lib\algorithm\gens\LabGen;


class LabChromosomeFabric extends BaseChromosomeFabric {
    protected function generateGen(): IGen {
        return new LabGen();
    }
}
