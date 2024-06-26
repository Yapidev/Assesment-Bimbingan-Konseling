@extends("layouts.admin")
@section("title")
    Opsi Jawaban
@endsection
@section("content")
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Opsi Jawaban</h4>
            <p class="mb-0">Opsi Jawaban yang tersedia pada instrumen yang anda pilih</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex gap-3">
     <a href="{{route('instruments.index')}}" class="btn btn-outline-primary">Kembali</a>
     <a href="{{route('answer.create',$instrumen_id)}}" class="btn btn-primary">Tambah</a>
    </div>
</div>
<div>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Text</th>
            <th>Poin Fav</th>
            <th>Point UnFav</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($answers as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->text }}</td>
            <td>{{$item->point_fav}}</td>
            <td>{{$item->point_unfav}}</td>
            <td>
                <a href="{{ route('answer.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                <button type="button" onclick="handleConfirmDelete('formDeleteanswer')" class="btn btn-danger">Delete</button>
                <form id="formDeleteanswer" action="{{ route('answer.destroy', $item->id) }}" method="POST" class="d-none" style="display:inline;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Anda belum mengatur kriteria untuk Instrumen ini</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection

@section("script")

@endsection
