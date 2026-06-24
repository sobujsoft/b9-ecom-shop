<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    /**
     * Display the storefront product catalog.
     */
    public function __invoke(): Response
    {
        return Inertia::render('shop/Shop');
    }
}
