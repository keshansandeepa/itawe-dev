<?php


namespace App\Service;


use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;
class Money
{

    protected $money;

    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('LKR'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('lkr', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }

    public function add(\App\Cart\Money $money)
    {
        $this->money = $this->money->add($money->instance());

        return $this;
    }

    public function subtract(Money  $money)
    {
        $this->money = $this->money->subtract($money->instance());

        return $this;
    }

    public function multiply(Money $money)
    {
        $this->money = $this->money->multiply($money->instance());

        return $this;
    }

    public function instance()
    {
        return $this->money;
    }
}