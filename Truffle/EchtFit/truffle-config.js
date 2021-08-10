var keythereum = require("keythereum");
const HDWalletProvider = require('@truffle/hdwallet-provider');
//var datadir = "/Users/martijndebruijn/ethereum/node-1"
const password = "Welkom01";

module.exports = {
  // Uncommenting the defaults below 
  // provides for an easier quick-start with Ganache.
  // You can also follow this format for other networks;
  // see <http://truffleframework.com/docs/advanced/configuration>
  // for more details on how to specify configuration options!
  //
  networks: {
   development: {
     host: "192.168.1.3",
     port: 7545,
     network_id: "*"
   },
   geth: {
    host: "0.0.0.0",
    port: 8545,
    network_id: "1234"
   },
   staging: {
    provider: function() {
      /// Dynamicly Generate private key using a loca keystore File
      // var address = "0xe5423bab00679e0768d6ded41f98e23cccbee5cf";
      // var keyObject = keythereum.importFromFile(address, datadir);
      // var privateKey = keythereum.recover(password, keyObject);
      //
      // return new HDWalletProvider(privateKey.toString('hex'), "http://94.213.158.62:8545");

      return new HDWalletProvider("a18c8d159e1acea990d3a65c42369b11b1c8b3074c39bd692e31143c8f43606f", "http://94.213.158.62:8545");
    },
    host: "94.213.158.62",
    port: 8545,
    network_id: "1234"
   }
  },
  compilers: {
    solc: {
      version: "0.8.6"
    }
  }
  //
};
