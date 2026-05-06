{{-- ===== SHARED SIDEBAR ===== --}}
{{-- Usage: @include('partials.sidebar', ['active' => 'dashboard']) --}}

@php
    $user = auth()->user();
    $plan = $user->effectivePlan();

    // Menu items with feature requirements
    // null = always accessible
    $menuItems = [
        ['section' => 'Menu'],
        ['label' => 'Dashboard',         'icon' => '📊', 'url' => '/dashboard',         'key' => 'dashboard',  'feature' => null],
        ['label' => 'Transaksi',         'icon' => '💳', 'url' => '/transaksi',          'key' => 'transaksi',  'feature' => null],
        ['label' => 'Tambah Transaksi',  'icon' => '➕', 'url' => '/transaksi/create',   'key' => 'create',     'feature' => null],

        ['section' => 'Lainnya'],
        ['label' => 'Kategori',          'icon' => '📁', 'url' => '/kategori',           'key' => 'kategori',   'feature' => null],
        ['label' => 'Budget',            'icon' => '🎯', 'url' => '/budget',             'key' => 'budget',     'feature' => 'budget_planner',            'min_plan' => 'Personal'],
        ['label' => 'Laporan',           'icon' => '📈', 'url' => '/laporan',            'key' => 'laporan',    'feature' => 'laporan_bulanan_detail',    'min_plan' => 'Personal'],
        ['label' => 'Pengaturan',        'icon' => '⚙️', 'url' => '/profile',            'key' => 'pengaturan', 'feature' => null],
    ];

    $currentActive = $active ?? '';
@endphp

<aside class="sidebar" id="sidebar">
    <a href="/" class="sidebar-logo">
        <div class="logo-icon">💰</div>
        <span class="logo-text">RantauFinance</span>
    </a>

    <nav class="sidebar-nav">
        @php $currentSection = null; @endphp
        @foreach($menuItems as $item)
            @if(isset($item['section']))
                @if($currentSection !== null)
                    </div>
                @endif
                <div class="nav-section">
                    <div class="nav-section-title">{{ $item['section'] }}</div>
                @php $currentSection = $item['section']; @endphp
                @continue
            @endif

            @php
                $hasAccess = $item['feature'] === null || $user->canAccess($item['feature']);
                $isActive  = ($currentActive === $item['key']);
            @endphp

            @if($hasAccess)
                <a href="{{ $item['url'] }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                    <span class="nav-icon">{{ $item['icon'] }}</span> {{ $item['label'] }}
                </a>
            @else
                <div class="nav-item locked" 
                     title="Upgrade ke {{ $item['min_plan'] ?? 'Personal' }} untuk mengakses fitur ini" 
                     data-label="{{ $item['label'] }}" 
                     data-plan="{{ $item['min_plan'] ?? 'Personal' }}"
                     onclick="showUpgradeToast(this.dataset.label, this.dataset.plan)">
                    <span class="nav-icon">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    <span class="lock-badge">🔒</span>
                </div>
            @endif
        @endforeach
        @if($currentSection !== null)
            </div>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="name">{{ $user->name }}</div>
                <div class="role">
                    {{ $user->is_admin ? '⚙️ Administrator' : $user->planLabel() }}
                </div>
            </div>
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn">
                <span>🚪</span> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Upgrade toast notification --}}
<div class="upgrade-toast" id="upgradeToast">
    <div class="upgrade-toast-content">
        <span class="upgrade-toast-icon">🔒</span>
        <div class="upgrade-toast-text">
            <strong id="upgradeToastTitle">Fitur Terkunci</strong>
            <p id="upgradeToastMsg">Upgrade paket untuk mengakses fitur ini.</p>
        </div>
        <a href="/profile" class="upgrade-toast-btn" onclick="document.getElementById('upgradeToast').classList.remove('show')">Upgrade</a>
    </div>
</div>

<style>
/* Locked nav item */
.nav-item.locked {
    opacity: 0.45;
    cursor: not-allowed;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    user-select: none;
}
.nav-item.locked:hover {
    opacity: 0.6;
    background: rgba(239,68,68,0.04);
}
.lock-badge {
    font-size: 0.65rem;
    margin-left: auto;
}

/* Upgrade toast */
.upgrade-toast {
    position: fixed;
    bottom: -100px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    transition: bottom 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.upgrade-toast.show {
    bottom: 2rem;
}
.upgrade-toast-content {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    color: white;
    padding: 0.85rem 1.25rem;
    border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
    min-width: 320px;
    max-width: 90vw;
}
.upgrade-toast-icon {
    font-size: 1.3rem;
    flex-shrink: 0;
}
.upgrade-toast-text {
    flex: 1;
}
.upgrade-toast-text strong {
    display: block;
    font-size: 0.85rem;
    margin-bottom: 2px;
}
.upgrade-toast-text p {
    font-size: 0.75rem;
    opacity: 0.8;
    margin: 0;
}
.upgrade-toast-btn {
    padding: 0.45rem 1rem;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: white;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s;
    font-family: 'Inter', sans-serif;
}
.upgrade-toast-btn:hover {
    background: rgba(255,255,255,0.25);
}
</style>

<script>
function showUpgradeToast(featureName, minPlan) {
    const toast = document.getElementById('upgradeToast');
    const btn = toast.querySelector('.upgrade-toast-btn');
    
    document.getElementById('upgradeToastTitle').textContent = featureName + ' — Terkunci 🔒';
    document.getElementById('upgradeToastMsg').textContent = 'Upgrade ke paket ' + minPlan + ' untuk membuka fitur ini.';
    
    // Update link berdasarkan plan minimal
    const targetPlan = minPlan.toLowerCase() === 'profesional' ? 'profesional' : 'personal';
    btn.href = '/payment/upgrade/' + targetPlan;

    toast.classList.add('show');
    clearTimeout(window._upgradeToastTimer);
    window._upgradeToastTimer = setTimeout(() => toast.classList.remove('show'), 4000);
}
</script>
