<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // appt_status tracks the workflow: Ongoing, Completed, Cancelled
            $table->string('appt_status')->default('Ongoing')->after('status');

            // This stores the exact time the "Complete" button was clicked
            $table->timestamp('completed_at')->nullable()->after('updated_at');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['appt_status', 'completed_at']);
        });
    }
};
