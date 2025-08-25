@extends('main.layouts.app')




<style>
    .container{
  margin: 20px;
}

/* autocomplete tagsinput*/
.label-info {
  background-color: #5bc0de;
  display: inline-block;
  padding: 0.2em 0.6em 0.3em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25em;
}

.bootstrap-tagsinput{
    width: 100%;
}
</style>



<style>
    body {
        overflow-y: scroll;
    }

    .data{
        z-index: -1;
        margin: 50px 0 50px 0;
    }

   
</style>





@section('content')
<div class="form-block my-5">
         <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-8">
                  <div class="border border-radius-medium p-lg-5 p-sm-3 p-2">
                    <h3 class="h3 text-dark-heading mb-3">التعديل على الإعلان {{ $post->title }}</h3>
                     @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                    <div class="row border-top pt-4">
                        <form method="POST" action="{{ route('the_posts.update', $post->slug) }}" id="post_add_form" enctype="multipart/form-data">
                        <label hidden>
                                                    @csrf

                        </label>
                        @method('PUT')
                        <div class="row">
                           <div class="col-lg-12 mb-3">
                              <label for="opportunityName" class="form-label">اسم الفرصة</label >
                              <input id="title" type="text" value="{{ $post->title }}"
                              class="form-control @error('title') is-invalid @enderror"
                              name="title" required autocomplete="title" autofocus >

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-6 mb-3">
                              <label for="opportunityCountry" class="form-label text-dark-heading">الدولة</label>
                              <select  onchange="getSubCities(this);" class="form-control">
                                    @foreach ($countries as $country)
                                        @if ($country->id !== 1)
                                            <option value="{{ $country->id }}"  @if($theCountry == $country->id) selected @endif>{{ $country->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                           </div>

                           <div class="col-lg-6 mb-3">
                              <label for="opportunityCity" class="form-label text-dark-heading">المدينة</label>
                              <select class="area_id form-control" name="area_id" id="area_id"></select>
                              </select>
                                @error('area_id')
                                    <span class="invalid-feedback" country="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-12 mb-3">
                              <label for="" class="form-label text-dark-heading">
                                 نوع الفرصة
                              </label>
                              <div class="d-flex flex-wrap" id="checkboxs-wrap">
                                 <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                    <div class="card-body p-0">
                                       <input type="radio" class="btn-check" value="2" name="sort" id="radiooption1" @if($post->sort == 'فكرة') checked @endif
                                          autocomplete="off">
                                       <label
                                          class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                          for="radiooption1">
                                          فكرة
                                       </label>
                                    </div>
                                 </div>
                                 <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                    <div class="card-body p-0">
                                       <input type="radio" class="btn-check" value="1" name="sort" id="radiooption2" @if($post->sort == 'عمل قائم') checked @endif
                                          autocomplete="off">
                                       <label
                                          class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                          for="radiooption2">
                                          عمل قائم
                                       </label>
                                    </div>
                                 </div>

                              </div>
                           </div>
                           
                           <div class="col-lg-12 mb-3">
                              <label for="formGroupExampleInput" class="form-label text-dark-heading">
                                 قطاع الفرصة
                              </label>

                              <div class="row p-0 m-0 px-2" id="checkboxs-wrap">
                                
                                @foreach ($categories as $category)
                                <div class=" col-sm-4 col-lg-3 col-12 card border border-radius-rounded bg-gray-light card-transition me-3 mb-3">
                                    <div class="card-body p-0">
                                       <input type="radio" onclick="getSubCategories(this);" class="btn-check" name="category_id" @if($theCategory == $category->id) checked @endif value="{{$category->id}}"  
                                       @if(\App\Models\Category::whereId(session()->get('post'))->exists()) 
                                            @if (\App\Models\Category::whereId(session()->get('post')['category_id'])->first()->category_id == $category->id)
                                                checked
                                            @endif
                                        @endif
                                       id="sectionoption{{$category->id}}"
                                          autocomplete="off">
                                       <label
                                          class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                          for="sectionoption{{$category->id}}">
                                          {{$category->name}}
                                       </label>
                                    </div>
                                </div>
                                @endforeach
                                 
                              </div>
                           </div>

                           <!--<div class="col-lg-12 mb-3">
                              <label for="opportunityField" class="form-label text-dark-heading">مجال الفرصة</label>
                              <select class="category_id form-control" name="category_id" id="category_id"></select>
                              </select>
                           </div>-->
                           
                           <div class="col-lg-12 mb-3">
                              <label for="opportunityField" class="form-label text-dark-heading">مجال الفرصة</label>
                              <select class="category_id form-control" name="category_id" id="category_id">onchange="getSubCities(this);" name="category_id"
                                    @foreach ($sub_categories as $sub_cat)
                                    <option @if(($theSubCategory->id) == $sub_cat->id) selected @endif value="{{ $sub_cat->category_id }}"  >{{ $sub_cat->name }}</option>
                                    @endforeach
                                </select>
                           </div>
                           

                           <div class="col-lg-12 mb-3">
                              <label for="PartnerSkills" class="form-label text-dark-heading">أبحث عن مهارات
                                 التالية للشريك
                                </label>

                               <select name="the_tags" class="form-control">

                                    @foreach ($tags as $tag)
                                        <option @if($theTags->id == $tag->id) selected @endif value="{{ $tag->id}}">{{ json_decode($tag)->name->en }}</option>
                                    @endforeach
                                </select>
                           </div>

                           <div class="col-lg-12 mb-3">
                              <label for="formGroupExampleInput" class="form-label text-dark-heading">
                                 أبحث عن
                              </label>
                              <div class="d-flex" id="checkboxs-wrap">
                                  
                                    <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                        <div class="card-body p-0">
                                           <input type="radio" class="btn-check" value="on" id="full_partnership" name="full_partnership" @if(isset($post->partner_sort[0])) checked @endif  id="searchoptions1"
                                              >
                                           <label
                                              class="btn btn-checked-rounded f4 card-text text-dark-content mb-0  px-3 py-2"
                                              for="full_partnership">
                                              شراكة
                                           </label>
                                    </div>
                                    </div>
                                    
                                    <div class="card border border-radius-rounded bg-gray-light card-transition me-3">
                                        <div class="card-body p-0">
                                           <input type="radio" class="btn-check"  value="off"  id="loan" name="full_partnership" @if(isset($post->partner_sort[1])) checked @endif
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

                           <div class="col-lg-9 mb-3">
                                <label for="formGroupExampleInput" class="form-label">
                                  أبحث عن اجمالي مبلغ
                                </label>
                                <input id="price" type="number" value="{{ $post->price }}"
                                class="form-control @error('price') is-invalid @enderror"name="price"  required autocomplete="price" autofocus>
                           </div>
                           
                           <div class="col-lg-3 mt-4">
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

                           <div class="col-lg-9 mb-3">
                                <label for="searchPartnership" class="form-label">
                                  نسبة الشراكة ممكن التفاوض فيها حتى
                                </label>
                                <input id="PercEdit"
                               type="text" placeholder="1.00%" value="{{ $post->partnership_percentage }}" class="form-control @error('partnership_percentage') is-invalid @enderror" name="partnership_percentage" value="{{ old('partnership_percentage') }}"
                                 onmousedown="this.value = this.value.replace('%','')" onblur="javascript:document.getElementById(this.id.substring(0,4)).value = Formatter.decimal(this.value);this.value=Formatter.percentage(Formatter.decimal(this.value))"
                                 required autocomplete="partnership_percentage" autofocus>
                                 <input type="hidden" id="Perc" onchange="document.getElementById('PercEdit').value = Formatter.percentage(this.value);" />
                           </div>
                           
                           <div class="col-lg-3 mt-4">
                              <fieldset disabled>
                                 <div class="mb-3">
                                    <input type="text" id="disabledTextInput" class="form-control bg-gold-light"
                                       placeholder="%">
                                 </div>
                              </fieldset>

                                @error('partnership_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-12 mb-3">
                             <label for="workhours" class="form-label">
                                  عدد ساعات العمل الملتزم بها (اختيارية)
                              </label>
                              <input id="weeks_hours" type="number"
                              class="form-control @error('weeks_hours') is-invalid @enderror"
                              name="weeks_hours"
                              value="{{ $post->weeks_hours }}"
                               autocomplete="weeks_hours" autofocus>
                                @error('weeks_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-12 mb-3">
                              <label for="numPartnershipn" class="form-label">
                                 ابحث عن عدد شركاء بحد أقصى
                              </label>
                             <input id="partners_no" type="number" class="form-control @error('partners_no') is-invalid @enderror" name="partners_no" value="{{ $post->partners_no }}"  autocomplete="partners_no" autofocus>

                                @error('partners_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-12 mb-3">
                              <label for="numPartnershipn" class="form-label">
                                 تحدث عن الفرصة
                              </label>
                              <textarea id="body"  class="form-control @error('body') is-invalid @enderror"
                              name="body" required autocomplete="body" autofocus>{{ $post->body }}</textarea>
                               @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>

                           <div class="col-lg-12 mb-3">
                              <label for="numPartnershipn" class="form-label">
                                 رقم الجوال للتواصل
                              </label>
                              <input type="number" id="phone"  class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $post->phone }}" required autocomplete="phone">
                        
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           </div>
                           <div class="col-lg-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" 
                                    name="email" @if($post->email == 1) checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">
                                        إظهار البريد الإلكتروني
                                    </label>
                                </div>
                           </div>

                           <div class="col-lg-12 mb-3">
                            @foreach ($post->attachments as $key=>$value)
                                <dl id="delete_file{{ $value->id }}">
                                    <div class="element">
                                        <label for="attachment{{$key}}">
                                            <a href="{{ $value->name }}" download><h2> مرفق {{ $key }} </h2> </a>        
                                        </label>
                                        <a href='javascript:del_file({{$value->id}} , 1)' style="cursor:pointer;" onclick="return confirm('Are you really want to delete ?')">حذف مرفق {{ $key }}</a>
                                    </div>
                                </dl>
                            @endforeach

                           </div>

                           <div class="col-lg-12 mt-4">
                              <button id="confirm" class="btn main-btn blue-btn p-3 rounded w-100 mb-2">
                                  التعديل على الإعلان
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

    <div class="aggrements" style="display: none">
        <div class="col-md-12 justify-content-center">
            <div class="card">
                
                <h2 class="card-header text-center"> @if(isset($aggrements)) {{ $aggrement->title  }} @endif</h2>
                <div class="card-body">
                    
                    @if(isset($aggrements))
                    {{ $aggrements->body }}
                    @endif
                
                    
                </div>

                <div class="row card-footer text-right">
                    <h5 class="label" style="padding: 15px;">                
                        <input type="checkbox" name="agree" id="agree">
                        أتعهد بذلك
                    </h5>

                    <button class="btn btn-primary col-md-12" id="confirm" disabled>
                        تأكيد إضافة الإعلان
                    </button>
                </div>
            </div>
            

        </div>
    </div>
@endsection


@section('footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>
    <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>

    <script>
        getTags();

        $(document).ready(function () {
            let item = {};
            item.value = '{!! $theCountry !!}';
            getSubCities(item);

            let itemone = {};
            itemone.value = '{!! $theCategory !!}';
            getSubCategories(itemone);
        });

        function getSubCities(item){
            axios.get('../../areas/'+item.value)
                .then((data) => {
                $('#area_id').empty()
                for(subcity of data.data){
                    $('#area_id').append('<option value="'+subcity.id+'">'+subcity.name+'</option>')
                }
            })
        }
        
       

        function getSubCategories(itemone){
            axios.get('../../list_subcategories/'+itemone.value)
                .then((data) => {
                $('#category_id').empty()
                $("#flexSwitchCheckChecked").val(1);
                for(subcategory of data.data){
                    if(subcategory.id == {!! $theSubCategory->id !!}){
                        $('#category_id').append('<option value="'+subcategory.id+'" selected>'+subcategory.name+'</option>')
                    }else{
                        $('#category_id').append('<option value="'+subcategory.id+'">'+subcategory.name+'</option>')
                    }
                }  
            })
        }

        function changeImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                $('.the_image_changing').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#image").change(function() {
            changeImage(this);
        });
              
        function changeValueOfEmail(){
           if($("#flexSwitchCheckChecked").val() == 1){
               $("#flexSwitchCheckChecked").val(1)
           }else{
               $("#flexSwitchCheckChecked").val(0)
           }
       }

        function getTags(){
        axios.get('../../tags').then((data) => { 
        var task = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: data.data,
        });

        task.initialize();

        var elt = $("#tag1");
            elt.tagsinput({
            itemValue: "id",
            itemText: "text",
            typeaheadjs: {
                name: "task",
                displayKey: "text",
                source: task.ttAdapter()
            }
        });


        /*@php foreach($tags as $t) {   @endphp

            //insert data to input in load page
            elt.tagsinput("add", {
            value: "<?=$t['id']?>",
            text: "<?=$t['text']?>",
            });

          

        @php } @endphp*/

         });
    }

    </script>
    
    <script type="text/javascript">
        $('#loan').change(function(){
            if ($('#loan').is(':checked') == true){
                $('#full_partnership').is(':checked') ? $('#PercEdit').prop('disabled', false) : $('#PercEdit').prop('disabled', true);
                $('#PercEdit').val('0.00%')
            }else{
                $('#PercEdit').prop('disabled', false);
                $('#PercEdit').val('0.00%')
            }
        });

        $('#full_partnership').change(function() {
            if ($('#full_partnership').is(':checked') == true){
                $('#loan').is(':checked') ? $('#PercEdit').prop('disabled', true) : $('#PercEdit').prop('disabled', false);
                $('#PercEdit').val('0.00%')
            }else{
                $('#loan').is(':checked') ? $('#PercEdit').prop('disabled', true) : $('#PercEdit').prop('disabled', false);
                $('#PercEdit').val('0.00%')
            }
        });

        $("#full_partnership").click(function(){console.log($(this)[0].checked);});
        
        $("#loan").click(function(){console.log($(this)[0].checked);});
        
        function removeEmojis (string) {
            var regex = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
            return string.replace(regex, '');
        }
        
        $('#confirm').click(function () {
            console.log($('#post_add_form').serializeArray());
            body_val = $("#body").val();
            body_val = removeEmojis(body_val);
            $("#body").val(body_val);
            if(!$("#partners_no").val().length) $("#partners_no").val(0)
          $('#post_add_form').submit(function(e){
                // e.preventDefault();
            });
        });
        
        $(document).ready(function() {
            $("input[id^='upload_file']").each(function() {
                var id = parseInt(this.id.replace("upload_file", ""));
                $("#upload_file" + id).change(function() {
                    if ($("#upload_file" + id).val() != "") {
                        $("#moreImageUploadLink").show();
                    }
                });
            });
        });
        
        $(document).ready(function() {
            var upload_number = 2;
            $('#attachMore').click(function() {
                //add more file
                var moreUploadTag = '';
                moreUploadTag += '<div class="element"><label for="upload_file"' + upload_number + '>Upload File ' + upload_number + '</label>';
                moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="the_attachment[]"/>';
                moreUploadTag += ' <a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="return confirm("Are you really want to delete ?")">Delete ' + upload_number + '</a></div>';
                $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').appendTo('#moreImageUpload');
                upload_number++;
            });
        });
        
        function del_file(eleId, allow = null) {
            var ele = document.getElementById("delete_file" + eleId);
            ele.parentNode.removeChild(ele);

            if(allow == 1){ axios.get(`../../delete_the_attachment/${eleId}`).then((data) => { alert('done')  }); }
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
    </script>
@endsection