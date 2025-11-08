<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\PriceModification;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index() {
        $sales = Sale::with('items')->get();
        return Inertia::render('Sales/Index', [
            'sales' => $sales,
        ]);
    }

    public function show($id) {
        $sale = Sale::with('items')->findOrFail($id);
        return Inertia::render('Sales/Show', [
            'sale' => $sale
        ]);
    }

    public function store(StoreSaleRequest $request) {
        $validated = $request->validated();

        $sale = Sale::create([
            'client' => $validated['client'],
            'total' => $validated['total'],
            'paid_amount' => $validated['paid_amount'],
            'change_amount' => $validated['change_amount'],
        ]);

        $saleTotal = 0;
        foreach ($validated['sale_items'] as $item) {
            $priceModification = PriceModification::find($item['price_modification_id']);
            $product = $priceModification->product;
            $saleItemTotal = $this->getSaleItemTotal($item['quantity'], $item['is_retail_sale'], $item['selected_percentage'], $priceModification->toArray());
            $saleTotal += $saleItemTotal;

            SaleItem::create([
                'quantity' => $item['quantity'],
                'is_retail_sale' => $item['is_retail_sale'],
                'selected_percentage' => $item['selected_percentage'],
                'total' => $saleItemTotal,
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'price_modification_id' => $item['price_modification_id'],
            ]);

            /* Reduce stock
             *
             * Retail sales
             * */
            if ($item['is_retail_sale']) {
                if ($item['quantity'] > $priceModification->total_retail_remaining_stock) {
                    $auxRetailStock = $item['quantity'] - $priceModification->total_retail_remaining_stock;
                    $priceModification->update([
                        'remaining_stock' => 0,
                        'retail_remaining_stock' => 0,
                        'is_current' => false,
                    ]);

                    $newPriceModification = $product->priceModifications()->where('created_at', '>', $priceModification->created_at)->first();
                    $boxesToReduce = ceil($auxRetailStock / $newPriceModification->retail_units_per_box);
                    $newRetailRemainingStock = ( $boxesToReduce * $newPriceModification->retail_units_per_box ) - $auxRetailStock;
                    $newPriceModification->update([
                        'remaining_stock' => $newPriceModification->remaining_stock - $boxesToReduce,
                        'retail_remaining_stock' => $newRetailRemainingStock,
                        'is_current' => true,
                    ]);

                    $product->update([
                        'stock' => $product->stock - $boxesToReduce,
                        'retail_remaining_stock' => $newRetailRemainingStock,
                    ]);
                } else {
                    if ($item['quantity'] > $priceModification->retail_remaining_stock) {
                        $auxRetailStock = $item['quantity'] - $priceModification->retail_remaining_stock;
                        $boxesToReduce = ceil($auxRetailStock / $priceModification->retail_units_per_box);
                        $newRetailRemainingStock = ($boxesToReduce * $priceModification->retail_units_per_box) - $auxRetailStock;

                        $priceModification->update([
                            'remaining_stock' => $priceModification->remaining_stock - $boxesToReduce,
                            'retail_remaining_stock' => $newRetailRemainingStock,
                        ]);

                        $product->update([
                            'stock' => $product->stock - $boxesToReduce,
                            'retail_remaining_stock' => $newRetailRemainingStock,
                        ]);

                    } else {
                        $priceModification->update([
                            'retail_remaining_stock' => $priceModification->retail_remaining_stock - $item['quantity'],
                        ]);

                        $product->update([
                            'retail_remaining_stock' => $product->retail_remaining_stock - $item['quantity'],
                        ]);
                    }
                }

            /* Wholesale sales */
            } else {
                if ($item['quantity'] > $priceModification->remaining_stock) {
                    $auxStock = $item['quantity'] - $priceModification->remaining_stock;
                    $auxRetailStock = $priceModification->retail_remaining_stock;
                    $priceModification->update([
                        'remaining_stock' => 0,
                        'retail_remaining_stock' => 0,
                        'is_current' => false,
                    ]);

                    /* PriceModification stock */
                    $newPriceModification = $product->priceModifications()->where('created_at', '>', $priceModification->created_at)->first();
                    $newPriceModification->update([
                        'remaining_stock' => $newPriceModification->remaining_stock - $auxStock,
                        'retail_remaining_stock' => $auxRetailStock != null ? $newPriceModification->retail_remaining_stock + $auxRetailStock : $newPriceModification->retail_remaining_stock,
                        'is_current' => true,
                    ]);
                } else {
                    $priceModification->update([
                        'remaining_stock' => $priceModification->remaining_stock - $item['quantity'],
                    ]);
                }

                /* Product stock */
                $product->update([
                    'stock' => $product->stock - $item['quantity'],
                ]);
            }
        }

        $sale->update(['total' => $saleTotal]);

        return redirect()->route('sales.index')->with('success', 'Venta creada exitosamente.');
    }

    public function update(UpdateSaleRequest $request, $id) {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $id) {
            $sale = Sale::findOrFail($id);
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
            ]);

            $sale->items()->delete();

            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Venta actualizada exitosamente.');
    }

    public function destroy($id) {
        $sale = Sale::findOrFail($id);
        $sale->items()->delete();
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venta eliminada exitosamente.');
    }

    private function getSaleItemTotal($quantity, $isRetailSale, $selectedPercentage, $priceModification) {
        $total = 0;

        if ($isRetailSale) {
            $total = $quantity * ($priceModification['cost_price'] / $priceModification['retail_units_per_box']) * (1 + $priceModification[$selectedPercentage]);
        } else {
            $total = $quantity * $priceModification['cost_price'] * (1 + $priceModification[$selectedPercentage]);
        }

        return $total;
    }
}
