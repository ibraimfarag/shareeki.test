<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdPricingRule;
use App\Models\AdType;
use App\Models\Category;

class PricingRuleController extends Controller
{
    public function index()
    {
        $rules = AdPricingRule::with(['adType', 'category'])
                             ->orderBy('active', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);
                             
        return view('admin.pricing-rules.index', compact('rules'));
    }

    public function create()
    {
        $adTypes = AdType::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('admin.pricing-rules.create', compact('adTypes', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'ad_type_id' => 'nullable|exists:ad_types,id',
            'duration_unit' => 'required|in:day,week,month',
            'multiplier' => 'required|numeric|min:0.1|max:10',
            'active' => 'boolean',
        ]);

        AdPricingRule::create($request->all());

        return redirect()->route('admin.pricing-rules.index')
                        ->with('success', 'تم إنشاء قاعدة التسعير بنجاح');
    }

    public function edit(AdPricingRule $pricingRule)
    {
        $adTypes = AdType::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('admin.pricing-rules.edit', compact('pricingRule', 'adTypes', 'categories'));
    }

    public function update(Request $request, AdPricingRule $pricingRule)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'ad_type_id' => 'nullable|exists:ad_types,id',
            'duration_unit' => 'required|in:day,week,month',
            'multiplier' => 'required|numeric|min:0.1|max:10',
            'active' => 'boolean',
        ]);

        $pricingRule->update($request->all());

        return redirect()->route('admin.pricing-rules.index')
                        ->with('success', 'تم تحديث قاعدة التسعير بنجاح');
    }

    public function destroy(AdPricingRule $pricingRule)
    {
        $pricingRule->delete();
        return redirect()->route('admin.pricing-rules.index')
                        ->with('success', 'تم حذف قاعدة التسعير بنجاح');
    }

    public function toggleStatus(AdPricingRule $pricingRule)
    {
        $pricingRule->update(['active' => !$pricingRule->active]);
        
        $status = $pricingRule->active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return back()->with('success', $status . ' قاعدة التسعير بنجاح');
    }
}
