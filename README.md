# architect-labs
Test model of basket with table delivery and special offers "buy one red widget, get the second half price"

Model require catalog of available products as plain array, delivery and offers are optional.
By default basket assume no offers and cost of delivery is 0.

Table delivery can be provided to model as plain array where key is cost of products in the basket and value is cost of delivery.
```
$delivery = [
    90 => 0,
    50 => 2.95,
    0 => 4.95,
];
```
Basket will calculate delivery based on cost of products:
1. free delivery for basket over 90
2. 2.95 for basket between 50 and 90
3. 4.95 for basket under 50

Offers can be provided as plain array where value is code of product

```
$offer = [
    'R01',
];
```
Basket will calculate half price for each 2-nd product with code R01

Products to basket can be added with method `->add()`
```
$basket->add('R01');
```
Basket will throw exception in case product was not found in provided catalog of products.
