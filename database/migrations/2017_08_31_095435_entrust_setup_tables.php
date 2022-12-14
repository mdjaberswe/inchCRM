<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('roles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 200)->unique();
            $table->string('display_name', 200)->nullable();
            $table->string('description')->nullable();
            $table->boolean('fixed')->default(0);
            $table->enum('label', ['general', 'project', 'client']);
            $table->timestamps();
            $table->softDeletes();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 200)->unique();
            $table->string('display_name', 200)->nullable();
            $table->enum('type', ['open', 'preserve', 'semi_preserve']);
            $table->string('description')->nullable();
            $table->enum('label', ['general', 'project', 'client']); 
            $table->enum('group', ['basic', 'tool', 'import_export', 'send_email', 'send_SMS', 'admin_level'])->nullable(); 
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function(Blueprint $table)
        {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
