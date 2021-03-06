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
            <form action="/courses/{{$course->id}}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PATCH')

                <div class="formContent p-5">
                    
                    <div class="row">

                        <div class="col">
                            <label for="name">Ime Kursa</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                            name="name" value="{{ $course->name }}"  autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    
                    
                        <div class="col">
                            <label for="price">Cijena Kursa</label>

                            <div class="input-group">
                                <input type="text" 
                                class="form-control form-control-lg @error('price') is-invalid @enderror" placeholder="00.00" 
                                aria-label="price-input" aria-describedby="price-currency"
                                name="price" value="{{ $course->price }}"  autocomplete="price" autofocus
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text" id="price-currency">KM</span>
                                </div>
        
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
        
                </div>

                <div class="row justify-content-center mt-5">
                    <div class="row justify-content-end pt-4">
                        <button type="submit" class="btn btn-primary">Spasi izmjene</button>
                    </div>
                </div>

            </form>


            <form action="/courses/{{$course->id}}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('DELETE')
                <div class="row justify-content-center mt-2">

                    <div class="row justify-content-end pt-4">
                        <button type="submit" class="btn btn-danger">Obrisi kurs</button>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection
