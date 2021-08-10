// SPDX-License-Identifier: MIT
pragma solidity >=0.8.0;

// This is just a simple example of a coin-like contract.
// It is not standards compatible and cannot be expected to talk to other
// coin/token contracts. If you want to create a standards-compliant
// token, see: https://github.com/ConsenSys/Tokens. Cheers!

contract EchtToken {
	address public owner;
	string public name;
	string public symbol;

	mapping(address => mapping(address => uint256)) private _allowances;

	mapping (address => uint) balances;

	// Events
	event Transfer(address indexed _from, address indexed _to, uint256 _value);
	event Paid(address indexed _from, uint256 _value);
	event Minted(address indexed _to, uint256 _value);
	event Approval(address indexed _owner , address indexed _spender, uint256 _value);


	constructor() {
        require(msg.sender != address(0), "ERC20: Constructor failed with 0x address");
		owner = msg.sender;
		name = "EchtToken";
		symbol = "EchT";
		balances[tx.origin] = 1000000;
	}


	// ERC20 standard function
	function transfer(address receiver, uint tokens) public returns(bool){
		_transfer(msg.sender, receiver, tokens);
		return true;
	}

	// Alias for balanceOf
	function getBalance(address addr) public view returns(uint256) {
		return balances[addr];
	}

	// ERC20 standard function
	function balanceOf(address tokenOwner) public view returns(uint256) {
		return this.getBalance(tokenOwner);
	}

	// ERC20 Standard function
	function approve(address spender, uint amount) public returns(bool) {
		return _approve(msg.sender, spender, amount);
	}

	// ERC20 standard function
	function transferFrom(address sender, address recipient, uint256 amount) public returns(bool) {

		_transfer(sender, recipient, amount);

        uint256 currentAllowance = _allowances[sender][msg.sender];
        require(currentAllowance >= amount, "ERC20: transfer amount exceeds allowance");
        
        _approve(sender, msg.sender, currentAllowance - amount);

        return true;
	}

	function mintCoins(address addr, uint amount) external ownerOrContract {
		require(amount < 1e60, "Maximum issuance exceeded");
		balances[addr] += amount;
		emit Minted(addr, amount);
	}
	

	function spendCoin(address[] memory customers, uint[] memory amounts) public onlyOwner {
		for (uint i=0; i<customers.length; i++){
			balances[customers[i]] -= amounts[i];
			emit Paid(customers[i], amounts[i]);
		}	
	}

	// ERC20 function 
	function allowance(address _owner, address _spender) public view virtual returns (uint256) {
        return _allowances[_owner][_spender];
    }

    /**
     * @dev Atomically increases the allowance granted to `spender` by the caller.
     *
     * This is an alternative to {approve} that can be used as a mitigation for
     * problems described in {IERC20-approve}.
     *
     * Emits an {Approval} event indicating the updated allowance.
     *
     * Requirements:
     *
     * - `spender` cannot be the zero address.
     */
    function increaseAllowance(address spender, uint256 addedValue) public returns (bool) {
        _approve(msg.sender, spender, _allowances[msg.sender][spender] + addedValue);
        return true;
    }

    /**
     * @dev Atomically decreases the allowance granted to `spender` by the caller.
     *
     * This is an alternative to {approve} that can be used as a mitigation for
     * problems described in {IERC20-approve}.
     *
     * Emits an {Approval} event indicating the updated allowance.
     *
     * Requirements:
     *
     * - `spender` cannot be the zero address.
     * - `spender` must have allowance for the caller of at least
     * `subtractedValue`.
     */
    function decreaseAllowance(address spender, uint256 subtractedValue) public returns (bool) {
        uint256 currentAllowance = _allowances[msg.sender][spender];
        require(currentAllowance >= subtractedValue, "ERC20: decreased allowance below zero");
        unchecked {
            _approve(msg.sender, spender, currentAllowance - subtractedValue);
        }

        return true;
    }

	//
	// Modifiers
	//
	modifier onlyOwner() { 
		require(msg.sender == owner, "You are not the owner");
		_; 
	}

	modifier ownerOrContract() {
		require (msg.sender == owner || msg.sender == address(this), "You are not the owner or contract");
		_;
	}
	///


	function _transfer(
        address sender,
        address recipient,
        uint256 amount
    ) internal {
        require(sender != address(0), "ERC20: transfer from the zero address");
        require(recipient != address(0), "ERC20: transfer to the zero address");

        uint256 senderBalance = balances[sender];
        require(senderBalance >= amount, "ERC20: transfer amount exceeds balance");
            balances[sender] = senderBalance - amount;
        
        balances[recipient] += amount;

        emit Transfer(sender, recipient, amount);
    }

	/**
     * @dev Sets `amount` as the allowance of `spender` over the `owner` s tokens.
     *
     * This internal function is equivalent to `approve`, and can be used to
     * e.g. set automatic allowances for certain subsystems, etc.
     *
     * Emits an {Approval} event.
     *
     * Requirements:
     *
     * - `owner` cannot be the zero address.
     * - `spender` cannot be the zero address.
     */
    function _approve(
        address _owner,
        address _spender,
        uint256 _amount
    ) internal returns(bool) {
        require(_owner != address(0), "ERC20: approve from the zero address");
        require(_spender != address(0), "ERC20: approve to the zero address");

        _allowances[_owner][_spender] = _amount;
        emit Approval(_owner, _spender, _amount);

        return true;
    }
}
