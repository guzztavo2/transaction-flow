<?php

namespace App\Domain\ValueObjects;

use App\Exceptions\InvalidMoneyCurrency;

final class Money
{

    private string $amount;
    private string $currency;

    private const BC_SCALE = 2;

    public function __construct(string|float|int $amount, string $currency = 'BRL')
    {
        $this->setAmount($amount);
        $this->currency = strtoupper($currency);
    }
    private function normalize(string|float|int $amount): string
    {
        $stringAmount = (string) $amount;
        return bcadd($stringAmount, '0', self::BC_SCALE);
    }

    private function setAmount(string|float|int $amount): void
    {
        $this->amount = $this->normalize($amount);
        if (self::compareStringMoney($this->amount, '0') === -1)
            throw new \InvalidArgumentException('Amount of Money cannot be negative.');
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): Money
    {
        $this->ensureSameCurrency($other);
        $result = bcadd($this->getAmount(), $other->getAmount(), self::BC_SCALE);
        return new self($result, $this->getCurrency());
    }

    public function subtract(Money $other): Money
    {
        $this->ensureSameCurrency($other);
        $result = bcsub($this->getAmount(), $other->getAmount(), self::BC_SCALE);
        return new self($result, $this->getCurrency());
    }

    public function isGreaterThan(Money $other): bool
    {
        $this->ensureSameCurrency($other);
        return self::compareStringMoney($this->getAmount(), $other->getAmount()) === 1;
    }

    public function isLessThan(Money $other): bool
    {
        $this->ensureSameCurrency($other);
        return self::compareStringMoney($this->getAmount(), $other->getAmount()) === -1;
    }

    public function isEquals(Money $other): bool
    {
        $this->ensureSameCurrency($other);
        return self::compareStringMoney($this->getAmount(), $other->getAmount()) === 0;
    }

    private static function compareStringMoney(string $money1, string $money2): int
    {
        return bccomp($money1, $money2, self::BC_SCALE);
    }
    public function format(): string
    {
        return 'R$ ' .  $this->getAmount();
    }

    private function ensureSameCurrency(Money $other): void
    {
        if ($this->getCurrency() !== $other->getCurrency())
            throw new InvalidMoneyCurrency('Currency Money is Different!');
    }

    public function __toString(): string
    {
        return $this->getAmount();
    }
}
