<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    public function __invoke(){
        request()->validate([
            'customer'=> 'required|string', // otra manera de hacerlo sin la |, es 'customer' => ['required','string']
            'email' => ['required', 'email'], //validacion de email
            'payment_method' => ['required','in:1,2,3'], //1,2,3 son los valores que voy a permitir
            'products' => ['required','array'],
            'products.*.name' => ['required', 'string','max:50'], //todos los objetos que tengan un nombre *
            'products.*.price' => ['required','numeric','gt:0'], //gt -> greater than
            'products*.quantity' => ['required','integer','gt:1'],
        ]);

        $request = request()->all();

        $data = [
            'customer' => $request['customer'],
            'created_at' => now()->format('Y-m-d H:i'),
            'email' => $request['email'],
            'order_number' => 'RB'.now()->format('Y').now()->format('m').'-'.rand(1,100),
            'payment_method' => match($request['payment_method']){
                1 => 'Transferencia bancaria',
                2 => ' Contraentrega',
                3 => 'Tarjeta de credito'
            },
            'order_status' => match($request['payment_method']){
                1 => 'Pendiente de revision',
                2 => 'En proceso',
                3 => 'En proceso'
            },
        ];

        //proceso para calcular el total
        // Inicializamos una variable para calcular el total
        $total = 0;
        // Iteramos el arreglo de productos
        foreach($request['products'] as $product) {
			      // Calculamos el subtotal
            $subtotal = $product['price'] * $product['quantity'];
            // Agregamos el producto a un nuevo indice del arreglo
            $data['products'][] = [
                'name' => $product['name'],
                'price' => number_format($product['price'], 2), // Formateamos los decimales
                'quantity' => $product['quantity'],
                'subtotal' => number_format($subtotal,2),// Formateamos los decimales
            ];
            // 
            $total += $subtotal; // Se va sumando al total
        }
        $data['total'] = number_format($total, 2); //  Formateamos los decimales


        return view('EmailPreview', $data);
    }
}
