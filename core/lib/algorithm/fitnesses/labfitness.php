<?php
namespace core\lib\algorithm\fitnesses;

class LabFitness extends BaseFitness {
    protected function objectiveFunction(...$params) {
        $f1 = $this->function1($params[0], $params[1], $params[2], $params[3]);
        $f2 = $this->function2($params[0], $params[1], $params[2], $params[3]);
        $f3 = $this->function3($params[0], $params[1], $params[2], $params[3]);
        $f4 = $this->function4($params[0], $params[1], $params[2], $params[3]);

        $value = 3 * $f1 * $f1 + 2 * $f2 * $f2 + $f3 * $f3 + $f4 * $f4 + 1;
        return $value;
    }

    public function function1(int $x, int $y, int $z, int $t): int {
        return $x + $y - $z - $t;
    }

    public function function2(int $x, int $y, int $z, int $t): int {
        return $x * $x + $y * $y + $z * $z + $t * $t - 4;
    }

    public function function3(int $x, int $y, int $z, int $t): int {
        return $x * $y * $z * $t - 1;
    }

    public function function4(int $x, int $y, int $z, int $t): int {
        return $x + $y + $z + $t - 4;
    }

    public function getSize(): int {
        return 4;
    }
}