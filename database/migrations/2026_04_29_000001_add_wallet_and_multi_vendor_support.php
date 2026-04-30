<?php
// NOUVELLE MIGRATION À CRÉER
// Fichier : database/migrations/2026_04_29_000001_add_wallet_and_multi_vendor_support.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Wallet pour chaque vendeur (et admin)
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->decimal('balance', 14, 2)->default(0);          // solde disponible
            $table->decimal('pending_balance', 14, 2)->default(0);  // en attente de libération
            $table->timestamps();
        });

        // 2. Historique des mouvements de wallet
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->enum('type', ['credit', 'debit', 'commission', 'withdrawal']);
            $table->decimal('amount', 14, 2);
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();
        });

        // 3. Demandes de retrait
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('method');           // mobile_money, bank
            $table->string('account_details'); // numéro mobile ou IBAN
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        // 4. La table orders peut maintenant avoir seller_id NULL (multi-vendeur)
        // On ajoute un champ is_multi_vendor pour distinguer les commandes groupées
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_multi_vendor')->default(false)->after('shipping_status');
            $table->decimal('commission_rate', 5, 2)->default(8.00)->after('is_multi_vendor'); // 8%
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_multi_vendor', 'commission_rate']);
        });
    }
};