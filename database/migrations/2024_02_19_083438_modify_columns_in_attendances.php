<?php

use App\Models\District;
use App\Models\Event;
use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignIdFor(Event::class)->nullable()->constrained('events')->cascadeOnDelete();
            $table->decimal('paid_amount',12)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['paid_amount']);
            $table->dropForeignIdFor(Event::class);
        });
    }
};
