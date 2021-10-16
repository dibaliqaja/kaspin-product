@extends('home')
@section('content')

    @if (Session::has('alert'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ Session('alert') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            @if (auth()->user())
                <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Barang</a><br><br>
            @endif
        </div>
        <div class="col-md-4 mb-3">
            <form action="" class="flex-sm">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Search" value="{{ Request::get('keyword') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary mr-2 rounded-right" type="submit">Cari</i></button>
                        <button onclick="window.location.href='{{ route('products.index') }}'" type="button" class="btn btn-md btn-secondary rounded">Reload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr align="center">
                    <th width="5%">No</th>
                    <th>Nama</th>
                    <th>SKU</th>
                    <th>Harga</th>
                    <th width="13%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $product => $result)
                    <tr>
                        <td>{{ $product + $data->firstitem() }}</td>
                        <td>{{ $result->name }}</td>
                        <td>{{ $result->sku }}</td>
                        <td>Rp. {{ number_format($result->price, 2, ',', '.') }}</td>
                        <td>
                            @if (!auth()->user())
                                <small class="text-secondary">No Action</small>
                            @else
                                <a href="{{ route('products.edit', $result->id) }}" type="button" class="btn btn-sm btn-info">Edit</i></a>
                                @if (Auth::user()->role == 'Administrator')                                    
                                    <a href="javascript:void(0)" id="btn-delete" class="btn btn-sm btn-danger" onclick="deleteData('{{ $result->id }}')" data-toggle="modal" data-target="#deleteProductModal">Delete</i></a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3 float-right">
        {{ $data->links() }}
    </div>

@endsection

@section('modal')
    <!-- Modal Delete -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="javascript:void(0)" id="deleteForm" method="post">
                @method('DELETE')
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="vcenter">Hapus Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" onclick="formSubmit()" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function deleteData(id) {
            let url = '{{ route("products.destroy", ":id") }}';
            url     = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@endsection