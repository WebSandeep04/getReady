<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cloth;
use App\Models\Category;
use App\Models\FabricType;
use App\Models\Color;
use App\Models\Size;
use App\Models\BottomType;
use App\Models\BodyTypeFit;
use Illuminate\Support\Facades\Auth;
use App\Models\ClothImage;
use App\Models\AvailabilityBlock;
use Illuminate\Support\Facades\Storage;

class ClothController extends Controller
{
    public function show($id)
    {
        $cloth = Cloth::with(['images', 'availabilityBlocks'])->findOrFail($id);
        
        // Convert IDs to names for display
        if ($cloth->category) {
            $category = Category::find($cloth->category);
            $cloth->category = $category ? $category->name : 'Unknown';
        }
        
        if ($cloth->fabric) {
            $fabric = FabricType::find($cloth->fabric);
            $cloth->fabric = $fabric ? $fabric->name : 'Unknown';
        }
        
        if ($cloth->color) {
            $color = Color::find($cloth->color);
            $cloth->color = $color ? $color->name : 'Unknown';
        }
        
        if ($cloth->size) {
                // The Size model uses the 'sizes' table    
            $size = Size::where('id', $cloth->size)->first();
            $cloth->size = $size ? $size->name : 'Unknown';
        }
        
        if ($cloth->bottom_type) {
            $bottomType = BottomType::find($cloth->bottom_type);
            $cloth->bottom_type = $bottomType ? $bottomType->name : 'Unknown';
        }
        
        if ($cloth->fit_type) {
            $bodyTypeFit = BodyTypeFit::find($cloth->fit_type);
            $cloth->fit_type = $bodyTypeFit ? $bodyTypeFit->name : 'Unknown';
        }
        
        $showFilters = false;
        return view('clothes.show', compact('cloth', 'showFilters'));
    }

    public function index()
    {
        $clothes = Cloth::where('user_id', Auth::id())->with('images')->get();
        $sizes = Size::all();
        
        
        return view('clothes.index', compact('clothes', 'sizes'));
    }

    public function edit($id)
    {
        $cloth = Cloth::where('user_id', Auth::id())->with(['images', 'availabilityBlocks'])->findOrFail($id);
        $sizes = Size::all();
        
        // Get data for dropdowns
        $categories = Category::orderBy('name')->get();
        $fabricTypes = FabricType::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        $bottomTypes = BottomType::orderBy('name')->get();
        $fitTypes = BodyTypeFit::orderBy('name')->get();

        $showFilters = true;   
        
        return view('clothes.edit', compact('cloth', 'sizes', 'categories', 'fabricTypes', 'colors', 'bottomTypes', 'fitTypes', 'showFilters'));
    }

    public function update(Request $request, $id)
    {
        $cloth = Cloth::where('user_id', Auth::id())->findOrFail($id);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('clothes', 'public');
                $cloth->images()->create([
                    'image_path' => $imagePath
                ]);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Images uploaded successfully'
                ]);
            }
        }
        
        // Handle cloth details update
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Unisex',
            'brand' => 'nullable|string|max:255',
            'fabric' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'bottom_type' => 'nullable|string|max:255',
            'chest_bust' => 'nullable|string|max:50',
            'waist' => 'nullable|string|max:50',
            'length' => 'nullable|string|max:50',
            'shoulder' => 'nullable|string|max:50',
            'sleeve_length' => 'nullable|string|max:50',
            'size' => 'required|exists:sizes,id',
            'fit_type' => 'nullable|string|max:255',
            'condition' => 'required|in:Brand New,Like New,Good Condition,Worn but Usable',
            'defects' => 'nullable|string',
            'is_cleaned' => 'boolean',
            'rent_price' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
        ]);

        // Prepare update data
        $updateData = $request->only([
            'title', 'category', 'gender', 'brand', 'fabric', 'color', 
            'bottom_type', 'chest_bust', 'waist', 'length', 'shoulder', 
            'sleeve_length', 'size', 'fit_type', 'condition', 'defects', 
            'rent_price', 'security_deposit'
        ]);
        
        // Handle checkbox
        $updateData['is_cleaned'] = $request->has('is_cleaned') ? 1 : 0;
        
        $cloth->update($updateData);

        // Handle availability blocks
        if ($request->has('availability_blocks')) {
            // Validate availability blocks
            $request->validate([
                'availability_blocks.*.start_date' => 'required|date',
                'availability_blocks.*.end_date' => 'required|date|after_or_equal:availability_blocks.*.start_date',
                'availability_blocks.*.type' => 'required|in:available,blocked',
                'availability_blocks.*.reason' => 'nullable|string|max:255',
            ]);
            
            // Delete existing availability blocks
            $cloth->availabilityBlocks()->delete();
            
            // Create new availability blocks
            foreach ($request->availability_blocks as $block) {
                if (!empty($block['start_date']) && !empty($block['end_date'])) {
                    $cloth->availabilityBlocks()->create([
                        'start_date' => $block['start_date'],
                        'end_date' => $block['end_date'],
                        'type' => $block['type'] ?? 'blocked',
                        'reason' => $block['reason'] ?? null,
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cloth updated successfully',
                'cloth' => $cloth->fresh()
            ]);
        }

        return redirect()->route('listed.clothes')->with('success', 'Cloth updated successfully');
    }

    public function destroy($id)
    {
        $cloth = Cloth::where('user_id', Auth::id())->findOrFail($id);
        $cloth->delete();

        return redirect()->route('listed.clothes')->with('success', 'Cloth deleted successfully');
    }

    public function destroyImage($imageId)
    {
        $image = ClothImage::whereHas('cloth', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($imageId);
        
        // Delete the file from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
} 