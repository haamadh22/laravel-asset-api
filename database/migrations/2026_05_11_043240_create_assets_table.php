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
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('asset_name');
        $table->string('category');
        $table->string('serial_number');
        $table->date('purchase_date'); 
        $table->decimal('value', 10, 2);
        $table->string('status')->default('Active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
