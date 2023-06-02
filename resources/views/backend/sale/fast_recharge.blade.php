@extends('backend.layout.main') @section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>Recharge Load Card</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'sale.recharge_load_card_save', 'method' => 'post', 'files' => true]) !!}
                        <div class="row">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">

                                        <label> {{trans('file.Load Card')}} *</label>

                                        <select id="load_card_id_select" name="load_card_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Load Card...">
                                            @foreach($load_cards as $card)
                                                <option value="{{$card->id}}">{{$card->card_no}}</option>
                                            @endforeach
                                        </select> 
                                </div> 
                                <div class="form-group">
                                    <label>{{trans('file.Amount')}} *</label>
                                    <input type="number" name="amount" required class="form-control">
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-4">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@push('scripts') 
@endpush
