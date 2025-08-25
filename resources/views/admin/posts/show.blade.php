@extends('admin.layouts.app')


@section('header')
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" integrity="sha512-wu4jn1tktzX0SHl5qNLDtx1uRPSj+pm9dDgqsrYUS16AqwzfdEmh1JR8IQL7h+phL/EAHpbBkISl5HXiZqxBlQ==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />


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
--}}




@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header text-center">     {{ $post->title }}</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('posts.update', $post->slug) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                    
                     
                        <div class="form-group row">
                            <label for="title" class="col-md-3 col-form-label text-md-right">إسم الفرصة</label>

                            <div class="col-md-9">
                                <input id="title" type="text" readonly class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $post->title }}" required autocomplete="title" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="countries" class="col-md-3 col-form-label text-md-right">الفرصة التجارية موجودة في دولة</label>
                        
                            <div class="col-md-9">
                                

                                <select readonly disabled class="form-control">

                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"  @if($theCountry == $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach

                                </select>

                               

                                
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="cities" class="col-md-3 col-form-label text-md-right">المدينة</label>
                        
                            <div class="col-md-9">
                                

                                <select readonly disabled class="area_id form-control" name="area_id" id="area_id"></select>
                                

                                </select>

                               

                                @error('area_id')
                                    <span class="invalid-feedback" country="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        
                        <div class="form-group row">
                            <label for="subcategories" class="col-md-3 col-form-label text-md-right">قطاع الفرصة</label>
                        
                            <div class="col-md-9">
                                <select  readonly disabled class="form-control">

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"  @if($theCategory == $category->id) selected @endif>{{ $category->name }}</option>

                                    @endforeach

                                </select>
                            </div>
                        </div>


                        


                        <div class="form-group row">
                            <label for="sort" class="col-md-3 col-form-label text-md-right">نوع الفرصة</label>

                            <div class="col-md-9">
                                
                                <select name="sort" readonly disabled id="sort" class="form-control @error('sort') is-invalid @enderror">
                                    <option>فكرة</option>
                                    <option>عمل قائم</option>
                                </select>

                                @error('sort')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="partner_sort" class="col-md-3 col-form-label text-md-right">أبحث عن</label>

                            <div class="col-md-9 text-right">
                                
                                {{--<select name="partner_sort" readonly disabled id="partner_sort" class="form-control @error('partner_sort') is-invalid @enderror">
                                    <option>تمويل قرض</option>
                                    <option>شراكة عائدات فقط</option>
                                    <option>شراكة كاملة</option>
                                </select>--}}

                                
                                {{--<input type="checkbox"  readonly disabled name="partner_sort[]" />
                                &nbsp;
                                شراكة عائدات فقط
                                &nbsp;--}}


                                <input type="checkbox" readonly disabled id="full_partnership" name="full_partnership" @if(isset($post->partner_sort[0])) checked @endif />
                                &nbsp;
                                شراكة كاملة
                                &nbsp;



                                <input type="checkbox" readonly disabled id="loan" name="loan" @if(isset($post->partner_sort[1])) checked @endif />
                                &nbsp;
                                تمويل قرض
                                &nbsp;



                              
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="partnership_percentage" class="col-md-3 col-form-label text-md-right">نسبة الشراكة ممكن التفاوض فيها حتى</label>

                            <div class="col-md-9">
                                
                                 <input readonly disabled id="PercEdit" @if(key($post->partner_sort) == 1) disabled @endif type="text" placeholder="1.00%" value="{{ $post->partnership_percentage }}" class="form-control @error('partnership_percentage') is-invalid @enderror" name="partnership_percentage" value="{{ old('partnership_percentage') }}"
                                 onmousedown="this.value = this.value.replace('%','')" onblur="javascript:document.getElementById(this.id.substring(0,4)).value = Formatter.decimal(this.value);this.value=Formatter.percentage(Formatter.decimal(this.value))"
                                 required autocomplete="partnership_percentage" autofocus>

                                 <input readonly disabled type="hidden" id="Perc" onchange="document.getElementById('PercEdit').value = Formatter.percentage(this.value);" />

                                @error('partnership_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="weeks_hours" class="col-md-3 col-form-label text-md-right">عدد ساعات العمل الواجب الإلتزام بها بالأسبوع</label>

                            <div class="col-md-9">
                                <input id="weeks_hours" readonly disabled type="number" class="form-control @error('weeks_hours') is-invalid @enderror" name="weeks_hours" value="{{ $post->weeks_hours }}" required autocomplete="weeks_hours" autofocus>

                                @error('weeks_hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="price" class="col-md-3 col-form-label text-md-right">أبحث عن اجمالي مبلغ</label>

                            <div class="col-md-9">
                                <input id="price" readonly disabled type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $post->price }}" required autocomplete="price" autofocus>

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                       


                        <div class="form-group row">
                            <label for="partners_no" class="col-md-3 col-form-label text-md-right">ابحث عن عدد شركاء بحد أقصى</label>

                            <div class="col-md-9">
                                <input id="partners_no" readonly disabled type="number" class="form-control @error('partners_no') is-invalid @enderror" name="partners_no" value="{{ $post->partners_no }}" required autocomplete="partners_no" autofocus>

                                @error('partners_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="visible" class="col-md-3 col-form-label text-md-right">أبحث عن مهارات التالية للشريك</label>

                            <div class="col-md-9">
                                <select readonly disabled name="the_tags" class="form-control">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag['id'] }}">{{ $tag['text'] }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="body" class="col-md-3 col-form-label text-md-right">تحدث عن الفرصة</label>

                            <div class="col-md-9">
                                <textarea name="body" readonly disabled id="body" class="form-control @error('body') is-invalid @enderror textarea" cols="30" rows="10">{{ $post->body }}</textarea>


                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right">رقم الجوال للتواصل</label>
                        
                            <div class="col-md-9">
                                <input type="text" readonly disabled id="phone"  class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $post->phone }}" required autocomplete="phone">
                        
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right">البريد الالكتروني</label>
                        
                            <div class="col-md-9">
                                <input type="text" readonly disabled id="phone"  class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $post->user->email }}" required autocomplete="phone">
                        
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                    <div class="orm-group row" style="text-align:center;">
                         <div class="">
                            <img style="width:300px;"
                               src="{{ $post->img != null ? $post->img_path : $post->category->img_path ?? '' }}"
                               class="img-fluid" alt="...">
                         </div>
                     </div>
                     
                    </form>
                </div>
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


        $(document).ready(function () {
            let item = {};
            item.value = '{!! $theCountry !!}';
            getSubCities(item);

            let itemone = {};
            itemone.value = '{!! $theCategory !!}';
            getSubCategories(itemone);
        });


      
        function getSubCities(item){
            axios.get('../../../areas/'+item.value)
                .then((data) => {
                $('#area_id').empty()
                for(subcity of data.data){
                    if(subcity.id == {!! $theCity->id !!}){
                        $('#area_id').append('<option value="'+subcity.id+'" selected>'+subcity.name+'</option>')
                    }else{
                        $('#area_id').append('<option value="'+subcity.id+'">'+subcity.name+'</option>')
                    }
                }  
            })
        }


        function getSubCategories(itemone){
            axios.get('../../../list_subcategories/'+itemone.value)
                .then((data) => {
                $('#category_id').empty()
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


        function getTags(){
        axios.get('../../../tags').then((data) => { 



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
                $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
                upload_number++;
            });
        });
        
        function del_file(eleId, allow = null) {
            var ele = document.getElementById("delete_file" + eleId);
            ele.parentNode.removeChild(ele);

            if(allow == 1){ axios.get(`../../../admin/delete_attachment/${eleId}`).then((data) => { alert('done')  }); }
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