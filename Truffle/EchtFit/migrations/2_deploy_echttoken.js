
const EchtToken = artifacts.require("EchtToken");

module.exports = function(deployer) {
  deployer.deploy(EchtToken);
};
