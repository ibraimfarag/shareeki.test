<?php

namespace App\Http\Controllers;

use App\Models\AdType;
use App\Models\Area;
use App\Models\Attachment;
use App\Models\Category;
use App\Http\Requests\PostFormRequest;
use App\Models\Page;
use App\Models\Post;
use App\Models\Payment;
use App\Upload\Upload;
use Illuminate\Http\Request;
// use App\Models\Tag;
use Spatie\Tags\Tag;
use Illuminate\support\Str;
use Illuminate\Support\Facades\File;
use DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $theTags = Tag::all(["id", "name"]);
    //     $tags = [];
    //     foreach($theTags as $tag){
    //         array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
    //     }

    //     return view('main.posts.index', [
    // 'countries' => Area::whereParentId(1)->orderBy('position')->get(),
    // 'categories' => Category::whereNull('category_id')->get(), 
    // 'subcategories' => Category::whereNotNull('category_id')->get(),
    //  'tags' => $tags ,
    //  'posts' => Post::where('blacklist',0)->latest()->paginate(20)]);
    // }




    public function index()
    {
        $countries = Area::whereParentId(1)->orderBy('position')->get();
        $categories = Category::whereNull('category_id')->get();
        $subcategories = Category::whereNotNull('category_id')->get();
        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }


        $baseSelect = [
            'id',
            'code',
            'user_id',
            'category_id',
            'ad_type_id',
            'title',
            'slug',
            'img',
            'price',
            'created_at'
        ];
        $with = [
         
            'category:id,name,slug',
            'user:id,name'
        ];



        $paidPosts = Post::query()
            ->where('blacklist', 0)
            ->where('is_featured', true)
            ->where(function ($q) {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>=', now());
            })
            ->with($with)
            ->select($baseSelect)
            ->latest('created_at')
            ->paginate(12, ['*'], 'paid_page')
            ->withQueryString();


        $Posts = Post::query()
            ->where('blacklist', 0)
            ->where('is_paid', false)
            ->with($with)
            ->select($baseSelect)
            ->latest('created_at')
            ->paginate(20, ['*'], 'free_page')
            ->withQueryString();





        // $posts = Post::where('blacklist',0)->latest()->paginate(20;
        // $posts = Post::where('blacklist',0)->latest()->paginate(20;


        $viewData = [
            'countries' => $countries,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'tags' => $tags,
            'posts' => $Posts,
            'paidPosts' => $paidPosts,
        ];

        return view('main.posts.index', $viewData);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user || empty($user->phone)) {
            return redirect()->route('get_personal_info')->withErrors(['phone' => 'يجب إضافة رقم الجوال أولاً قبل إضافة فرصة جديدة.']);
        }
        $theTags = Tag::whereNotNull('id')->get();
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }


        $aggrements = Page::whereName('agreements')->firstOrFail();
        return view(
            'main.posts.add',
            [
                'countries' => Area::whereParentId(1)->orderBy('position')->get(),
                'aggrements' => $aggrements,
                'subcategories' => Category::whereNotNull('category_id')->get(),
                'categories' => Category::whereNull('category_id')->get(),
                'tags' => json_decode($theTags)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFormRequest $request)
    {

        /*----- in case of user not authenticated -----*/
        if ($request->not_logged_in) {
            if ($request->has('main_image'))
                session()->put('image', Upload::uploadImage($request->main_image, 'temp', $request->title));
            session()->put('post', $request->except('main_image'));
            return redirect()->route('login');
        }
        /*----- in case of user not authenticated -----*/

        // منع المستخدم غير المفعّل للجوال من إضافة إعلان
        $user = auth()->user();
        if (!$user->phone_verified_at) {
            return redirect()->route('get_personal_info')->with('error', 'يجب تفعيل رقم الجوال أولاً قبل إضافة إعلان جديد.');
        }

        $request->full_partnership != "on" ?: $partner_sort[0] = "on";
        $request->loan != "on" ?: $partner_sort[1] = "on";

        $request->full_partnership != "on" ?: $partner_sort[0] = "on";
        $request->full_partnership != "off" ?: $partner_sort[1] = "on";

        $boolenEmail = $request->email == '1' ? 1 : 0;

        if (!isset($partner_sort)) {
            $partner_sort[0] = "on";
            $partner_sort[1] = "on";
        }

        //upload only if request has main_image
        if ($request->has('main_image'))
            $request->merge(['img' => Upload::uploadImage($request->main_image, 'posts', $request->title)]);


        // Prepare All Request Input Either For Entering DB Or For Other Process Depending On Other Model
        $request->merge(['code' => rand(10, 50000), 'user_id' => auth()->user()->id, 'slug' => Str::slug($request->title), 'tags' => explode(',', $request->the_tags), 'partner_sort' => json_encode($partner_sort, JSON_FORCE_OBJECT)]);

        // Create Post
        $the_post = Post::create($request->except('_token', 'visible', 'main_image', 'the_attachment', 'tags', 'the_tags', 'full_partnership', 'loan', 'not_logged_in'));
        $the_post->update(['email' => $boolenEmail]);
        // Get It's name for make a special attachments folder for
        $postName = $the_post->title;

        // Add The Post Tags
        foreach ($request->tags as $tag) {
            DB::table('taggables')->insert(['tag_id' => $tag, 'taggable_type' => 'App\Post', 'taggable_id' => $the_post->id]);

        }

        // Add The Attachments
        if ($request->has('the_attachment')) {
            foreach ($request->the_attachment as $attachment) {
                Attachment::create(['post_id' => $the_post->id, 'name' => Upload::uploadImage($attachment, "attachments/${postName}", $postName . "_" . rand(0, 100000))]);
            }
        }

        // التحقق من الأماكن الفارغة للإعلانات المميزة
        $featuredLimit = 4; // الحد الأقصى للإعلانات المميزة
        $currentFeaturedCount = Post::where('is_featured', true)
            ->where(function ($query) {
                $query->whereNull('featured_until')
                    ->orWhere('featured_until', '>=', now());
            })
            ->count();

        if ($currentFeaturedCount < $featuredLimit) {
            // عرض صفحة العرض للإعلان المميز
            return redirect()->route('posts.featured.offer', $the_post->id);
        }

        return redirect()->route('the_posts.show', $the_post->id);
        // return view('main.posts.show')->withSlug($the_post->id);

        return view('main.posts.success.index')->withSlug($the_post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $the_post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        //$the_post = Post::find($id);
        $the_post = Post::where('id', $id)->orWhere('slug', $id)->first();
        if (!isset($the_post->title)) {
            abort(404);
            return;
        }
        $the_post->blacklist == 0 ?: abort(404);

        $the_post->area = Area::getMainArea($the_post->area_id ?? 0);
        $the_post->parentArea = Area::getMainArea($the_post->area->parent_id ?? 0);
        $the_post->parentCategory = Category::whereId($the_post->category->category_id ?? 1)->first();
        $the_post_title = $the_post->title;

        $attachments = json_decode($the_post->attachments);
        $storage_attachments = [];
        foreach ($attachments as $attachment) {
            $attachment_name = $attachment->name;
            $attachment->the_name = asset("storage/main/attachments/${the_post_title}/${attachment_name}");
            array_push($storage_attachments, $attachment->the_name);
        }

        //dd($storage_attachments);
        $the_post->storage_attachments = $storage_attachments;
        $related = DB::table('taggables')->where('taggable_id', $the_post->id)->get();
        $tags = DB::table('tags')->where('id', $related[0]->tag_id)->get();
        $post = $the_post;

        return view('main.posts.show', ["post" => $the_post, "tags" => json_decode($tags)]);

        // $the_post = Post::find($id);
        // if (!isset($the_post->title)){
        //     abort(404);
        //     return ;
        // }
        // $the_post->blacklist == 0 ? : abort(404) ;

        // $the_post->area = Area::getMainArea($the_post->area_id ?? 0);
        // $the_post->parentArea = Area::getMainArea($the_post->area->parent_id ?? 0);
        // $the_post->parentCategory = Category::whereId($the_post->category->category_id ?? 1)->first();
        // $the_post_title = $the_post->title;

        // $attachments = json_decode($the_post->attachments);
        // $storage_attachments = [];
        // foreach($attachments as $attachment){
        //     $attachment_name = $attachment->name;
        //     $attachment->the_name = asset("storage/main/attachments/${the_post_title}/${attachment_name}");
        //     array_push($storage_attachments, $attachment->the_name);
        // }

        // //dd($storage_attachments);
        // $the_post->storage_attachments = $storage_attachments;



        // $post = $the_post;

        // return view('main.posts.show', ["post" => $the_post, "tags" => json_decode($the_post->tag)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $the_post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $the_post = Post::find($id);
        if (!isset($the_post->title)) {
            abort(404);
            return;
        }
        // Get Current Country Then City
        $city = Area::where('id', $the_post->area_id)->first();
        $city == null ? $country = null : $country = Area::where('id', $city->parent_id)->first()->id;

        // Get Current Category Then SubCategory
        $subCategory = Category::where('id', $the_post->category_id)->first();
        //dd($subCategory);
        $subCategory == null ? $category = null : $category = Category::where('id', $subCategory->id)->first()->category_id;

        //   // Get The Post Tags
        //   $tags = $the_post->tags()->get(["id","name"]);

        $theTags = Tag::whereNotNull('id')->get();
        $tags = [];
        foreach ($theTags as $tag) {
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }

        $the_post->partner_sort = json_decode($the_post->partner_sort, true);

        $related = DB::table('taggables')->where('taggable_id', $the_post->id)->get();
        $tags = DB::table('tags')->where('id', $related[0]->tag_id)->get();
        return view('main.posts.edit', [
            'post' => $the_post,
            'countries' => Area::whereParentId(1)->orderBy('position')->get(),
            'categories' => Category::whereNull('category_id')->get(),
            'sub_categories' => Category::whereNotNull('category_id')->get(),
            'all_cats' => Category::all(),
            'theCity' => $city,
            'theCountry' => $country,
            'theCategory' => $category,
            'theSubCategory' => $subCategory,
            'tags' => $theTags,
            'theTags' => $tags[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $the_post
     * @return \Illuminate\Http\Response
     */
    public function update(PostFormRequest $request, Post $the_post)
    {

        $request->full_partnership != "on" ?: $partner_sort[0] = "on";
        $request->full_partnership != "off" ?: $partner_sort[1] = "on";
        $boolenEmail = $request->email;
        //dd($boolenEmail);
        $boolenEmail = $request->email == 1 ? 1 : 0;

        //return $partner_sort;

        if ($request->loan == "on") {
            $request->merge(['partnership_percentage' => '0.00%']);
        }

        // check if request has main_image
        if ($request->has('main_image')) {
            $request->merge(['img' => Upload::uploadImage($request->main_image, 'posts', $the_post->title)]);
        }

        // Prepare All Request Input Either For Entering DB Or For Other Process Depending On Other Model
        $request->merge(['tags' => explode(',', $request->the_tags), 'partner_sort' => json_encode($partner_sort, JSON_FORCE_OBJECT)]);

        // Update The Post
        $the_post->update($request->except('_token', 'visible', 'main_image', 'the_attachment', 'tags', 'the_tags', 'full_partnership', 'loan'));
        $the_post->update(['email' => $boolenEmail]);
        // Get It's name for make a special attachments folder for
        $postName = $the_post->title;

        // Update The Post Tags
        //$the_post->syncTags($request->tags);

        //dd($request->tags);

        DB::table('taggables')->where('taggable_id', $the_post->id)->delete();
        foreach ($request->tags as $tag) {
            DB::table('taggables')->insert(['tag_id' => $tag, 'taggable_type' => 'App\Models\Post', 'taggable_id' => $the_post->id]);
        }


        // Add The Attachments
        if ($request->has('the_attachment')) {
            foreach ($request->the_attachment as $attachment) {
                Attachment::create(['post_id' => $the_post->id, 'name' => Upload::uploadImage($attachment, "attachments/${postName}", $postName . "_" . rand(0, 100000))]);
            }
        }

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $the_post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $the_post = Post::find($id);
        foreach ($the_post->attachments as $attachment) {

            $the_post_title = $the_post->title;
            $attachment_name = $attachment->name;

            $image = asset("storage/main/attachments/${the_post_title}/${attachment_name}");

            File::delete($image);

            $attachment->delete();
        }


        File::delete($the_post->img_path);


        return $the_post->delete() ? redirect()->route('home') : abort(500);
    }


    public function calculatePrice(Request $request, Post $post, AdPricingService $pricing)
    {
        $request->validate([
            'ad_type_id' => 'required|exists:ad_types,id',
            'duration_value' => 'required|integer|min:1|max:365',
            'duration_unit' => 'required|in:day,week,month',
        ]);

        // إنشاء نسخة مؤقتة من الإعلان للتسعير
        $tempPost = $post->replicate()->fill([
            'ad_type_id' => $request->ad_type_id,
            'category_id' => $post->category_id
        ]);

        $quote = $pricing->quote(
            $tempPost,
            $request->duration_value,
            $request->duration_unit
        );

        return response()->json($quote);
    }

    public function checkout(Request $request, Post $post, AdPricingService $pricing, RajhiGateway $gateway)
    {
        $this->authorize('update', $post);

        $request->validate([
            'ad_type_id' => 'required|exists:ad_types,id',
            'duration_value' => 'required|integer|min:1|max:365',
            'duration_unit' => 'required|in:day,week,month',
        ]);

        // التأكد أن الإعلان قابل للترقية
        $isActive = $post->status === 'active' && (is_null($post->ends_at) || $post->ends_at->gte(now()));
        $canPromote = !$post->is_paid || !$isActive;

        if (!$canPromote) {
            return back()->with('error', 'هذا الإعلان مميز ونشط بالفعل');
        }

        // حساب السعر النهائي
        $tempPost = $post->replicate()->fill(['ad_type_id' => $request->ad_type_id]);
        $quote = $pricing->quote($tempPost, $request->duration_value, $request->duration_unit);

        // تحديث الإعلان مؤقتاً
        $post->update([
            'ad_type_id' => $request->ad_type_id,
            'pricing_snapshot' => $quote,
            'status' => 'pending_payment',
        ]);

        // إنشاء دفعة جديدة
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'gateway' => 'rajhi',
            'amount' => $quote['total'],
            'currency' => config('rajhi.currency', 'SAR'),
            'status' => 'pending',
            'gateway_order_id' => Str::uuid()->toString(),
        ]);
        $payment->payable()->associate($post)->save();

        try {
            // التوجيه لبوابة الراجحي
            $redirectUrl = $gateway->createPayment($payment, [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ], [
                'post_title' => $post->title,
                'post_id' => $post->id,
            ]);

            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            // في حال فشل إنشاء الدفعة، إرجاع الحالة
            $post->update(['status' => 'draft']);
            $payment->update(['status' => 'failed']);

            return back()->with('error', 'حدث خطأ في معالجة الدفع: ' . $e->getMessage());
        }
    }

    public function showFeaturedOffer($id)
    {
        $post = Post::findOrFail($id);
        return view('main.posts.featured_offer', compact('post'));
    }

    public function featuredCheckout($id)
    {
        $post = Post::findOrFail($id);

        // إنشاء دفعة جديدة للإعلان المميز
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'gateway' => 'rajhi',
            'amount' => 149.50, // السعر الإجمالي شامل الضريبة
            'currency' => 'SAR',
            'status' => 'pending',
            'gateway_order_id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'تمييز إعلان - ' . $post->title,
            'payable_type' => 'App\\Models\\Post',
            'payable_id' => $post->id,
        ]);

        // توجيه المستخدم لصفحة الدفع
        return redirect()->route('payments.checkout', $payment->id);
    }

    public function confirmFeatured($id)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'is_featured' => true,
            'featured_until' => now()->addMonths(3), // 3 أشهر من لحظة التفعيل
        ]);

        return redirect()->route('the_posts.show', $post->id)->with('success', 'تم تمييز الإعلان بنجاح لمدة 3 أشهر!');
    }

    public function approveAndCheckout($id)
    {
        $post = Post::findOrFail($id);

        // إنشاء دفعة جديدة للإعلان المميز
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'gateway' => 'rajhi',
            'amount' => 149.50, // السعر الإجمالي شامل الضريبة
            'currency' => 'SAR',
            'status' => 'pending',
            'gateway_order_id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'تمييز إعلان - ' . $post->title,
            'payable_type' => 'App\\Models\\Post',
            'payable_id' => $post->id,
        ]);

        // توجيه المستخدم لصفحة الدفع
        return redirect()->route('payments.checkout', $payment->id);
    }



    /**
     * منع إضافة فرصة إذا لم يكن لدى المستخدم رقم جوال
     */
}
