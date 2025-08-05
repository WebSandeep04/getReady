<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page with clothes data.
     */
    public function index(Request $request)
    {
        $query = Cloth::with('images')
            ->where('is_available', true)
            ->where('is_approved', true);

        // Filter by category_id
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by fabric_id
        if ($request->filled('fabric')) {
            $query->where('fabric', $request->fabric);
        }

        // Filter by color_id
        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        // Filter by size_id
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        // Filter by bottom_type_id
        if ($request->filled('bottomType')) {
            $query->where('bottom_type', $request->bottomType);
        }

        // Filter by price range
        if ($request->filled('priceRange')) {
            $priceRange = $request->priceRange;
            switch ($priceRange) {
                case '0-500':
                    $query->where('rent_price', '<=', 500);
                    break;
                case '500-1000':
                    $query->whereBetween('rent_price', [500, 1000]);
                    break;
                case '1000-2000':
                    $query->whereBetween('rent_price', [1000, 2000]);
                    break;
                case '2000-5000':
                    $query->whereBetween('rent_price', [2000, 5000]);
                    break;
                case '5000+':
                    $query->where('rent_price', '>', 5000);
                    break;
            }
        }

        $clothes = $query->latest()->take(8)->get();

        $showHero = true;
        $showFilters = true;

        return view('home', compact('clothes', 'showHero', 'showFilters'));
    }
} 