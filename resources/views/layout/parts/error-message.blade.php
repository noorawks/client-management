@if ($errors->any())
    <div class="col-md-3">
        <div class="card bg-gradient-danger" style="background: transparent;">
            <div class="card-body pt-1 pr-1">
                <div class="card-tools float-right">
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <br>
                @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                @endforeach
            </div>
        <!-- /.card-body -->
        </div>
    <!-- /.card -->
    </div>
@endif