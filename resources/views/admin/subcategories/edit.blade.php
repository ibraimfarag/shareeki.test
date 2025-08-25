@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header text-center">التعديل على القطاع {{ $subcategory->name }}</h2>

                <div class="card-body">
                    <form method="POST" id="form" action="{{ route('subcategories.update' , [$category->slug , $subcategory->slug]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        


                        <div class="col-md-12" id="the_icon">
                            <img src="{{ $subcategory->img_path }}" style="width:60px;height:60px;margin: 16px 47%;background-color: black;" class="rounded-circle" onclick="document.getElementById('image').click()"
                            alt="Cinque Terre">
                            <h5 class="text-center">عدل الصورة من هنا</h5>
                            <input hidden  id="image" type="file" name="main_image" accept="image/*">
                        </div>


                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">الإسم</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $subcategory->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="description" class="col-md-2 col-form-label text-md-right">الوصف</label>

                            <div class="col-md-10">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="10">{{ $subcategory->description }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="visible" class="col-md-2 col-form-label text-md-right">ظهور</label>

                            <div class="col-md-10">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="visible" id="inlineRadio1" value="1" @if($subcategory->visible == 1) checked  @endif>
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="visible" id="inlineRadio2" value="0" @if($subcategory->visible == 0) checked  @endif>
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>

                                @error('visible')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary col-md-12">
                                    تعديل
                                </button>
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
    <script>
    $(document).on("submit", "form", function(e)
        {
        // window.localStorage.setItem('link',$(this).attr('action'))
       //e.preventDefault();
            // $.ajax({
            //     url: $(this).attr("action"),
            //     type: $(this).attr("method"),
            //     dataType: "JSON",
            //     data: new FormData(this),
            //     processData: false,
            //     contentType: false,
            //     success: function (data, status)
            //     {
            //         console.log()
            //         window.location.href = window.localStorage.getItem('link')
            //     },
            //     error: function (xhr, desc, err)
            //     {
            //         console.log('e', this)
        
            //     }
            // });        
        
        })
         function changeImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('.rounded-circle').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#image").change(function() {
            changeImage(this);
        });
    </script>
@endsection