<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('shop/Checkout');
    }
}
