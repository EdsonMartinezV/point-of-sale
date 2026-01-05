<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PriceModification;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Provider;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index() {
        $purchases = Purchase::with([
            'provider',
            'purchaseItems' => [
                'product',
                'priceModification'
            ]
        ])->get();
        $providers = Provider::all();
        return Inertia::render('Purchases/Index', [
            'purchases' => PurchaseResource::collection($purchases),
            'providers' => ProviderResource::collection($providers),
        ]);
    }

    public function show($id) {
        $purchase = Purchase::with('items')->findOrFail($id);
        return Inertia::render('Purchases/Show', [
            'purchase' => $purchase
        ]);
    }

    public function store(StorePurchaseRequest $request) {
        $validated = $request->validated();

        $purchase = Purchase::create([
            'total' => $validated['total'],
            'user_id' => $request->user()->id,
            'provider_id' => $validated['provider_id'],
        ]);

        $purchaseTotal = 0;

        foreach ($validated['purchase_items'] as $item) {
            $purchaseTotal += $item['quantity'] * $item['cost_price'];
            $product = Product::find($item['product_id']);

            $purchaseItem = PurchaseItem::create([
                'quantity' => $item['quantity'],
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
            ]);

            $currentPriceModification = $product->priceModifications()->where('is_current', true)->first();

            $setNewAsCurrent = false;
            if ($product->sold_by_retail) {
                /* Product has ran out and this product data gets in */
                /* If there is no currentPriceModification, it is the first purchase */
                if ($currentPriceModification) {
                    if ($currentPriceModification->total_retail_remaining_stock == 0) {
                        $setNewAsCurrent = true;

                        $product->update([
                            'sold_by_retail' => $item['sold_by_retail'],
                            'retail_units_per_box' => $item['retail_units_per_box'],
                            'cost_price' => $item['cost_price'],
                            'first_wholesale_percentage' => $item['first_wholesale_percentage'] / 100,
                            'second_wholesale_percentage' => $item['second_wholesale_percentage'] / 100,
                            'third_wholesale_percentage' => $item['third_wholesale_percentage'] / 100,
                            'retail_percentage' => $item['sold_by_retail'] ? $item['retail_percentage'] / 100 : null,
                        ]);
                    }
                } else {
                    $setNewAsCurrent = true;

                    $product->update([
                        'sold_by_retail' => $item['sold_by_retail'],
                        'retail_units_per_box' => $item['retail_units_per_box'],
                        'cost_price' => $item['cost_price'],
                        'first_wholesale_percentage' => $item['first_wholesale_percentage'] / 100,
                        'second_wholesale_percentage' => $item['second_wholesale_percentage'] / 100,
                        'third_wholesale_percentage' => $item['third_wholesale_percentage'] / 100,
                        'retail_percentage' => $item['sold_by_retail'] ? $item['retail_percentage'] / 100 : null,
                    ]);
                }
            /* Product has ran out and this product data gets in */
            /* If there is no currentPriceModification, it is the first purchase */
            } else if ($currentPriceModification) {
                if ($currentPriceModification->remaining_stock == 0) {
                    $setNewAsCurrent = true;

                    $product->update([
                        'sold_by_retail' => $item['sold_by_retail'],
                        'retail_units_per_box' => $item['retail_units_per_box'],
                        'cost_price' => $item['cost_price'],
                        'first_wholesale_percentage' => $item['first_wholesale_percentage'] / 100,
                        'second_wholesale_percentage' => $item['second_wholesale_percentage'] / 100,
                        'third_wholesale_percentage' => $item['third_wholesale_percentage'] / 100,
                        'retail_percentage' => $item['sold_by_retail'] ? $item['retail_percentage'] / 100 : null,
                    ]);
                }
            } else {
                $setNewAsCurrent = true;

                $product->update([
                    'sold_by_retail' => $item['sold_by_retail'],
                    'retail_units_per_box' => $item['retail_units_per_box'],
                    'cost_price' => $item['cost_price'],
                    'first_wholesale_percentage' => $item['first_wholesale_percentage'] / 100,
                    'second_wholesale_percentage' => $item['second_wholesale_percentage'] / 100,
                    'third_wholesale_percentage' => $item['third_wholesale_percentage'] / 100,
                    'retail_percentage' => $item['sold_by_retail'] ? $item['retail_percentage'] / 100 : null,
                ]);
            }

            PriceModification::create([
                'is_current' => $setNewAsCurrent,
                'sold_by_retail' => $item['sold_by_retail'],
                'retail_units_per_box' => $item['retail_units_per_box'],
                'remaining_stock' => $item['quantity'],
                'retail_remaining_stock' => $item['sold_by_retail'] ? 0 : null,
                'cost_price' => $item['cost_price'],
                'first_wholesale_percentage' => $item['first_wholesale_percentage'] / 100,
                'second_wholesale_percentage' => $item['second_wholesale_percentage'] / 100,
                'third_wholesale_percentage' => $item['third_wholesale_percentage'] / 100,
                'retail_percentage' => $item['sold_by_retail'] ? $item['retail_percentage'] / 100 : null,
                'product_id' => $item['product_id'],
                'purchase_item_id' => $purchaseItem->id,
            ]);

            $product->update([
                'stock' => $product->stock + $item['quantity'],
            ]);
        }

        $purchase->update([
            'total' => $purchaseTotal
        ]);

        return redirect()->route('purchases.index')->with('success', 'Compra creada exitosamente.');
    }

    public function update(UpdatePurchaseRequest $request, $id) {
        $validated = $request->validated();

        // TODO Implement update logic

        return redirect()->route('purchases.index')->with('success', 'Compra actualizada exitosamente.');
    }

    public function destroy($id) {
        $purchase = Purchase::findOrFail($id);
        foreach ($purchase->purchaseItems() as $item) {
            $item->priceModification()->delete();
            // TODO Complete destroying
        }
        $purchase->purchaseItems()->delete();
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Compra eliminada exitosamente.');
    }
}
