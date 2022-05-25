@extends('layout.skeleton')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Organization</h1>
                </div>
            </div>

            @include('layout.parts.error-message')
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10">
                    <div class="card card-primary">
                        <form class="form-horizontal" autocomplete="off" action="{{ route('organization.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Phone" required>
                                </div>
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" name="url" value="{{ old('url') }}" class="form-control" placeholder="Website">
                                </div>
                                @if (Auth::user()->isAdmin)
                                    <div class="form-group">
                                        <label>Assign to Account Manager</label>
                                        <select name="user_id" class="form-control select2" style="width: 100%;">
                                            @foreach ($account_managers as $account_manager)
                                                <option value="{{ $account_manager->id }}">{{ $account_manager->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                @endif
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Logo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="logo" class="custom-file-input" required>
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('custom-js')
    <script>
        $(function () {
            $('.select2').select2()
        });
    </script>
@endsection