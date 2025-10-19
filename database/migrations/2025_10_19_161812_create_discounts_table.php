<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá
            $table->string('description')->nullable(); // Mô tả ngắn
            $table->enum('type', ['percent', 'amount']); // loại: giảm % hoặc giảm số tiền
            $table->integer('value'); // giá trị giảm
            $table->boolean('active')->default(true); // còn hiệu lực hay không
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
