<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { // table amortization
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->string('code')->nullable(); // para el correlativo
            $table->unsignedBigInteger('procedure_modality_id'); // id modalidad
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->date('estimated_date'); // fecha estimada de pago
            $table->unsignedSmallInteger('quota_number'); // numero de cuota, cuando sea necesario se repite
            // de las siguientes 5 columnas se obtendra el total pagado
            $table->float('estimated_quota',10,2); // cuota estimada
            $table->float('penal_payment',10,2)->default(0); //pago penal
            $table->float('accumulated_payment',10,2)->default(0); //pago interés acumulado
            $table->float('interest_payment',10,2)->default(0); // pago de interes corriente
            $table->float('capital_payment',10,2)->default(0); // pago de capital
            $table->float('penal_remaining',10,2)->default(0); // interés penal previo
            $table->float('accumulated_remaining',10,2)->default(0); // interés acumulado previo
            $table->unsignedBigInteger('state_id')->nullable(false); //id estado del tramite
            $table->foreign('state_id')->references('id')->on('loan_states'); // estado de registro de pago
            $table->unsignedBigInteger('role_id');  // id rol bandeja actual
            $table->foreign('role_id')->references('id')->on('roles');
            $table->boolean('validated')->default(true);
            $table->text('description')->nullable(); // descripcion del pago
            $table->timestamps();
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
        Schema::dropIfExists('loan_payments');
    }
}
