@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            @include('errors.error_message')
            <form class="row" action="/admin/posts/categories/update/{{$item->id}}" method="post">
                @csrf
                <div class="col-lg-8 col-xs-12 col-sm-12 ali-margin-0">
                    <div class="card">

                        <div class="card-body">
                            <div class="form-horizontal form-material">

                                <div class="form-group">
                                    <label class="col-md-12">عنوان</label>
                                    <div class="col-md-12">
                                        <input onkeyup="convertToSlug()" type="text" name="title" value="@if(old('title')){{old('title')}}@else{{$item->title}}@endif"
                                               placeholder="عنوان دسته بندی را وارد کنید"
                                               class="form-control form-control-line">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">نامک</label>
                                    <div class="col-md-12">
                                        <input type="text" name="slug" value="@if(old('slug')){{old('slug')}}@else{{$item->slug}}@endif" placeholder="نامک  را وارد کنید"
                                               class="form-control form-control-line">
                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group m-0">
                                    <div class="col-md-12">
                                        <button type="submit" style="padding: 3px" class="btn waves-effect waves-light btn-block btn-info">
                                            ویرایش
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label">دسته مادر</label>
                                    <select class="form-control" name="parent"
                                            data-placeholder="Choose a Category" tabindex="1">
                                        <option value="0">دسته مادر را انتخاب کنید</option>
                                        @foreach($items as $cat)
                                            <option @if(old('parent')) @if(old('parent')==$cat->id) selected @endif @else @if($item->parent==$cat->id) selected @endif @endif value="{{$cat->id}}">{{$cat->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection
