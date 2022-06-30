@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="btnAdd">Tambah product</a>
                    <br><br>
                    <table class="table table-striped table-bordered table-sm" id="productTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Updated at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateOrCreateModal" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formAction" name="formAction" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="form-group row">
                        <label for="name" class="col-md-12 col-form-label">{{ __('Name') }}</label>
                        
                        <div class="col-md-12">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="price" class="col-md-12 col-form-label">{{ __('Price') }}</label>
                        
                        <div class="col-md-12">
                            <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="name" autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="name" class="col-sm-12 control-label">{{ __('Description') }}</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="description" id="description" required></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block" id="btnCreate" value="create">Save
                </button>
                
            </form>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="confirmModal" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>are you sure to delete this data ?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" name="btnDelete" id="btnDelete">Delete Data</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        
        $('#btnAdd').click(function () {
            $('#button-create').val("create-post"); 
            $('#id').val('');
            $('#formAction').trigger("reset");
            $('#titleModal').html("Tambah product Baru");
            $('#updateOrCreateModal').modal('show');
        });

        $(document).ready(function () {
            $('#productTable').DataTable({
                processing: true,
                serverSide: true, //active server-side 
                ajax: {
                    url: "{{ route('home.index') }}",
                    type: 'GET'
                },
                columns: [
                {
                    data: 'id',
                    name: 'id'
                },{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                ],
                order: [
                [0, 'asc']
                ]
            });
        });
        
        if ($("#formAction").length > 0) {
            $("#formAction").validate({
                submitHandler: function (form) {
                    var actionType = $('#btnCreate').val();
                    $('#btnCreate').html('Sending..');
                    $.ajax({
                        data: $('#formAction').serialize(),
                        url: "{{ route('home.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) { 
                            $('#formAction').trigger("reset");
                            $('#updateOrCreateModal').modal('hide');
                            $('#btnCreate').html('Save');
                            var oTable = $('#productTable').dataTable();
                            oTable.fnDraw(false); //reset datatable
                            iziToast.success({ //show toast success
                                title: 'Saved',
                                message: '{{ Session(' success ')}}',
                                position: 'bottomRight'
                            });
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btnCreate').html('Save');
                        }
                    });
                }
            })
        }
        
        // if class edit-post clicked
        $('body').on('click', '.edit-post', function () {
            var data_id = $(this).data('id');
            $.get('home/' + data_id + '/edit', function (data) {
                $('#titleModal').html("Edit Post");
                $('#btnCreate').val("edit-post");
                $('#updateOrCreateModal').modal('show');         
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#description').val(data.description);
                $('#updated_at').val(data.updated_at);
            })
        });

        //if class delete clicked
        $(document).on('click', '.delete', function () {
            dataId = $(this).attr('id');
            $('#confirmModal').modal('show');
        });
        
        $('#btnDelete').click(function () {
            $.ajax({
                url: "home/" + dataId,
                type: 'delete',
                beforeSend: function () {
                    $('#btnDelete').text('Delete');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        var oTable = $('#productTable').dataTable();
                        oTable.fnDraw(false);
                    });
                    iziToast.warning({ //show toast warning
                        title: 'Deleted',
                        message: '{{ Session('delete')}}',
                        position: 'bottomRight'
                    });
                }
            })
        });
    </script>
    
    @endsection
    