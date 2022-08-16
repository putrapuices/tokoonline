<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {

            /* buat column id->integer,autoincrement dan primary*/
            $table->id();
            /*buat column name->string*/
            $table->string("name");
            /*buat column created_at dan updated_at*/
            $table->timestamps();
            /*untuk fitur softdelete*/
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
            hapus jika table categories exist.
            untuk migrate:rollback
        */
        Schema::dropIfExists('categories');
    }
}
