@extends("layouts.app")

@section("content")

<ul>
	@foreach($daftar_kategori as $kategori)
	<li>{{ $kategori->name }}</li>
	<br />
	@endforeach
</ul>
<hr>
<!-- INI MERUPAKAN KODE UNTUK MENAMPILKAN PAGINATION -->
{{$daftar_kategori->links()}}
@endsection