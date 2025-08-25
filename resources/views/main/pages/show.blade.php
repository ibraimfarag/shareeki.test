@extends('main.layouts.app')
@section('content')
    <div class="show-ad-content" style="">
        <div class="layer">
            <div class="container text-right">
                {!! $the_page->body !!}
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $('#commission_value').keyup(function() {
            $('.the_result').empty();
            $('.the_result').append($("#commission_value").val() * {!! $settings->commission_percentage !!}/100)
        });


        $('#the_partner_btn').click(function() {
            $('#partner').css('display', 'block');
            $('#chance').css('display', 'none');
        });

        $('#the_chance_btn').click(function() {
            $('#partner').css('display', 'none');
            $('#chance').css('display', 'block');
        });
    </script>
@endsection