<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\FabricType;
use App\Models\Color;
use App\Models\BottomType;
use App\Models\Size;
use App\Models\BodyTypeFit;
use App\Models\GarmentCondition;
class SellController extends Controller
{
    /**
     * Show the sell form.
     */
    public function showSellForm()
    {
        $categories = Category::all();
        $fabric_types = FabricType::all();
        $colors = Color::all();
        $bottom_types = BottomType::all();
        $sizes = Size::all();
        $body_type_fits = BodyTypeFit::all();
        $garment_conditions = GarmentCondition::all();
        $showFilters = false;
        return view('sell', compact('categories', 'fabric_types', 'colors', 'bottom_types', 'sizes', 'body_type_fits', 'garment_conditions', 'showFilters'));
    }

    /**
     * Store a new cloth item.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|exists:category,id',
            'gender' => 'required|in:Male,Female,Unisex',
            'brand' => 'nullable|string|max:255',
            'fabric' => 'nullable|exists:fabric_types,id',
            'color' => 'nullable|exists:colors,id',
            'bottom_type' => 'nullable|exists:bottom_types,id',
            'size' => 'required|exists:sizes,id',
            'body_type_fit' => 'nullable|exists:body_type_fits,id',
            'condition' => 'required|in:Brand New,Like New,Good Condition,Worn but Usable',
            'defects' => 'nullable|string',
            'rent_price' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'images' => 'required|array|min:1|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the cloth record
        $cloth = Cloth::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'gender' => $request->input('gender'),
            'brand' => $request->input('brand'),
            'fabric' => $request->input('fabric'),
            'color' => $request->input('color'),
            'bottom_type' => $request->input('bottom_type'),
            'size' => $request->input('size'),
            'fit_type' => $request->input('body_type_fit'),
            'condition' => $request->input('condition'),
            'defects' => $request->input('defects'),
            'rent_price' => $request->input('rent_price'),
            'security_deposit' => $request->input('security_deposit'),
            'is_available' => true,
            'chest_bust' => $request->input('chest_bust'),
            'waist' => $request->input('waist'),
            'length' => $request->input('length'),
            'shoulder' => $request->input('shoulder'),
            'sleeve_length' => $request->input('sleeve_length'),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('clothes', 'public');
                
                // Store image record in cloth_images table
                $cloth->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect('/')->with('success', 'Your item has been listed successfully!');
    }
} 