<?php

namespace purephp\functional\monad;

use purephp\functional\monad\contract\Either as ContractEither;

class Either implements ContractEither
{
    protected $value;

    protected $leftValue = null;
    protected $rightValue = null;

    protected $isSuccess;

    private function __construct($value, bool $isSuccess)
    {
        if ($isSuccess) {
            $this->rightValue = $value;
        } else {
            $this->leftValue = $value;
        }

        $this->isSuccess = $isSuccess;
    }

    // 创建一个成功的 Either 实例
    public static function of($value): self
    {
        return new static($value, true);
    }

    // 创建一个失败的 Either 实例
    public static function left($error): self
    {
        return new static($error, false);
    }

    public function map(): self
    {
        return $this;
    }

    public function isRight(): bool
    {
        return $this->isSuccess;
    }

    public function isLeft(): bool
    {
        return !$this->isSuccess;
    }

    public function getRight()
    {
        if (!$this->isRight()) {
            throw new \RuntimeException('Attempted to get the right value of a Left Either');
        }
        return $this->rightValue;
    }

    public function getLeft()
    {
        if (!$this->isLeft()) {
            throw new \RuntimeException('Attempted to get the left value of a Right Either');
        }
        return $this->leftValue;
    }

    // Bind operation for chaining with Right values
    public function bind(callable $function): self
    {
        if ($this->isRight()) {
            return $function($this->rightValue);
        }
        return $this;
    }

    // Then operation for performing side effects with Right values
    public function then(callable $function): self
    {
        if ($this->isRight()) {
            $function($this->rightValue);
        }
        return $this;
    }

    public function __toString()
    {
        return $this->isSuccess ? 'Right(' . $this->value . ')' : 'Left(' . $this->value . ')';
    }

    public static function assertTrue(callable $fn, $data)
    {
        if ($fn($data) === true) {
            return static::of($data);
        } else {
            return static::left($data);
        }
    }
}
