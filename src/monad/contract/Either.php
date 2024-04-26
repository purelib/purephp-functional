<?php

namespace purephp\functional\monad\contract;

use Stringable;

interface Either extends Stringable
{

    // 创建一个成功的 Either 实例
    public static function of($value): Either;

    // 创建一个失败的 Either 实例
    public static function left($error): Either;

    public function map(): Either;

    public function isRight(): bool;

    public function isLeft(): bool;

    public function getRight();

    public function getLeft();

    public function bind(callable $function): Either;

    public function then(callable $function): Either;

    public static function assertTrue(callable $fn, $data);
}
