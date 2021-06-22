<?php

namespace Appbakkers\Ethereum;

interface TokenContract {
    public function balance($address);
    public function transfer($address, $tokens);
}