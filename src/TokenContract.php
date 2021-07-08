<?php

namespace Appbakkers\Ethereum;

interface TokenContract {
    public function totalSupply($address) : int;
    public function balanceOf($address) : int;
    public function allowance($address, $tokens) : int;

    public function transfer($address) : bool;
    public function approve($address) : bool;
    public function transferFrom($address, $tokens) : bool;
}
