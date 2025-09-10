@extends('main.layouts.app')
@section('content')
    <div class="form-block my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="border border-radius-medium p-lg-5 p-sm-3 p-2">
                        <h3 class="h3 text-dark-heading mb-3"> إضــــافــــة عـــــرض جـــــديـــــد</h3>

                        
                        <div class="border-top pt-4">
                            <form method="POST" action="{{ route('the_posts.store') }}" novalidate id="post_add_form"
                                enctype="multipart/form-data">
                                @csrf
                                <input type='hidden' name='not_logged_in' value='0' id='not_logged_in'>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label for="opportunityName" class="form-label">اسم الفرصة</label>
                                        <input id="title" type="text"
                                            class="form-control @error('title') is-invalid @enderror" name="title"
                                            value="{{  old('title', isset(session()->get('post')['title']) ? session()->get('post')['title'] : '') }}"
                                            required autocomplete="title" autofocus>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <label for="opportunityCountry" class="form-label text-dark-heading">الدولة</label>
                                        <select onchange="getSubCities(this)" class="form-control">
                                            @foreach ($countries as $country)
                                                @if ($country->id !== 1)
                                                    <option value="{{ $country->id }}" @if(isset(session()->get('post')['area_id']))
                                                        @if(App\Models\Area::whereId(session()->get('post')['area_id'])->exists())
                                                            @if (App\Models\Area::whereId(session()->get('post')['area_id'])->first()->parent_id == $country->id)
                                                    selected @endif @endif @endif>
                                                        {{ $country->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label for="opportunityCity" class="form-label text-dark-heading">المدينة</label>
                                        <select class="area_id form-control @error('area_id') is-invalid @enderror"
                                            name="area_id" id="area_id"></select>
                                        @error('area_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="" class="form-label text-dark-heading">
                                            نوع الفرصة
                                        </label>
                                        <div class="d-flex flex-wrap" id="checkboxs-wrap">
                                            <div
                                                class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                                <div class="card-body p-0">
                                                    <input type="radio" class="btn-check" value="2" name="sort"
                                                        id="radiooption1" {{ old('sort') == '2' ? 'checked' : '' }}
                                                        autocomplete="off">
                                                    <label
                                                        class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                        for="radiooption1">
                                                        فكرة
                                                    </label>
                                                </div>
                                            </div>
                                            <div
                                                class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                                <div class="card-body p-0">
                                                    <input type="radio" class="btn-check" value="1" name="sort"
                                                        id="radiooption2" {{ old('sort') == '1' ? 'checked' : '' }}
                                                        autocomplete="off">
                                                    <label
                                                        class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                        for="radiooption2">
                                                        عمل قائم
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        @error('sort')
                                            <div class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="formGroupExampleInput" class="form-label text-dark-heading">
                                            قطاع الفرصة
                                        </label>

                                        <div class="row p-0 m-0 px-2" id="checkboxs-wrap">

                                            @foreach ($categories as $category)
                                                <div
                                                    class="col-sm-4 px-0 col-lg-3 col-12 card border border-radius-rounded bg-gray-light card-transition m-2">
                                                    <div class="card-body p-0">
                                                        <input type="radio" onclick="getSubCategories(this);" class="btn-check @error('category_id') is-invalid @enderror"
                                                            name="main_category_id" value="{{$category->id}}"
                                                            id="sectionoption{{$category->id}}" autocomplete="off">
                                                        <label
                                                            class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                            for="sectionoption{{$category->id}}">
                                                            {{$category->name}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                        
                                        <!-- رسالة خطأ للفئة الرئيسية -->
                                        @error('category_id')
                                            <div class="invalid-feedback d-block mt-2" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>يجب اختيار قطاع الفرصة أولاً</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="opportunityField" class="form-label text-dark-heading">
                                            <i class="fas fa-arrow-down me-1"></i>
                                            مجال الفرصة التفصيلي
                                            <small class="text-muted d-block">اختر القطاع أولاً لتظهر المجالات المتاحة</small>
                                        </label>
                                        <select class="category_id form-control @error('category_id') is-invalid @enderror"
                                            name="category_id" id="category_id" onchange="changeDefaultPic()" required disabled>
                                            <option value="">اختر القطاع أولاً لإظهار المجالات</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory['id']}}"
                                                    {{ old('category_id') == $subcategory['id'] ? 'selected' : '' }}>
                                                    {{$subcategory->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="PartnerSkills" class="form-label text-dark-heading">
                                            أبحث عن مهارات التالية للشريك: "اذا كنت تبحث عن مهارة معينة للشريك, أو الإبقاء
                                            على خيار "لايوجد" "
                                        </label>
                                        <h1>

                                        </h1>

                                        <select name="the_tags"
                                            class="form-control @error('the_tags') is-invalid @enderror">
                                            <option value="">اختر المهارة المطلوبة</option>
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id}}" 
                                                    {{ old('the_tags') == $tag->id ? 'selected' : '' }}>
                                                    {{ $tag->name->en }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('the_tags')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="formGroupExampleInput" class="form-label text-dark-heading">
                                            أبحث عن
                                        </label>
                                        <div class="d-flex" id="checkboxs-wrap">
                                            <div
                                                class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                                <div class="card-body p-0">
                                                    <input type="radio" class="btn-check" value="on" id="full_partnership"
                                                        name="full_partnership" onchange="togglePartnersSection()"
                                                        {{ old('full_partnership') == 'on' ? 'checked' : '' }}
                                                        autocomplete="off">
                                                    <label
                                                        class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                        for="full_partnership">
                                                        شراكة
                                                    </label>
                                                </div>
                                            </div>
                                            <div
                                                class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                                <div class="card-body p-0">
                                                    <input type="radio" class="btn-check" value="off" id="loan"
                                                        name="full_partnership" onchange="togglePartnersSection()"
                                                        {{ old('full_partnership') == 'off' ? 'checked' : '' }}
                                                        autocomplete="off">
                                                    <label
                                                        class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                                        for="loan">
                                                        تمويل قرض
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <label for="formGroupExampleInput" class="form-label">
                                        أبحث عن اجمالي مبلغ
                                    </label>
                                    <div class="col-lg-9 mb-3">
                                        <input id="price" type="number"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ old('price', isset(session()->get('post')['price']) ? session()->get('post')['price'] : '') }}"
                                            required autocomplete="price" autofocus>
                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <fieldset disabled>
                                            <div class="mb-3">
                                                <input type="text" id="disabledTextInput" class="form-control bg-gold-light"
                                                    placeholder="ريال سعودي">
                                            </div>
                                        </fieldset>

                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <label for="searchPartnership" class="form-label">
                                        نسبة الشراكة ممكن التفاوض فيها حتى
                                    </label>
                                    <div class="col-lg-9 mb-3">
                                        <input id="PercEdit" type="text" placeholder="1.00%"
                                            class="form-control @error('partnership_percentage') is-invalid @enderror"
                                            name="partnership_percentage"
                                            value="{{ old('partnership_percentage', session()->get('post')['partnership_percentage'] ?? 0) }}"
                                            onmousedown="this.value = this.value.replace('%','')"
                                            onblur="javascript:document.getElementById(this.id.substring(0,4)).value = Formatter.decimal(this.value);this.value=Formatter.percentage(Formatter.decimal(this.value))"
                                            required autocomplete="partnership_percentage" autofocus>

                                        <input type="hidden" id="Perc"
                                            onchange="document.getElementById('PercEdit').value = Formatter.percentage(this.value);" />
                                        @error('partnership_percentage')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <fieldset disabled>
                                            <div class="mb-3">
                                                <input type="text" id="disabledTextInput" class="form-control bg-gold-light"
                                                    placeholder="%">
                                            </div>
                                        </fieldset>

                                        @error('partnership_percentage')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="workhours" class="form-label">
                                            عدد ساعات الملتزم بها (اختياريا): "عدد ساعات الملتزم بها بالأسبوع"
                                        </label>
                                        <input id="weeks_hours" type="number"
                                            class="form-control @error('weeks_hours') is-invalid @enderror"
                                            name="weeks_hours"
                                            value="{{ old('weeks_hours', isset(session()->get('post')['weeks_hours']) ? session()->get('post')['weeks_hours'] : '') }}"
                                            autocomplete="weeks_hours" autofocus>
                                        @error('weeks_hours')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3" id="partners_section">
                                        <label for="numPartnershipn" class="form-label">
                                            ابحث عن عدد شركاء بحد أقصى
                                        </label>
                                        <input id="partners_no" type="number" min="1" max="100"
                                            class="form-control @error('partners_no') is-invalid @enderror"
                                            name="partners_no"
                                            value="{{ old('partners_no', isset(session()->get('post')['partners_no']) ? session()->get('post')['partners_no'] : '1') }}"
                                            autocomplete="partners_no" required>

                                        @error('partners_no')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="col-lg-12 mb-3">
                                        <label for="numPartnershipn" class="form-label">
                                            تحدث عن الفرصة: "التحدث عن الفرصة بشكل وافي يسهم في رفع جاذبية الإعلان واعجاب
                                            الشركاء المحتملين!"
                                        </label>
                                        <textarea id="body" class="form-control @error('body') is-invalid @enderror"
                                            name="body" required autocomplete="body" autofocus>{{ old('body', isset(session()->get('post')['body']) ?
        session()->get('post')['body'] : '') }}</textarea>
                                        @error('body')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label for="numPartnershipn" class="form-label">
                                            رقم الجوال للتواصل
                                        </label>
                                        <div class="form-control-plaintext"
                                            style="font-size:1.1rem; color:#333; background:none; border:none; padding-left:0;">
                                            @php
                                                $phone = auth()->user()->phone ?? '';
                                                if ($phone && preg_match('/^(966|20|971|973|974|968|965|962|961|218|212|216|218|249|963|970|972)/', $phone)) {
                                                    if (strpos($phone, '+') !== 0) {
                                                        $phone = '+' . $phone;
                                                    }
                                                }
                                             @endphp
                                            <span
                                                style="direction:ltr; text-align:left; display:inline-block; min-width:120px;">{{ $phone }}</span>
                                        </div>

                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                name="email" onchange="changeValueOfEmail()" checked>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">
                                                إظهار البريد الإلكتروني
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="dropflie-wrap">
                                            <label for="formGroupExampleInput" name="main_image" class="form-label"> أضف
                                                صورة </label>
                                            <!-- Target DOM node -->
                                            <div class="col-md-12 text-center" id="the_icon">
                                                <div class="w-100 d-flex justify-content-center">
                                                    <img src="/storage/main/posts/uploaded.png"
                                                        style="width:120px;height:120px;margin: 16px 43.5%;background-color:white"
                                                        class="the_image_changing"
                                                        onclick="document.getElementById('image').click()"
                                                        alt="Cinque Terre">
                                                </div>
                                                <!--<h5 class="text-center" style="margin-top: -15px;">أضف صورة للفرصة</h5>-->
                                                <label for="partner"><i class="fa fa-info-circle" aria-hidden="true"></i>أضف
                                                    صورة: "رفع صورة الشعار أو صورة خاصّة بالمشروع يميَز إعلانك بين باقي
                                                    الإعلانات! ماعندك صورة, لاتشيل هم"</label>
                                                <input style="display: none;" id="image" type="file" name="main_image"
                                                    accept="image/*" class="@error('main_image') is-invalid @enderror">
                                                @error('main_image')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="aggrements mt-3" style="display: none">
                                        <div class="col-md-12 justify-content-center">
                                            <div class="card">
                                                <h2 class="card-header text-center">{!! $aggrements->title !!}</h2>
                                                <div class="card-body">


                                                    {!! $aggrements->body !!}


                                                </div>

                                                <div class="row card-footer text-right">
                                                    <h5 class="label" style="padding: 15px;">
                                                        <input type="checkbox" id="agree">
                                                        أتعهد بذلك
                                                    </h5>

                                                    <button class="btn main-btn blue-btn p-3 rounded w-100 mb-2"
                                                        id="confirm" disabled>
                                                        تأكيد إضافة الإعلان
                                                    </button>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <!-- رسائل خطأ للمرفقات -->
                                    @if ($errors->has('the_attachment.*'))
                                        <div class="col-lg-12 mb-3">
                                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    @foreach ($errors->get('the_attachment.*') as $message)
                                                        <strong>{{ $message[0] }}</strong><br>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-12 mt-4">
                                        <button type="button" onclick="showAggrements();return false;"
                                            class="btn main-btn blue-btn p-3 rounded w-100 mb-2 send">
                                            إضافة
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript">
        $("#loan").change(function () {
            if ($('#loan').is(':checked') == true) {
                $('#full_partnership').is(':checked') ? $('#PercEdit').prop('disabled', false) : $('#PercEdit').prop('disabled', true);
                $('#PercEdit').val('0.00%')
            } else {
                $('#PercEdit').prop('disabled', false);
                $('#PercEdit').val('0.00%')
            }
        });


        $('#full_partnership').change(function () {
            if ($('#full_partnership').is(':checked') == true) {
                //if($('#loan').is(':checked') && $('#full_partnership').is(':checked')) { $('#PercEdit').prop('disabled', false); } 
                $('#PercEdit').prop('disabled', false)
                $('#PercEdit').val('0.00%')
            } else {
                $('#loan').is(':checked') ? $('#PercEdit').prop('disabled', true) : $('#PercEdit').prop('disabled', false);
                $('#PercEdit').val('0.00%')
            }
        });

        $(document).ready(function () {
            let itemone = {};
            itemone.value = 15;
            getSubCities(itemone);
            let itemtwo = {};
            itemtwo.value = 102;
            getSubCategories(itemtwo);
        });

        function getSubCities(item) {
            axios.get('../public/areas/' + item.value)
                .then((data) => {
                    $('#area_id').empty()
                    for (subcity of data.data) {
                        $('#area_id').append('<option value="' + subcity.id + '">' + subcity.name + '</option>')
                    }
                })
        }

        subCategories = [];

        function changeValueOfEmail() {
            if ($("#flexSwitchCheckChecked").val() == 1) {
                $("#flexSwitchCheckChecked").val(0)
            } else {
                $("#flexSwitchCheckChecked").val(1)
            }
        }
        function getSubCategories(item) {
            // تفعيل حقل الفئة الفرعية
            $('#category_id').prop('disabled', false);
            $('#category_id').html('<option value="">جاري تحميل المجالات...</option>');
            
            axios.get('list_subcategories/' + item.value)
                .then((data) => {
                    subCategories = data.data;
                    changeDefaultPic();
                    $("#flexSwitchCheckChecked").val(1);
                    console.log(386, $("#flexSwitchCheckChecked").val())
                    
                    // تحديث خيارات الفئة الفرعية
                    $('#category_id').empty();
                    $('#category_id').append('<option value="">اختر مجال الفرصة التفصيلي</option>');
                    
                    for (subcity of data.data) {
                        $('#category_id').append('<option value="' + subcity.id + '">' + subcity.name + '</option>');
                    }
                })
                .catch((error) => {
                    console.error('خطأ في تحميل المجالات:', error);
                    $('#category_id').html('<option value="">خطأ في تحميل المجالات</option>');
                });
        }



        /*function getTags(){
            axios.get('tags').then((data) => { 



            var task = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: data.data,
            });

            task.initialize();

            var elt = $("#tag1");
                elt.tagsinput({
                itemValue: "text",
                itemText: "text",
                typeaheadjs: {
                    name: "task",
                    displayKey: "text",
                    source: task.ttAdapter()
                }
            });



             });
        }*/

        function changeDefaultPic() {
            let id = $("#category_id").val();


        }

        function changeImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.the_image_changing').attr('src', e.target.result);
                    $(".the_image_changing").css({ "width": "350px", "height": "350px" });
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#image").change(function () {
            changeImage(this);
        });


    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            $("input[id^='upload_file']").each(function () {
                var id = parseInt(this.id.replace("upload_file", ""));
                $("#upload_file" + id).change(function () {
                    if ($("#upload_file" + id).val() != "") {
                        $("#moreImageUploadLink").show();
                    }
                });
            });
        });

        $(document).ready(function () {
            var upload_number = 2;
            $('#attachMore').click(function () {
                //add more file
                var moreUploadTag = '';
                moreUploadTag += '<div class="element"><label for="upload_file"' + upload_number + '>Upload File ' + upload_number + '</label>';
                moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="the_attachment[]"/>';
                moreUploadTag += ' <a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="return confirm("Are you really want to delete ?")">Delete ' + upload_number + '</a></div>';
                $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').appendTo('#moreImageUpload');
                upload_number++;
            });
        });

        function del_file(eleId) {
            var ele = document.getElementById("delete_file" + eleId);
            ele.parentNode.removeChild(ele);
        }







        //Used by KoTextBox... controls for formatting as numbers, percentages and so on
        var Formatter = (function () {
            var me = {};

            me.percentage = function (val, places) {
                if (typeof places == 'undefined')
                    places = 2;

                var v;
                if (typeof val == 'function')
                    v = val()
                else
                    v = val;

                if (isNaN(v) || v == null || v == 'Infinity')
                    v = '';
                else
                    v = Number(v * 100).toFixed(places) + "%";

                return v;
            };

            me.decimal = function (val) {
                if (typeof val == 'undefined')
                    return '';

                var v;
                if (typeof val == 'function')
                    v = val()
                else
                    v = val;

                if (typeof v != 'undefined')
                    v = v.replace(/[^0-9\.]/gi, '');

                if (v.length < 1)
                    return '';

                return Number(v) * 0.01;
            };

            return me;
        }());

        function swalMessageIfUnauthenticated() {
            Swal.fire({
                icon: 'error',
                position: 'center',
                type: 'error',
                title: "تنبيه",
                html:
                    '<h5>الرجاء تسجيل الدخول أو الإنضمام للموقع</h5> <br/>' +
                    '<btn class="btn btn-info" onclick=$("#post_add_form").submit()>دخول الموقع</btn> ' +
                    '<a class="btn btn-info" onclick="swal.closeModal(); return false;">شكراً ... ربما لاحقاً</a> ',
                showConfirmButton: false,

            })
        }

        function showAggrements() {
            @guest
                $('#not_logged_in').val('1');
                swalMessageIfUnauthenticated();
                return;
            @endguest

                @auth
                    var userVerified = {!! date("Y-m-d", strtotime(auth()->user()->email_verified_at)) !!}
                    if (userVerified == 1968) {

                        swalMessageIfUnauthenticatedOne();
                        return;
                    }
                @endauth


            let allAreFilled = true;
            let missingFields = [];
            
            document.getElementById("post_add_form").querySelectorAll("[required]").forEach(function (i) {
                if (!allAreFilled && missingFields.length > 3) return; // حد أقصى 3 حقول للرسالة
                
                let fieldName = getFieldDisplayName(i.name, i.id);
                
                if (!i.value) {
                    allAreFilled = false;
                    missingFields.push(fieldName);
                }
                
                if (i.type === "checkbox") {
                    let radioValueCheck = false;
                    document.getElementById("post_add_form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                        if (r.checked) radioValueCheck = true;
                    })
                    if (!radioValueCheck) {
                        allAreFilled = false;
                        if (!missingFields.includes(fieldName)) {
                            missingFields.push(fieldName);
                        }
                    }
                }

                if (i.type === "radio") {
                    let radioValueCheck2 = false;
                    document.getElementById("post_add_form").querySelectorAll(`[name=${i.name}]`).forEach(function(r) {
                        if (r.checked) radioValueCheck2 = true;
                    })
                    if (!radioValueCheck2) {
                        allAreFilled = false;
                        if (!missingFields.includes(fieldName)) {
                            missingFields.push(fieldName);
                        }
                    }
                }
            });
            
            console.log(567, $("#flexSwitchCheckChecked").val())
            if (!allAreFilled) {
                let missingFieldsText = missingFields.join('، ');
                Swal.fire({
                    icon: 'warning',
                    title: 'حقول ناقصة',
                    html: `
                        <div class="text-right">
                            <p>يرجى تعبئة الحقول التالية:</p>
                            <ul class="list-unstyled text-danger">
                                ${missingFields.map(field => `<li><i class="fas fa-exclamation-triangle me-1"></i>${field}</li>`).join('')}
                            </ul>
                        </div>
                    `,
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#007bff'
                });
                
                // التركيز على أول حقل ناقص
                let firstMissingField = document.getElementById("post_add_form").querySelector("[required]:invalid, [required][value='']");
                if (firstMissingField) {
                    firstMissingField.focus();
                    firstMissingField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return;
            }



            $('.post-form').css('display', 'none');
            $('.aggrements').css('display', 'block');
            $('.send').css('display', 'none')
        }

        $('#agree').change(function () {
            if ($('#agree').is(':checked') == true) {
                $('#confirm').prop('disabled', false);
            } else {
                $('#confirm').prop('disabled', true);
            }
        });
        function removeEmojis(string) {
            var regex = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
            return string.replace(regex, '');
        }
        $('#confirm').click(function () {
            body_val = $("#body").val();
            body_val = removeEmojis(body_val);
            $("#body").val(body_val);
            console.log($('#post_add_form').serializeArray());
            if (!$("#partners_no").val().length) $("#partners_no").val(0)

            $('#post_add_form').submit(function (e) {
                // e.preventDefault();
            });
        });
        $(document).on("keydown", "form", function (event) {
            // return event.key != "Enter";
        });


        function swalMessageIfUnauthenticatedOne() {
            Swal.fire({
                icon: 'error',
                position: 'center',
                type: 'error',
                title: "تنبيه",
                html:
                    '<h5>الرجاء تفعيل الحساب الخاص  بك</h5> <br/> ' +
                    '<a class="btn btn-info" href="{{ route("verification.notice") }}">تفعيل الحساب</a> ',
                showConfirmButton: false,

            })
        }

        // تحسين مظهر اختيار القطاع
        $(document).ready(function() {
            // إضافة تأثير بصري عند اختيار القطاع
            $('input[name="main_category_id"]').change(function() {
                $('.card-transition').removeClass('border-primary');
                $(this).closest('.card-transition').addClass('border-primary');
                
                // إضافة رسالة توضيحية
                $('#category_id').attr('disabled', false);
                if ($('#category_id option').length <= 1) {
                    $('#category_id').append('<option value="">جاري تحميل المجالات...</option>');
                }
            });
            
            // التحقق من حالة أبحث عن عند تحميل الصفحة
            togglePartnersSection();
        });

        // دالة لتحويل أسماء الحقول إلى أسماء عربية واضحة
        function getFieldDisplayName(fieldName, fieldId) {
            const fieldNames = {
                'title': 'عنوان الفرصة',
                'category_id': 'مجال الفرصة',
                'sort': 'نوع الفرصة (فكرة أم عمل قائم)',
                'price': 'المبلغ المطلوب',
                'partners_no': 'عدد الشركاء',
                'body': 'وصف الفرصة',
                'main_image': 'الصورة الرئيسية',
                'area_id': 'المنطقة',
                'the_tags': 'المهارات المطلوبة',
                'partnership_percentage': 'نسبة الشراكة',
                'weeks_hours': 'ساعات العمل الأسبوعية'
            };
            
            return fieldNames[fieldName] || fieldNames[fieldId] || fieldName || fieldId || 'حقل غير محدد';
        }

        // دالة لإظهار/إخفاء قسم عدد الشركاء حسب نوع البحث
        function togglePartnersSection() {
            const loanRadio = document.getElementById('loan');
            const partnersSection = document.getElementById('partners_section');
            const partnersInput = document.getElementById('partners_no');
            
            if (loanRadio && loanRadio.checked) {
                // إخفاء قسم عدد الشركاء عند اختيار "تمويل قرض"
                partnersSection.style.display = 'none';
                partnersInput.removeAttribute('required');
                partnersInput.value = '0'; // تعيين قيمة افتراضية
                
                // إزالة رسائل الخطأ إن وجدت
                const errorElement = partnersSection.querySelector('.invalid-feedback');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                
                // إضافة تأثير بصري
                partnersSection.classList.add('hidden');
                
                console.log('تم إخفاء قسم عدد الشركاء - تمويل قرض');
            } else {
                // إظهار قسم عدد الشركاء عند اختيار "شراكة"
                partnersSection.style.display = 'block';
                partnersInput.setAttribute('required', 'required');
                if (partnersInput.value === '0' || !partnersInput.value) {
                    partnersInput.value = '1'; // قيمة افتراضية للشراكة
                }
                
                // إظهار رسائل الخطأ إن وجدت
                const errorElement = partnersSection.querySelector('.invalid-feedback');
                if (errorElement) {
                    errorElement.style.display = 'block';
                }
                
                // إزالة تأثير الإخفاء
                partnersSection.classList.remove('hidden');
                
                console.log('تم إظهار قسم عدد الشركاء - شراكة');
            }
        }

    </script>
    
    <style>
        /* تحسين مظهر قطاع الفرصة */
        .card-transition {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .card-transition:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card-transition.border-primary {
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* تحسين مظهر الملصقات */
        .form-label .fas {
            color: #6c757d;
        }
        
        .text-muted {
            font-size: 0.85em;
            font-style: italic;
        }
        
        /* تحسين مظهر رسائل الخطأ */
        .invalid-feedback.d-block {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
            color: #721c24;
        }
        
        /* تحسين مظهر SweetAlert للحقول الناقصة */
        .swal2-popup .text-right {
            text-align: right;
            direction: rtl;
        }
        
        .swal2-popup .list-unstyled li {
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
        }
        
        .swal2-popup .text-danger {
            color: #dc3545 !important;
        }
        
        /* تأثير سلس لإخفاء/إظهار قسم الشركاء */
        #partners_section {
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        #partners_section.hidden {
            opacity: 0;
            max-height: 0;
            margin: 0;
            padding: 0;
        }
    </style>
@endsection