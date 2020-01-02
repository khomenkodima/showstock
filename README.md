# Show Stock test module

Using Magento 2.3 write one or more modules that will:
For configurable products
- Show the amount of stock for each variant inside the variant dropdown
- Show products that are out of stock but not allow the option to be selected
For simple products
- Show the amount of stock available next to the stock status.

Consideration should be taken to ensure that:
- It works with multi-way configurable
- Work in an extensible fashion
- Works with MSI
- Works with Full Page Cache Enabled

Notes:

1. Solution expected that configuration option Catalog->Inventory-> Display Out of Stock Products = Yes. For live module this could be implemented in 'Recurring Upgrade Scripts'
2. For now stock qty is calculated without attributes combination. For example: Qty if 'Red' associated simple products of all sizes. Or, all "XL" associated products in any color. It works well for conf products with 1 or 2 conf attributes. If projects expects more conf attributes, it makes sense to change stock Qty respecting to previously selected attributes. But this solution is much more complicated.
