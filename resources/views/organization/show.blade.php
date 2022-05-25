@extends('layout.skeleton')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $organization->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <span class="float-sm-right">
                        <i class="fas fa-clock"></i> Last Update: {{ date('d M Y H:i', strtotime($organization->updated_at)) }}
                    </span>
                </div>
            </div>
            @include('layout.parts.error-message')
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <table>
                                        <tr>
                                            <td><h5>Name:</h5></td>
                                            <td><h5>{{ $organization->name }}</h5></td>
                                        </tr>
                                        <tr>
                                            <td><h5>Email:</h5></td>
                                            <td><h5>{{ $organization->email }}</h5></td>
                                        </tr>
                                        <tr>
                                            <td><h5>Website:</h5></td>
                                            <td>
                                                <a href="{{ $organization->url }}" target="_blank">
                                                    <h5>Click Here! <i class="fa fa-link"></i></h5>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <img class="float-sm-right" width="100%" src="{{ $organization->logo }}" alt="{{ $organization->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="float-sm-right">
                                    <form action="{{ route('organization.destroy', $organization->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            
                                            <a href="{{ route('organization.edit', $organization->id) }}" class="btn btn-primary mb-2">Edit Organization <i class="fas fa-edit"></i></a>

                                            <button type="submit" class="btn btn-danger mb-2" onclick="return confirm('Are you sure?')">
                                                Delete Organization <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h5>PIC List</h5>
                                </div>
                                <div class="col-sm-6">
                                    <button data-toggle="modal" data-target="#person-add" class="btn btn-primary btn-sm float-sm-right">
                                        Add <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="person-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($organization->persons as $person)
                                        <tr>
                                            <td>
                                                <img width="200px" src="{{ $person->avatar }}" alt="{{ $person->name }}">
                                            </td>
                                            <td>
                                                {{ $person->name }}
                                            </td>
                                            <td>{{ $person->email }}</td>
                                            <td>{{ $person->phone }}</td>
                                            <td>
                                                <form action="{{ route('person.destroy', $person->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')

                                                    <a href="#" v-on:click="getPerson('{{ route('person.edit', $person->id) }}')">
                                                        <button type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                                    </a>

                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('modal')
    <div class="modal fade" id="person-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add person</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" autocomplete="off" action="{{ route('person.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input name="phone" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Avatar</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="person-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit person</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit_person_form" class="form-horizontal" autocomplete="off" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input id="edit_person_form_name" name="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input id="edit_person_form_email" name="email" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input id="edit_person_form_phone" name="phone" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Avatar</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>

    <script>
        var app = new Vue({
            el: '.wrapper',
            methods: {
                getPerson: function (url) {
                    axios.get(url).then( function (response) {
                        app.person = response.data;
                        let actionUrl = '{{ route("person.update", ":id") }}';
                        actionUrl = actionUrl.replace(':id', app.person.id);

                        $('#edit_person_form').attr('action', actionUrl);
                        $('#edit_person_form_name').val(app.person.name);
                        $('#edit_person_form_email').val(app.person.email);
                        $('#edit_person_form_phone').val(app.person.phone);
                        $("#person-edit").modal('show');
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                }
            }
        });

        $(function () {
            $('#person-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "info": true,
                "ordering": false,
                "autoWidth": false,
            });
        });
    </script>
@endsection