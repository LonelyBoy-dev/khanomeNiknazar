@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    @if (admin()->can('users'))<li class="nav-item"><a class="nav-link @if($data_table=="users")active show @endif" href="/admin/trashed/users">کاربران</a></li>@endif
                    @if (admin()->can('admins'))<li class="nav-item"><a class="nav-link @if($data_table=="admins")active show @endif" href="/admin/trashed/admins">مدیران</a></li>@endif
                    @if (admin()->can('posts_index'))<li class="nav-item"><a class="nav-link @if($data_table=="posts")active show @endif" href="/admin/trashed/posts">مطالب</a></li>@endif
                    @if (admin()->can('posts_category'))<li class="nav-item"><a class="nav-link @if($data_table=="postCategories")active show @endif" href="/admin/trashed/postCategories">دسته بندی مطالب</a></li>@endif
{{--
                    <li class="nav-item"><a class="nav-link @if($data_table=="product")active show @endif" href="">محصولات</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#favorites">علاقه مندی ها</a></li>
--}}
                </ul>
                <div class="tab-content">
                    @if($data_table=="users")
                    <div class="tab-pane  @if($data_table=="users")active show @endif">
                        @include('admin.trashed.users')
                    </div>
                    @endif
                    @if($data_table=="admins")
                    <div class="tab-pane  @if($data_table=="admins")active show @endif">
                        @include('admin.trashed.admins')
                    </div>
                    @endif

                    @if($data_table=="posts")
                    <div class="tab-pane @if($data_table=="posts")active show @endif">
                        @include('admin.trashed.posts')
                    </div>
                    @endif

                    @if($data_table=="postCategories")
                    <div class="tab-pane @if($data_table=="postCategories")active show @endif" id="posts-tab">
                        @include('admin.trashed.postCategories')
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
