@extends('layouts.app')

<style>
    #content{
        background-color: white;
    }

    #sections{
        width: 100%;
        height: 100%;
    }

</style>

@section('content')

<div id="content">
    <div id="sections">

            <form action="/coupons/{{$coupon->id}}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PATCH')

                <div class="formContent p-5"> 
                    
                    <div class="row">

                        <div class="col">
                            <label for="name">Naziv kupona</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                            name="name" value="{{ $coupon->name }}"  autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    
                    
                        <div class="col">
                            <label for="price">Popust</label>

                            <div class="input-group">
                                <input type="text" 
                                class="form-control form-control-lg @error('discount') is-invalid @enderror" placeholder="0" 
                                aria-label="discount-input" aria-describedby="discount-currency"
                                name="discount" value="{{ $coupon->discount }}"  autocomplete="discount" autofocus
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text" id="discount-currency">%</span>
                                </div>
        
                                @error('discount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
        
                </div>

                <div class="row pl-3 pr-3 mt-2">
                        <label for="name">Kod</label>
                        <input id="code" type="text" class="form-control form-control-lg @error('code') is-invalid @enderror" 
                        name="code" value="{{ $coupon->code }}"  autocomplete="code" autofocus>

                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>

                <div class="row justify-content-center mt-2">
                    <div class="row justify-content-end pt-4">
                        <button type="submit" class="btn btn-primary">Spasi izmjene</button>
                    </div>
                </div>

            </form>


            <form action="/coupons/{{$coupon->id}}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('DELETE')
                <div class="row justify-content-center mt-2">

                    <div class="row justify-content-end pt-4">
                        <button type="submit" class="btn btn-danger">Obrisi kupon</button>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection
