@if (auth()->user()->allow_recommendation_page == 0)
    @php $user = auth()->user(); $user->update(['allow_recommendation_page' => 1]); @endphp

   {{-- <div class="modal hide fade" id="myModal">

        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>ســـــاعــــدنـــــا نـــــعـــــرف رغــــبـــتـــك</h3>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
            <a href="#" class="btn">Close</a>
        </div>

    </div>
--}}
    
    
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ســـــاعــــدنـــــا نـــــعـــــرف رغــــبـــتـــك</h5>
        
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('update_suggestions') }}">
                @csrf
                @method('PATCH')
        
                <div class="form-group row">
                    <label for="city" class="col-md-2 col-form-label text-md-right">مدينة السكن</label>
                
                    <div class="col-md-10">
                        <input type="text" id="city"  class="form-control @error('city') is-invalid @enderror" name="city" value="{{ auth()->user()->city }}" required autocomplete="city">
                
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
        
                <div class="form-group row">
                    <label for="birth_date" class="col-md-2 col-form-label text-md-right">تاريخ الميلاد</label>
                
                    <div class="col-md-10">
                        <input type="date" id="birth_date"  class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ auth()->user()->birth_date }}" required autocomplete="birth_date">
                
                        @error('birth_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
        
                <div class="form-group row">
                    <label for="mobile" class="col-md-2 col-form-label text-md-right">الجوال</label>
                
                    <div class="col-md-10">
                        <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"  value="{{ auth()->user()->mobile }}" required autocomplete="mobile">
                
                        @error('mobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
                <div class="form-group row">
                    <label for="max_budget" class="col-md-2 col-form-label text-md-right">الميزانية القصوى للإستثمار</label>
                
                    <div class="col-md-10">
                        <input type="number" id="max_budget"  class="form-control @error('max_budget') is-invalid @enderror" name="max_budget" value="{{ auth()->user()->max_budget }}" required autocomplete="max_budget">
                
                        @error('max_budget')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
                <button type="submit" class="btn btn-primary col-md-12">
                    إرســـــــــــــــــــال
                </button>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary col-md-12" data-dismiss="modal">إغــــــــــــلاق الــــصــــفـــحـــــة</button>
        </div>
      </div>
    </div>
  </div>

    @endif