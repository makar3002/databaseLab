<?php


use core\lib\algorithm\GeneticAlgorithm;


require_once($_SERVER['DOCUMENT_ROOT'] . '/core/util/loader.php');?>
<pre>
    <?
    $algorithm = new GeneticAlgorithm(false);
    $algorithm->calculate();
    ?>
</pre>