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
        return $this->isPlanActive() ? $this->plan : 'starter';
    }

    public function isStarter(): bool
    {
        return $this->effectivePlan() === 'starter';
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
            'input_transaksi',        // maks 30/bulan
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',
            'export_csv_pdf',
        ],
        'personal' => [
            'input_transaksi',        // unlimited
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',
            'export_csv_pdf',
            'dashboard_grafik',
            'filter_cari_transaksi',
            'laporan_bulanan_detail',
            'budget_planner',
            'notif_inapp',
        ],
        'profesional' => [
            'input_transaksi',
            'kategori_dasar',
            'laporan_bulanan_simpel',
            'riwayat_transaksi',
            'export_csv_pdf',
            'dashboard_grafik',
            'filter_cari_transaksi',
            'laporan_bulanan_detail',
            'budget_planner',
            'notif_inapp',
            'laporan_tahunan',
            'analisis_per_kategori',
            'kategori_custom',
            'notif_email',
            'notif_telegram',
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
        $plan = $this->effectivePlan();
        return in_array($feature, self::PLAN_FEATURES[$plan] ?? []);
    }

    /**
     * Batas maksimal transaksi per bulan (hanya berlaku untuk starter)
     */
    public function maxTransaksiPerBulan(): int|null
    {
        return $this->isStarter() ? 30 : null; // null = unlimited
    }

    /**
     * Label nama plan yang tampil di UI
     */
    public function planLabel(): string
    {
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
        return match ($this->effectivePlan()) {
            'personal'    => 'blue',
            'profesional' => 'purple',
            default       => 'gray',
        };
    }

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function transaksi()
    {
        return $this->hasMany(\App\Models\Transaksi::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}