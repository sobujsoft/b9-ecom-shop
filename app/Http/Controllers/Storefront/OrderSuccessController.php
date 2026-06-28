<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class OrderSuccessController extends Controller
{
    /**
     * Display the order success page.
     */
    public function __invoke(): Response
    {
        $order = session('order');

        return Inertia::render('shop/OrderSuccess', [
            'order' => $order,
        ]);
    }
}
