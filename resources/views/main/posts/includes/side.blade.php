<div class="search-form">

    <div class="form-group row">
        <div class="col-md-12">
            <div class="btn btn-primary col-md-12" id="reset" onclick="reset();return false;">الغاء تصفية البحث</div>
        </div>
    </div>

    <div class="accordion" id="accordionExample">

    </div>

    <div class="card">
      <div class="card-header" id="headingTwo">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-right collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            نـــوع  الـــــفـــــرصـــــة
          </button>
        </h2>
      </div>
      <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
        <div class="card-body">
            <div class="">
                <div class="col-md-12">
                    {{--<select onchange="search()" name="sort" id="sort" class="form-control @error('sort') is-invalid @enderror">
                        <option selected disabled>نوع الإستثمار</option>
                        <option>فكرة</option>
                        <option>عمل قائم</option>
                    </select>--}}
        
                    <div class="row">
                        <input type="checkbox" name="sort[]" value="0" onclick="search()">
                        <label for="idea" style="margin-top: -3px;"> &nbsp; فكرة</label>
                    </div>
                  
                    <div class="row">
                        <input type="checkbox" name="sort[]" value="1" onclick="search()">
                        <label for="work" style="margin-top: -3px;"> &nbsp; عمل قائم</label>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-header" id="headingFour">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-right collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            القطاعات الإستثمارية
          </button>
        </h2>
      </div>
      
      <div id="collapseFour" class="collapse show" aria-labelledby="headingFour" data-parent="#accordionExample">
        <div class="card-body">

            <div class="">

                <div class="col-md-12">
                      {{--<select onchange="search()" class="category_id form-control" name="category_id" id="category_id">
                          <option selected disabled>المجالات الإستثمارية</option>

                      </select>--}}

                      @foreach ($categories as $category)

                      <div class="row">
                          <input type="checkbox" name="main_category[]" value="{{ $category->id }}" onclick="search()">
                          <label for="work" style="margin-top: -3px;"> &nbsp; {{ $category->name }}</label>
                      </div>

                      @endforeach
        
                </div>
            </div>

        </div>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-md-12">
        <input onchange="search()" id="price" type="number" placeholder="المبلغ المطلوب" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="price" >
      </div>
    </div>

    {{--<div class="form-group row">
      <div class="col-md-12">
        <input onchange="search()" id="partners_no" placeholder="عدد الشركاء" type="number" class="form-control @error('partners_no') is-invalid @enderror" name="partners_no" value="{{ old('partners_no') }}" required autocomplete="partners_no" >
      </div>
    </div>--}}

  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-right" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          الـــدولــــة - الــــمـــديــــنـــة
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">

          <div class="form-group row">    
              <div class="col-md-12">
                  <select id="the_country" onchange="getSubCities(this);" class="form-control">
                      @foreach ($countries as $country)
                          <option value="{{ $country->id }}">{{ $country->name }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
      
          <div class="form-group row">
              <div class="col-md-12">
                  <select onchange="search()" onclick="search()" class="area_id form-control" name="area_id" id="area_id">
                      <option selected disabled>المدينة</option>
                  </select>
              </div>
          </div>
      </div>
    </div>
  </div>


  <div class="form-group row">
    <div class="col-md-12">
      <input onchange="search()" id="weeks_hours" type="number" placeholder="عدد ساعات العمل بالأسبوع" id="weeks_hours" class="form-control @error('weeks_hours') is-invalid @enderror" name="weeks_hours" value="{{ old('weeks_hours') }}" required autocomplete="weeks_hours" >
    </div>
  </div>
   
  {{--<div class="form-group row">
      <div class="col-md-12">
        <input onchange="search()" id="name" placeholder="بحث بالإسم" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" >    
      </div>
  </div>--}}

  <div class="card">
    <div class="card-header" id="headingFive">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-right collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          المهارات المطلوبة
        </button>
      </h2>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
      <div class="card-body">

          <div class="">

              <div class="col-md-12">
                    {{--<select name="the_tags" id="the_tags" onchange="search()" class="form-control">
                        @foreach ($tags as $tag)
                            <option selected disabled>المهارات المطلوبة</option>
                            <option value="{{ $tag['id'] }}">{{ $tag['text'] }}</option>
                        @endforeach
                    </select>--}}

                    @foreach ($tags as $tag)

                    <div class="row">
                        <input type="checkbox" name="the_tags[]" value="{{ $tag['id'] }}" onclick="search()">
                        <label for="work" style="margin-top: -3px;"> &nbsp; {{ $tag['text'] }}</label>
                    </div>

                    @endforeach
      
              </div>
          </div>

      </div>
    </div>
  </div>
   

  {{--<div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-right collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          المجالات الإستثمارية
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">

          <div class="form-group row">

              <div class="col-md-12">
                  <select onchange="getSubCategories(this)" id="main_category" class="form-control">
                      <option selected disabled>القطاعات الإستثمارية</option>
                      @foreach ($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
      
                  </select>
      
                  @foreach ($categories as $category)
      
                  <div class="row">
                      <input type="checkbox" name="main_category[]" value="{{ $category->id }}" onclick="search()">
                      <label for="work" style="margin-top: -3px;"> &nbsp; {{ $category->name }}</label>
                  </div>
      
                  @endforeach
      
              </div>
          </div>

      </div>
    </div>
  </div>--}}

    <div class="form-group row mb-0">
        <div class="col-md-12">
            <button onclick="search();return false;" class="btn btn-primary col-md-12">
                بــــــــــحــــــــــــث
            </button>
        </div>
    </div>

    <div class="form-group row mt-2 mb-0">
      <div class="col-md-12">
          <button class="btn btn-primary col-md-12">
              <a href="{{ route('the_posts.create') }}" style="color: #fff;">أضـــــــف فـــــرصــــــة !</a>
          </button>
      </div>
  </div>

</div>