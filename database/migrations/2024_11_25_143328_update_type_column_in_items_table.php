<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Change the 'type' column to NOT NULL and set a default value
            $table->integer('type')->default(1)->change(); // default 1 (Clothing)
        });
    }
    
    
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Revert the column change if necessary
            $table->string('type')->change();
        });
    }
    
};
