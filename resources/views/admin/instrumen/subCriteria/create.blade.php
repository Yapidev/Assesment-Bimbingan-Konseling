@extends("layouts.admin")
@section("title")
    Sub Kriteria
@endsection
@section("content")
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Sub Kriteria</h4>
            <p class="mb-0">Form untuk menambahkan Sub Kriteria pada instrumen</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
     <a href="{{route('subCriteria.index',$instrumen_id)}}" class="btn btn-primary">Kembali</a>
    </div>
</div>

<div class="">
    <form action="{{route('subCriteria.store', '')}}/{{$instrumen_id}}" method="post">
        @csrf
        <div class="d-flex flex-column mt-3">
            <label for="text" class="form-label">Nama</label>
            <input type="text" name="text" id="text" class="form-control" value="{{ old('text') }}">
            @error('text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex flex-column mt-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex flex-column mt-3">
            <label for="solutions" class="form-label">Solusi</label>
            <textarea name="solutions" id="solutions" class="form-control">{{ old('solutions') }}</textarea>
            @error('text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-end mt-5">
            <button class="btn btn-primary">Submit</button>
        </div>

    </form>
</div>
@endsection
