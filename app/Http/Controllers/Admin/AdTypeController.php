<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdType;


class AdTypeController extends Controller
{
    public function index()
    {
        $adTypes = AdType::withCount('posts')->orderBy('base_price')->get();
        return view('admin.ad-types.index', compact('adTypes'));
    }

    public function create()
    {
        return view('admin.ad-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ad_types,name',
            'base_price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1|max:365',
            'description' => 'nullable|string|max:500',
            'is_paid' => 'boolean',
            'is_recurring' => 'boolean',
            'features' => 'array',
        ]);

        $features = [
            'badge' => $request->boolean('features.badge'),
            'pin' => $request->boolean('features.pin'),
            'slider' => $request->boolean('features.slider'),
        ];

        AdType::create([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'is_paid' => $request->boolean('is_paid'),
            'is_recurring' => $request->boolean('is_recurring'),
            'features' => $features,
        ]);

        return redirect()->route('admin.ad-types.index')
                        ->with('success', 'تم إنشاء نوع الإعلان بنجاح');
    }

    public function show(AdType $adType)
    {
        $adType->load('posts', 'pricingRules');
        return view('admin.ad-types.show', compact('adType'));
    }

    public function edit(AdType $adType)
    {
        return view('admin.ad-types.edit', compact('adType'));
    }

    public function update(Request $request, AdType $adType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ad_types,name,' . $adType->id,
            'base_price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1|max:365',
            'description' => 'nullable|string|max:500',
            'is_paid' => 'boolean',
            'is_recurring' => 'boolean',
        ]);

        $features = [
            'badge' => $request->boolean('features.badge'),
            'pin' => $request->boolean('features.pin'),
            'slider' => $request->boolean('features.slider'),
        ];

        $adType->update([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'is_paid' => $request->boolean('is_paid'),
            'is_recurring' => $request->boolean('is_recurring'),
            'features' => $features,
        ]);

        return redirect()->route('admin.ad-types.index')
                        ->with('success', 'تم تحديث نوع الإعلان بنجاح');
    }

    public function destroy(AdType $adType)
    {
        if ($adType->posts()->exists()) {
            return back()->with('error', 'لا يمكن حذف نوع الإعلان لوجود إعلانات مرتبطة به');
        }

        $adType->delete();
        return redirect()->route('admin.ad-types.index')
                        ->with('success', 'تم حذف نوع الإعلان بنجاح');
    }
}
