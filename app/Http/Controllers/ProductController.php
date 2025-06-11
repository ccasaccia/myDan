<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $productDetails = $this->fetchProducts();

               return Inertia::render('Product/Show', [
            'productDetails' => $productDetails
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function fetchProducts()
    {
        $url = 'https://store-dev.daneurope.org/rest/V1/orders/3';
        $token = 'gufnf7eqc4pf55i73k3c6yjd3f45f3k2';

        $response = Http::withToken($token)
            ->acceptJson()
            ->get($url);


       $order = $response->json();

    $structuredResponse = [
        'order' => [
            'id' => $order['entity_id'],
            'increment_id' => $order['increment_id'],
            'created_at' => $order['created_at'],
            'status' => $order['status'],
            'total' => $order['grand_total'],
            'subtotal' => $order['subtotal'],
            'shipping' => $order['shipping_amount'],
            'tax' => $order['tax_amount'],
            'currency' => $order['order_currency_code'],
        ],
        'customer' => [
            'id' => $order['customer_id'],
            'firstname' => $order['customer_firstname'],
            'lastname' => $order['customer_lastname'],
            'email' => $order['customer_email'],
            'gender' => $order['customer_gender'],
            'is_guest' => $order['customer_is_guest'],
        ],
        'billing_address' => $order['billing_address'],
        'shipping_address' => $order['extension_attributes']['shipping_assignments'][0]['shipping']['address'] ?? null,
        'payment' => [
            'method' => $order['payment']['method'],
            'amount' => $order['payment']['amount_ordered'],
            'details' => $order['extension_attributes']['payment_additional_info'] ?? [],
        ],
        'items' => collect($order['items'])->map(function ($item) {
            return [
                'id' => $item['item_id'],
                'name' => $item['name'],
                'sku' => $item['sku'],
                'qty' => $item['qty_ordered'],
                'price' => $item['price'],
                'price_incl_tax' => $item['price_incl_tax'] ?? null,
                'tax_amount' => $item['tax_amount'],
                'product_type' => $item['product_type'],
                'parent_item_id' => $item['parent_item_id'] ?? null,
            ];
        }),
        'taxes' => $order['extension_attributes']['applied_taxes'] ?? [],
    ];

    return response()->json($structuredResponse);

        // Gestione errori
        return [
            'errore' => true,
            'status' => $response->status(),
            'messaggio' => $response->body()
        ];
    }
}
