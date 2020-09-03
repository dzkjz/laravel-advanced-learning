<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });


        //change connection from 'default' to 'foo'
        Schema::connection('foo')->create('orders', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';//指定数据库engine【仅MySQL支持】
            $table->charset = 'utf8mb4';//指定字符编码【仅MySQL支持】
            $table->collation = 'utf8mb4_unicode_ci';//指定collation【仅MySQL支持】
            $table->temporary();//创建一个临时表【除了SQL server都可以】

            $table->id()->unique();
            $table->primary('id');
            $table->spatialIndex('location');//除了sqlite以外的都支持
            $table->primary(['id', 'parent_id']);
            $table->unique('email');
            $table->index(['account_id', 'created_at']);
            $table->unique('email',
                'unique_email'// specify the index name yourself
            );
            $table->timestamps();
            $table->renameColumn('from', 'to');
            $table->dropColumn('votes');
            $table->dropColumn(['first', 'second', 'third']);
            $table->renameIndex('from', 'to');

            //外键【一个表[users表]上的键[id]出现在别的表[orders表]上，就是外键[user_id]】添加
            $table->foreign('user_id')->references('id')->on('users');


            //作用同上
            $table->foreignId('user_id')//unsignedBigInteger别名
            ->constrained();


            $table->foreignId('user_id')//unsignedBigInteger别名
            ->constrained('users')//可以指定表名
            ->onDelete('cascade')//预期行为
            ;


            $table->foreignId('user_id')//unsignedBigInteger别名
            ->nullable()//可在constrained前调用
            ->constrained();


            $table->dropForeign('posts_user_id_foreign');//删除外键[存放在posts表中的user_id外键]

            //也可以
            $table->dropForeign(['user_id']);


            //启动外键约束
            // SQLite disables foreign key constraints by default.
            // When using SQLite, make sure to enable foreign key support in your
            // database configuration before attempting to create them in your migrations.
            // In addition, SQLite only supports foreign keys upon creation of the table and not when tables are altered.
            Schema::enableForeignKeyConstraints();

            //取消外键约束
            Schema::disableForeignKeyConstraints();

        });


        Schema::rename('users', 'customers');//重命名已存在的表

        //重命名表格需要注意：
        // Before renaming a table,
        // you should verify that any foreign key constraints on the table have an explicit name in your migration
        // files instead of letting Laravel assign a convention based name

        Schema::drop('customers');
        Schema::dropIfExists('users');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
