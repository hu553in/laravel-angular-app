<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToPublicTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_transport', function (Blueprint $table) {
            $table->unique(['type', 'route_number'], 'ux_public_transport_type_route_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_transport', function (Blueprint $table) {
            $table->dropUnique('ux_public_transport_type_route_number');
        });
    }
}
