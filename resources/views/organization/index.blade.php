@extends('layout.skeleton')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Organization</h1>
                </div>
            </div>
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
                            <a href="{{ route('organization.create') }}">
                                <button class="btn btn-primary btn-sm">Add <i class="fa fa-plus"></i></button>
                            </a>

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
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Website</th>
                                        <th>PIC</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($organizations as $organization)
                                        <tr>
                                            <td>
                                                <img width="200px" src="{{ $organization->logo }}" alt="{{ $organization->name }}">
                                            </td>
                                            <td>
                                                <a href="{{ route('organization.show', $organization->id) }}" target="_blank">
                                                    {{ $organization->name }}
                                                </a>
                                            </td>
                                            <td>{{ $organization->email }}</td>
                                            <td>{{ $organization->phone }}</td>
                                            <td>
                                                @if ($organization->url)
                                                    <a href="{{ $organization->url }}" target="_blank" class="btn btn-xs btn-default">
                                                        Click Here <i class="fa fa-link"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if (count($organization->persons) > 0)
                                                    <ul class="mb-0">
                                                        @foreach ($organization->persons as $person)
                                                            <li>{{ $person->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('organization.destroy', $organization->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')

                                                    <a href="{{ route('organization.edit', $organization->id) }}">
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
                            {{ $organizations->appends($search)->links() }}
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