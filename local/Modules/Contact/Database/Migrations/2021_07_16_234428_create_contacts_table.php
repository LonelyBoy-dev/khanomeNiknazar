<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\RoleHasPermission;


class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();

            $tools=Permission::where('name','more')->first();
            $permissions=Permission::where('name','contact_us')->first();
            if (!$permissions){
                $permission=new Permission();
                $permission->name= 'contact_us';
                $permission->title= 'لیست تماس باما';
                $permission->guard_name= 'admin';
                $permission->parent= $tools->id;
                $permission->save();

                $complain=new RoleHasPermission();
                $complain->permission_id=$permission->id;
                $complain->role_id='1';
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
        Schema::dropIfExists('contacts');
    }
}
