<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cloth;
use App\Models\User;
use App\Models\Notification;
use App\Models\FrontendSetting;

class AdminController extends Controller
{
    public function index()
    {
        $showFilters = false;
        return view('admin.screens.admin', compact('showFilters'));
    }

    // Frontend Management
    public function frontend()
    {
        $sections = [
            'general' => 'General Settings',
            'logo' => 'Logo Settings',
            'hero' => 'Hero Section',
            'about' => 'About Section',
            'footer' => 'Footer Section',
            'social' => 'Social Media'
        ];
        
        $settings = FrontendSetting::orderBy('section')->orderBy('label')->get();
        return view('admin.screens.frontend', compact('settings', 'sections'));
    }

    // Update frontend setting (AJAX)
    public function updateFrontendSetting(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
            'type' => 'required|string'
        ]);

        $setting = FrontendSetting::where('key', $request->key)->first();
        
        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Setting not found']);
        }

        // Handle file upload for image type
        if ($request->type === 'image' && $request->hasFile('value')) {
            $file = $request->file('value');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $setting->value = 'images/' . $filename;
        } else {
            $setting->value = $request->value;
        }

        $setting->save();

        return response()->json([
            'success' => true, 
            'message' => 'Setting updated successfully',
            'value' => $setting->value
        ]);
    }

    // Get frontend settings by section (AJAX)
    public function getFrontendSettings($section)
    {
        $settings = FrontendSetting::where('section', $section)->get();
        return response()->json($settings);
    }

    // Fetch all clothes (AJAX)
    public function fetchClothes()
    {
        $clothes = Cloth::with('images', 'user')->get();
        return response()->json($clothes);
    }

    // Approve a cloth (AJAX)
    public function approveCloth($id)
    {
        $cloth = Cloth::with('user')->findOrFail($id);
        $cloth->is_approved = true;
        $cloth->save();

        // Send notification to the user
        if ($cloth->user) {
            Notification::create([
                'user_id' => $cloth->user->id,
                'title' => 'Item Approved',
                'message' => "Your item '{$cloth->title}' has been approved and is now live on our platform!",
                'type' => 'success',
                'icon' => 'bi-check-circle',
                'data' => [
                    'cloth_id' => $cloth->id,
                    'cloth_title' => $cloth->title
                ]
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Reject a cloth (AJAX)
    public function rejectCloth($id)
    {
        $cloth = Cloth::with('user')->findOrFail($id);
        $cloth->is_approved = false;
        $cloth->save();

        // Send notification to the user
        if ($cloth->user) {
            Notification::create([
                'user_id' => $cloth->user->id,
                'title' => 'Item Rejected',
                'message' => "Your item '{$cloth->title}' has been rejected. Please review and resubmit.",
                'type' => 'warning',
                'icon' => 'bi-exclamation-triangle',
                'data' => [
                    'cloth_id' => $cloth->id,
                    'cloth_title' => $cloth->title
                ]
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Dashboard stats for AJAX
    public function dashboardStats()
    {
        $users = User::count();
        $clothes = Cloth::count();
        $approved = Cloth::where('is_approved', true)->count();
        $pending = Cloth::where('is_approved', false)->count();
        $monthly = Cloth::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        $months = [];
        $monthlyCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0,0,0,$i,1));
            $monthlyCounts[] = $monthly[$i] ?? 0;
        }
        return response()->json([
            'users' => $users,
            'clothes' => $clothes,
            'approved' => $approved,
            'pending' => $pending,
            'months' => $months,
            'monthly' => $monthlyCounts,
        ]);
    }
}
