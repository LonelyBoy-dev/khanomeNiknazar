<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\RoleHasPermission;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('admin_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('shortContent');
            $table->longText('content');
            $table->string('seoTitle');
            $table->text('seoContent');
            $table->string('image');
            $table->bigInteger('view')->nullable()->default('0');
            $table->enum('status', ['PUBLISHED', 'DRAFT']);
            $table->timestamps();
            $table->softDeletes();


            $tools = Permission::where('name', 'mtaleb')->first();
            if (!$tools) {
                $permission1 = new Permission();
                $permission1->name = 'mtaleb';
                $permission1->title = 'مدیریت مطالب';
                $permission1->guard_name = 'admin';
                $permission1->parent = '0';
                $permission1->save();

                $complain = new RoleHasPermission();
                $complain->permission_id = $permission1->id;
                $complain->role_id = '1';
                $complain->save();


                $permission2 = new Permission();
                $permission2->name = 'posts';
                $permission2->title = 'منو مدیریت مطالب';
                $permission2->guard_name = 'admin';
                $permission2->parent = $permission1->id;
                $permission2->save();

                $complain = new RoleHasPermission();
                $complain->permission_id = $permission2->id;
                $complain->role_id = '1';
                $complain->save();



                $permission3 = new Permission();
                $permission3->name = 'posts_index';
                $permission3->title = 'لیست مطالب';
                $permission3->guard_name = 'admin';
                $permission3->parent = $permission1->id;
                $permission3->save();

                $complain = new RoleHasPermission();
                $complain->permission_id = $permission3->id;
                $complain->role_id = '1';
                $complain->save();



                $permission4 = new Permission();
                $permission4->name = 'posts_category';
                $permission4->title = 'دسته بندی مطالب';
                $permission4->guard_name = 'admin';
                $permission4->parent = $permission1->id;
                $permission4->save();

                $complain = new RoleHasPermission();
                $complain->permission_id = $permission4->id;
                $complain->role_id = '1';
                $complain->save();



                $permission5 = new Permission();
                $permission5->name = 'posts_comments';
                $permission5->title = 'مدیریت نظرات';
                $permission5->guard_name = 'admin';
                $permission5->parent = $permission1->id;
                $permission5->save();

                $complain = new RoleHasPermission();
                $complain->permission_id = $permission5->id;
                $complain->role_id = '1';
                $complain->save();

            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
