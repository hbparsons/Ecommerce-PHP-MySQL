<?php

function addTax($amount, $taxRate = 0.12)
{
    return $amount + getTaxAmount($amount, $taxRate);
}

function getTaxAmount($amount, $taxRate = 0.12)
{
    return $amount * $taxRate;
}
