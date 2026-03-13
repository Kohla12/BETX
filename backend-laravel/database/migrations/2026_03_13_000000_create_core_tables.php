<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country_code', 4);
            $table->string('status')->default('pending_verification')->index();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_fingerprint');
            $table->string('device_type')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('trusted')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'device_fingerprint']);
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('currency', 3);
            $table->timestamps();

            $table->unique(['user_id', 'type']);
            $table->index(['user_id', 'type']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->decimal('amount', 18, 2);
            $table->decimal('balance_before', 18, 2);
            $table->decimal('balance_after', 18, 2);
            $table->string('reference')->unique();
            $table->string('external_reference')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('withdrawal_pins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('pin_hash');
            $table->unsignedInteger('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();
        });

        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('provider_id')->index();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('sport')->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('provider_id')->index();
            $table->string('name');
            $table->string('short_code')->nullable();
            $table->string('country')->nullable();
            $table->string('sport')->index();
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('provider_id')->index();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->timestampTz('start_time')->index();
            $table->string('status')->default('scheduled')->index();
            $table->string('sport')->index();
            $table->unsignedTinyInteger('home_score')->default(0);
            $table->unsignedTinyInteger('away_score')->default(0);
            $table->string('period')->nullable();
            $table->timestamps();
        });

        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('status')->default('open')->index();
            $table->boolean('live')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained('markets')->cascadeOnDelete();
            $table->string('selection');
            $table->string('label');
            $table->decimal('odds', 7, 3);
            $table->decimal('line', 7, 3)->nullable();
            $table->string('status')->default('open')->index();
            $table->timestamps();
        });

        Schema::create('bet_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('stake', 18, 2);
            $table->decimal('potential_payout', 18, 2);
            $table->decimal('total_odds', 12, 4);
            $table->string('type');
            $table->string('status')->default('pending')->index();
            $table->decimal('cashout_amount', 18, 2)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bet_slip_id')->constrained('bet_slips')->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedBigInteger('market_id')->nullable();
            $table->unsignedBigInteger('odd_id')->nullable();
            $table->string('selection');
            $table->decimal('odds_at_placement', 7, 3);
            $table->string('result')->default('pending')->index();
            $table->timestamp('settled_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('bets');
        Schema::dropIfExists('bet_slips');
        Schema::dropIfExists('odds');
        Schema::dropIfExists('markets');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('leagues');
        Schema::dropIfExists('withdrawal_pins');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('users');
    }
};

