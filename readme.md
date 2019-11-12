# Cart

[![Build Status](https://scrutinizer-ci.com/g/simara-svatopluk/cart/badges/build.png?b=master)](https://scrutinizer-ci.com/g/simara-svatopluk/cart/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/simara-svatopluk/cart/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/simara-svatopluk/cart/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/simara-svatopluk/cart/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/simara-svatopluk/cart/?branch=master)

Sample project that demonstrates how simple e-shop cart can look like. Created to show how do I understand domain-driven design.
* Domain objects
* Layers
* Unit testing
* Contract testing
* Doctrine infrastructure

You can also see how do I program, commit, what technology I can handle...

### Dynamic Prices

Prices are not stored in the Cart itself but are loaded on demand.
This is a common use-case because we usually need fresh prices from a database or ERP.

Cart is separated from "loading prices" by an interface [`Prices`](src/Domain/Prices.php).
This is a domain element but have to be implemented by the project needs - by API calls or database queries.

### Fixed Prices

Once we add a product into the cart, the price may be fixed.
If it is the project use-case, check out [fixed-prices version](https://github.com/simara-svatopluk/cart/tree/fixed-prices).

## TDD

This project was written in the spirit of TDD, see [commits](https://github.com/simara-svatopluk/cart/commits/TDD).

