<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Static product slugs available during the design prototype phase.
     *
     * @var list<string>
     */
    private const STATIC_SLUGS = [
        'wireless-noise-cancelling-headphones',
    ];

    /**
     * Display the product details page.
     */
    public function show(string $slug): Response|HttpResponse
    {
        abort_unless(in_array($slug, self::STATIC_SLUGS, true), 404);

        return Inertia::render('shop/ProductShow', [
            'slug' => $slug,
        ]);
    }
}
