<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_revocations', function (Blueprint $table) {
            // Add jurisdiction fields if not exist
            if (!Schema::hasColumn('certificate_revocations', 'jurisdiction_country')) {
                $table->string('jurisdiction_country')->nullable();
            }
            if (!Schema::hasColumn('certificate_revocations', 'jurisdiction_purpose')) {
                $table->string('jurisdiction_purpose')->nullable();
            }
            if (!Schema::hasColumn('certificate_revocations', 'legal_basis_code')) {
                $table->string('legal_basis_code')->nullable();
            }
            if (!Schema::hasColumn('certificate_revocations', 'receipt_path')) {
                $table->string('receipt_path')->nullable();
            }
            // Add authority reference fields (for external government oversight)
            if (!Schema::hasColumn('certificate_revocations', 'authority_reference_id')) {
                $table->string('authority_reference_id')->nullable()->comment('External authority/court reference ID');
            }
            if (!Schema::hasColumn('certificate_revocations', 'authority_jurisdiction')) {
                $table->string('authority_jurisdiction')->nullable()->comment('Name of external authority (e.g., "Dubai Courts", "Dutch Ministry of Justice")');
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificate_revocations', function (Blueprint $table) {
            $table->dropColumn([
                'jurisdiction_country',
                'jurisdiction_purpose',
                'legal_basis_code',
                'receipt_path',
                'authority_reference_id',
                'authority_jurisdiction'
            ]);
        });
    }
};
