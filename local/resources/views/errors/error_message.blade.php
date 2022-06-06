
@if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <div style="padding: 5px 10px;font-size: 14px;" class="alert alert-danger"> {{ $error }}
                <button type="button" class="close m-l-10" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            </div>
        @endforeach
@endif
