<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
         'name',
         'email',
         'password',
         'status',
         'plan',
         'plan_expires_at',
         'is_admin',          // ← tambah ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

   protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_expires_at'   => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',  // ← tambah ini
        ];
    }

    // =========================================================
    // PLAN HELPERS
    // =========================================================

    /**
     * Cek apakah plan user masih aktif (belum expired)
     */
    public function isPlanActive(): bool
    {
        if ($this->plan === 'starter') return true; // starter tidak expired
        return $this->plan_expires_at === null || $this->plan_expires_at->isFuture();
    }

    /**
     * Ambil plan efektif — downgrade ke starter kalau expired
     */
    public function effectivePlan(): string
    {
        return ($this->isPlanActive() && $this->plan) ? $this->plan : 'starter';
    }

    public function isStarter(): bool
    {
        return $this->effectivePlan() === 'starter';
    }

    /**
     * Cek apakah user sedang dalam masa trial 30 hari (starter)
     */
    public function isTrialActive(): bool
    {
        return $this->plan === 'starter' && $this->created_at && $this->created_at->addDays(30)->isFuture();
    }

    public function isPersonal(): bool
    {
        return $this->effectivePlan() === 'personal';
    }

    public function isProfesional(): bool
    {
        return $this->effectivePlan() === 'profesional';
    }

    // =========================================================
    // FEATURE GATE — cek akses per fitur
    // =========================================================

    /**
     * Daftar fitur per plan.
     * Gunakan: $user->can('feature_name')  — atau canAccess() di bawah
     */
    const PLAN_FEATURES = [
        'starter' => [
            'input_transaksi',        // maks 50/bulan
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',      // total pemasukan & pengeluaran
        ],
        'personal' => [
            'input_transaksi',        // unlimited
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',
            'dashboard_grafik_basic',
            'filter_cari_transaksi',
            'laporan_bulanan_detail',
            'budget_planner',
        ],
        'profesional' => [
            'input_transaksi',
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',
            'dashboard_grafik_basic',
            'filter_cari_transaksi',
            'laporan_bulanan_detail',
            'budget_planner',
            'insight_otomatis',
            'analisis_kebiasaan',
            'perbandingan_bulanan',
            'notifikasi_pintar',
            'peringatan_budget',
            'export_csv_pdf',
            'analisis_kategori_detail',
            'kategori_custom_unlimited',
        ],
    ];

    /**
     * Cek apakah user boleh mengakses suatu fitur
     *
     * Contoh pemakaian di controller:
     *   if (! auth()->user()->canAccess('budget_planner')) abort(403);
     *
     * Contoh pemakaian di Blade:
     *   @if(auth()->user()->canAccess('dashboard_grafik')) ... @endif
     */
    public function canAccess(string $feature): bool
    {
        $plan = $this->isTrialActive() ? 'profesional' : $this->effectivePlan();
        return in_array($feature, self::PLAN_FEATURES[$plan] ?? []);
    }

    /**
     * Batas maksimal transaksi per bulan (hanya berlaku untuk starter)
     */
    public function maxTransaksiPerBulan(): int|null
    {
        if ($this->isTrialActive()) return null; // unlimited untuk trial
        return $this->isStarter() ? 50 : null; // null = unlimited
    }

    /**
     * Label nama plan yang tampil di UI
     */
    public function planLabel(): string
    {
        if ($this->isTrialActive()) return 'Starter (Trial 30 Hari)';
        return match ($this->effectivePlan()) {
            'personal'    => 'Personal',
            'profesional' => 'Profesional',
            default       => 'Starter',
        };
    }

    /**
     * Badge warna plan untuk UI
     */
    public function planColor(): string
    {
        if ($this->isTrialActive()) return 'purple';
        return match ($this->effectivePlan()) {
            'personal'    => 'blue',
            'profesional' => 'purple',
            default       => 'gray',
        };
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function transaksi(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Transaksi::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}