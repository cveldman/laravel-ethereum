<?php

namespace Appbakkers\Ethereum;

interface TokenContract {
    // public function totalSupply() : int;
    public function balanceOf($address) : int;
    public function allowance($address, $tokens) : int;

    // public function transfer($address, $tokens) : bool;
    // public function approve($address) : bool;
    public function transferFrom($sender, $recipient, $amount) : string;
}
