@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">رسائل تواصل معنا</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">الإسم</label>
                        <div class="col-md-10">{{ $contact->name }}</div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">الجوال</label>
                        <div class="col-md-10">{{ $contact->mobile }}</div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">البريد الإلكتروني</label>
                        <div class="col-md-10">{{ $contact->email }}</div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">محتوى الرسالة</label>
                        <div class="col-md-10">{!! $contact->body !!}</div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#form">
                        كتابة رد على الرسالة
                        </button>  
                    </div>

                    <div class="modal fade bs-example-modal-lg" id="form" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">إرسال رسالة</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <form>
                                    <div class="modal-body">
        
                                        <input type="hidden" name="name" id="name" value="{{ $contact->name }}">
                                        <input type="hidden" name="email" id="email" value="{{ $contact->email }}">


                                        <div class="col-md-12" id="the_icon">
                                            <img src="{{ asset('adminpanel/img/upload.png') }}" style="width:60px;height:60px;margin: 16px 47%;background-color: black;" class="changing-image" onclick="document.getElementById('image').click()" alt="Cinque Terre">
                                            <h5 class="text-center">إرفع صورة من هنا</h5>
                                            <input  style="display: none;"  id="image" type="file" name="main_image">
                                        </div>

                                        <div class="form-group">
                                            <label for="message">العنوان</label>
                                            <input type="text" class="form-control" name="title" id="title">
                                        </div>

                                        

                                        <div class="form-group">
                                        <label for="message">محتوى الرسالة</label>
                                        <textarea name="message" id="the_message" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                    
                                    
                                    </div>
                                    <div class="modal-footer border-top-0 d-flex justify-content-center">
                                        <button class="btn btn-success" id="reply">إرسال</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script>

        window.form_data = new FormData();

        $(document).on('change','#image',function(e){

        let file_data = $('#image').prop('files')[0];
        form_data.append('file', file_data);


        });
        
        $(document).on("click", "#reply", function(e){

            e.preventDefault();

            var ed = tinyMCE.get('the_message');


            form_data.append('name', '{!! auth()->user()->user_name !!}')
            form_data.append('mobile', '{!! $settings->telephone ?? '01055445544'  !!}')
            form_data.append('email', '{!! $settings->email ?? 'a@a.com'  !!}')
            form_data.append('title', $('#title').val())
            form_data.append('body', ed.getContent())
            form_data.append('receiver_email', $('#email').val())
            form_data.append('receiver_name', $('#name').val())
        

            axios.post('../admin/contacts', form_data)
            .then((response) => {
            alert('تم الإرسال');
            window.location.reload();
                
            
                //console.log(response);
            }).catch((error) => {
                if(error.response.data.errors.message){
                    $('.message-contact-error').append('<strong>'+error.response.data.errors.message+'</strong>');
                    $('.message').addClass('is-invalid')
                }
            })

        });


        function changeImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                $('.changing-image').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#image").change(function() {
            changeImage(this);
        });
        

    </script>
@endsection