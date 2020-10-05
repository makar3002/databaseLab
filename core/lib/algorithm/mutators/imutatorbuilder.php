<?php
namespace core\lib\algorithm\mutators;

interface IMutatorBuilder {
    public function __construct();
    public function build(): IMutator;
}
