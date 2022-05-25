@extends('layout.skeleton')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Account Managers</h1>
                </div>
            </div>

            @include('layout.parts.error-message')
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <button data-toggle="modal" data-target="#user-add" class="btn btn-primary btn-sm">
                                Add <i class="fa fa-plus"></i>
                            </button>

                            <div class="card-tools">
                                <form method="get" action="">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="search" class="form-control float-right" placeholder="Search" @if ($search) value="{{ $search }}" @endif>
    
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date Created</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                {{ $user->created_at }}
                                            </td>
                                            <td>
                                                {{ $user->name }}
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')

                                                    <a href="#" v-on:click="getUser('{{ route('user.get-user', $user->id) }}')">
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
                        <div class="card-footer">
                            {{ $users->appends($search)->links() }}
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('modal')
    <div class="modal fade" id="user-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Account Manager</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" autocomplete="off" action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="text" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Account Manager</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit_user_form" class="form-horizontal" autocomplete="off" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input id="edit_user_form_name" name="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input id="edit_user_form_email" name="email" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
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
                getUser: function (url) {
                    axios.get(url).then( function (response) {
                        app.user = response.data;
                        let actionUrl = '{{ route("user.update", ":id") }}';
                        actionUrl = actionUrl.replace(':id', app.user.id);

                        $('#edit_user_form').attr('action', actionUrl);
                        $('#edit_user_form_name').val(app.user.name);
                        $('#edit_user_form_email').val(app.user.email);
                        $("#user-edit").modal('show');
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                }
            }
        });
    </script>
@endsection