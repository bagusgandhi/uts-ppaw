@extends('layout.base',[
    'title' => 'List User',
])
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Tabel Rekaman User</h2>
    <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Name</th>
                <th>Email</th>
                <th>Jabatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal Create -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create-modalLabel">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createForm" enctype="multipart/form-data">
            @csrf
        <div class="form-group">
            <label for="n">Name Lengkap</label>
            <input type="" required="" id="n" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="e">Email</label>
            <input type="" required="" id="e" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="p">Password</label>
            <input type="password" required="" id="p" name="password" class="form-control">
        </div>
    
        <div class="form-group">
            <label for="j">Jabatan</label>
            <select name="jabatan" id="j" class="form-control">
                <option disabled="">- Pilih Jabatan -</option>
                <option value="admin">Admin</option>
                <option value="operator">operator</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="form-group">
            <label for="f">Foto</label>
            <input type="file" required="" id="f" name="foto" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-store">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Create -->

<!-- Modal Edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm" enctype="multipart/form-data">
            @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input type="" required="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="" required="" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <select name="jabatan" id="jabatan" class="form-control">
                <option disabled="">- Pilih Jabatan -</option>
                <option value="admin">Admin</option>
                <option value="operator">operator</option>
                <option value="user">User</option>
            </select>
        </div>
        
        <div class="form-group">
            <div>
                <img  id="imguser" src="" alt="" height="100">
            </div>
            <label for="f">Foto</label>
            <input type="file" id="f" name="foto" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->

<!-- Destroy Modal -->
<div class="modal fade" id="destroy-modal" tabindex="-1" role="dialog" aria-labelledby="destroy-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroy-modalLabel">Yakin Hapus ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-destroy">Hapus</button>
      </div>
    </div>
  </div>
</div>
<!-- Destroy Modal -->

@stop
@push('js')
<script type="text/javascript">
    $(function(){
         
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'foto', name: 'foto',
                render: function( data, type, full, meta ) {
                        return "<img src=\"/dokumen/" + data + "\" height=\"50\"/>";
                    }},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'jabatan', name: 'jabatan'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
    });

    $("#createForm").on("submit",function(e){
        e.preventDefault()
        var formData = new FormData(this);
        $.ajax({
            url: "/users",
            method: "POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(){
                $("#create-modal").modal("hide")
                $('.yajra-datatable').DataTable().ajax.reload();
                swal("Berhasil!", "User Berhasil Dimasukan!", "success");
            }
        })
    })

    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        
        $.ajax({
            url: "/users/"+id+"/edit",
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show")
                $("#id").val(response.id)
                $("#name").val(response.name)
                $("#email").val(response.email)
                $("#jabatan").val(response.jabatan)
                $("#imguser").attr('src', "dokumen/"+response.foto)
            }
        })
    });

    
    $("#editForm").on("submit",function(e){
        e.preventDefault()
        var id = $("#id").val()
        var formData = new FormData(this);

        $.ajax({
            url: "/usr/"+id,
            method: "POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(){
                $('.yajra-datatable').DataTable().ajax.reload();
                $("#edit-modal").modal("hide")
                swal("Berhasil!", "User Berhasil Diedit!", "success");

            }
        })
    })
   

    $('body').on("click",".btn-delete",function(){
        var id = $(this).attr("id")
        $(".btn-destroy").attr("id",id)
        $("#destroy-modal").modal("show")
    });
    
    $(".btn-destroy").on("click",function(){
        var id = $(this).attr("id")

        $.ajax({
            url: "/users/"+id,
            method: "DELETE",
            data:{
                'id': id,
                '_token': '{{ csrf_token() }}',
            },
            success:function(){
                $("#destroy-modal").modal("hide")
                $('.yajra-datatable').DataTable().ajax.reload();
                swal("Berhasil!", "User Berhasil Dihapus!", "success");
            }
        });
    })

  });

</script>
@endpush