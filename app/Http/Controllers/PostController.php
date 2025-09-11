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
use Illuminate\Support\Facades\Log;
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

            'title',
            'slug',
            'img',
            'price',
            'created_at'
        ];
        $with = [

            'category:id,name,slug,image',
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
        // إذا لم يكن المستخدم مسجل دخول، إعادة توجيه لصفحة تسجيل الدخول
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً لإضافة فرصة جديدة.');
        }
        // إذا لم يكن رقم الجوال موجود، إعادة توجيه لصفحة المعلومات الشخصية
        if (empty($user->phone)) {
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
    public function store(Request $request)
    {
        // الفاليديشن داخل الدالة مباشرة


        $request->validate([
            'main_image' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000',
            'area_id' => 'sometimes|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required',
            'sort' => 'required',
            'partner_sort' => 'in:0,1',
            'the_tags' => 'sometimes',
            'partnership_percentage' => 'sometimes',
            'weeks_hours' => 'sometimes',
            'price' => 'required|numeric',
            'partners_no' => 'required_if:full_partnership,on|min:0|max:100|numeric',
            'body' => 'required',
            'the_attachment.*' => 'required|file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff'
        ], [
            'category_id.required' => 'يجب اختيار فئة للإعلان',
            'category_id.numeric' => 'فئة الإعلان غير صحيحة',
            'title.required' => 'يجب كتابة عنوان الإعلان',
            'sort.required' => 'يجب اختيار نوع الفرصة (فكرة ، عمل قائم )',
            'price.required' => 'يجب تحديد المبلغ المطلوب',
            'price.numeric' => 'المبلغ يجب أن يكون رقماً صحيحاً',
            'partners_no.required_if' => 'يجب تحديد عدد الشركاء عند اختيار الشراكة',
            'partners_no.min' => 'عدد الشركاء يجب أن يكون على الأقل شريك واحد',
            'partners_no.max' => 'عدد الشركاء لا يمكن أن يزيد عن 100 شريك',
            'partners_no.numeric' => 'عدد الشركاء يجب أن يكون رقماً صحيحاً',
            'body.required' => 'يجب كتابة وصف تفصيلي عن الفرصة',
            'main_image.mimes' => 'الصورة يجب أن تكون من نوع: JPEG, JPG, PNG, أو GIF',
            'main_image.max' => 'حجم الصورة يجب أن يكون أقل من 10 ميجابايت',
            'area_id.numeric' => 'المنطقة المختارة غير صحيحة',
            'area_id.sometimes' => 'يجب اختيار منطقة صحيحة',
            'the_attachment.*.required' => 'يجب اختيار ملف للمرفق',
            'the_attachment.*.file' => 'المرفق يجب أن يكون ملفاً صحيحاً',
            'the_attachment.*.mimes' => 'المرفق يجب أن يكون من الأنواع المسموحة: Excel, Word, PDF, أو صورة',
            'partner_sort.in' => 'اختيار نوع الشريك غير صحيح',
            'partnership_percentage.numeric' => 'نسبة الشراكة يجب أن تكون رقماً',
            'the_tags.sometimes' => 'الكلمات المفتاحية يجب أن تكون نصاً صحيحاً',
            'weeks_hours.sometimes' => 'ساعات العمل يجب أن تكون رقماً صحيحاً',
        ]);
        /*----- in case of user not authenticated -----*/
        // if ($request->not_logged_in) {
        //     if ($request->has('main_image'))
        //         session()->put('image', Upload::uploadImage($request->main_image, 'temp', $request->title));
        //     session()->put('post', $request->except('main_image'));
        //     return redirect()->route('login');
        // }
        /*----- in case of user not authenticated -----*/
        //   
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


        // تجهيز رقم الجوال مع علامة + إذا كان يبدأ بمفتاح دولة
        $userPhone = auth()->user()->phone ?? '';
        if ($userPhone && preg_match('/^(966|20|971|973|974|968|965|962|961|218|212|216|218|249|963|970|972)/', $userPhone)) {
            if (strpos($userPhone, '+') !== 0) {
                $userPhone = '+' . $userPhone;
            }
        }
        // الهاتف يؤخذ من حساب المستخدم وليس من النموذج، لذلك لا داعي لجعله required في الفاليديشن
        $request->merge([
            'code' => rand(10, 50000),
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($request->title),
            'tags' => explode(',', $request->the_tags),
            'partner_sort' => json_encode($partner_sort, JSON_FORCE_OBJECT),
            'phone' => $userPhone
        ]);

        // Create Post
        $the_post = Post::create($request->except('_token', 'visible', 'main_image', 'the_attachment', 'tags', 'the_tags', 'full_partnership', 'loan', 'not_logged_in'));
        $the_post->update(['email' => $boolenEmail]);
        // Get It's name for make a special attachments folder for
        $postName = $the_post->title;

        // Add The Post Tags
        foreach ($request->tags as $tag) {
            if (!empty($tag) && is_numeric($tag)) {
                DB::table('taggables')->insert([
                    'tag_id' => $tag,
                    'taggable_type' => 'App\Post',
                    'taggable_id' => $the_post->id
                ]);
            }
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
        $the_post = Post::where('id', $id)->orWhere('slug', $id)->with('category:id,name,slug,image,category_id')->first();
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

        // التحقق من وجود tags قبل الوصول إليها
        $tags = [];
        if ($related->isNotEmpty()) {
            $tags = DB::table('tags')->where('id', $related[0]->tag_id)->get();
        }

        $post = $the_post;

        return view('main.posts.show', ["post" => $the_post, "tags" => $tags]);

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

        // التحقق من وجود tags قبل الوصول إليها
        $postTags = [];
        if ($related->isNotEmpty()) {
            $postTags = DB::table('tags')->where('id', $related[0]->tag_id)->get();
        }

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
            'theTags' => $postTags->isNotEmpty() ? $postTags[0] : null
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
            'amount' => env('FEATURED_POST_PRICE', 149.50), // السعر الإجمالي شامل الضريبة
            'currency' => 'SAR',
            'status' => 'pending',
            'gateway_order_id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'تمييز إعلان - ' . $post->title,
            'payable_type' => 'App\\Models\\Post',
            'payable_id' => $post->id,
        ]);

        // إرسال بيانات الدفع إلى بوابة الراجحي
        $basURL = "https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm";
        $headers = [
            'Content-type: application/json',
        ];
        $baseUrl = url('/');
        $response_url = $baseUrl . "/api/payment-status?payment_id=" . $payment->id;
        $error_url = $baseUrl . "/api/error?payment_id=" . $payment->id;
        $trackId = uniqid();
        $amount = round($payment->amount, 1);
        $obj = [
            [
                "amt" => $amount,
                "action" => "1",
                "password" => 'kf6CJ@R12@V7f!i',
                "id" => '2iZubJ0EJ9l00Ko',
                "currencyCode" => "682",
                "trackId" => "$trackId",
                "responseURL" => $response_url,
                "errorURL" => $error_url,
                "langid" => "ar",
            ]
        ];
        $order = json_encode($obj);
        // استخدم نفس دوال التشفير الموجودة في PageController
        $code = app(\App\Http\Controllers\PageController::class)->encryption($order, '20204458918420204458918420204458');
        $tranData = [
            [
                "id" => '2iZubJ0EJ9l00Ko',
                "trandata" => $code,
                "responseURL" => $response_url,
                "errorURL" => $error_url,
                "langid" => "ar",
            ]
        ];
        Log::info('Rajhi Payment Request', [
            'tranData' => $tranData,
            'response_url' => $response_url,
            'error_url' => $error_url,
        ]);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $basURL,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($tranData),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            \Log::error('Rajhi Payment CURL Error', [
                'error' => $err,
                'payment_id' => $payment->id,
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
            $payment->update(['status' => 'failed']);
            return back()->with('error', 'خطأ في الاتصال ببوابة الدفع');
        } else {
            $result = json_decode($response, true);
            if (isset($result[0]['status']) && $result[0]['status'] == '1') {
                $payment_id = substr($result[0]['result'], 0, 18);
                $payment->update([
                    'gateway_order_id' => $payment_id,
                    'status' => 'pending',
                ]);
                $url = 'https://digitalpayments.alrajhibank.com.sa/pg/paymentpage.htm?PaymentID=' . $payment_id;
                return redirect()->to($url);
            } else {
                \Log::error('Rajhi Payment Response Error', [
                    'response' => $response,
                    'decoded' => $result,
                    'response_url' => $response_url,
                    'error_url' => $error_url,
                    'payment_id' => $payment->id,
                    'post_id' => $post->id,
                    'user_id' => auth()->id(),
                ]);
                $payment->update(['status' => 'failed']);
                return back()->with('error', 'فشل إنشاء عملية الدفع');
            }
        }
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
            'amount' => env('FEATURED_POST_PRICE', 149.50), // السعر الإجمالي شامل الضريبة
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
