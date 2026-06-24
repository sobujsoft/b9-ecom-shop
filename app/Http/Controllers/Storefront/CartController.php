<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the shopping cart page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('shop/Cart');
    }
}
