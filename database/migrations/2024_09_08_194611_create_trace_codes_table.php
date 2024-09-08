<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraceCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trace_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trace_code')->unique()->index('trace-code-index');
            $table->string('service')->index('service-index');
            $table->string('http_code')->index('http-index');
            $table->string('method')->index('method-index');
            $table->string('class')->index('class-index');
            $table->string('description');
            $table->timestamp('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trace_codes');
    }
}
