<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration

{

    public function up(): void

    {

        Schema::create('Tasks', function (Blueprint $table) {

            $table->id();

            // Menghubungkan tugas dengan pengguna yang membuatnya

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');

            $table->text('description')->nullable();

            // Kolom untuk status sesuai SRS [cite: 14, 29]

            $table->enum('status', ['Belum dikerjakan', 'Sedang dikerjakan', 'Selesai'])->default('Belum dikerjakan');

            // Kolom untuk tenggat waktu [cite: 14, 30]

            $table->date('due_date')->nullable();

            $table->timestamps();

        });

    }



    public function down(): void

    {

        Schema::dropIfExists('Tasks');

    }

};