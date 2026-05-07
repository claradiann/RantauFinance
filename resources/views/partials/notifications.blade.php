@inject('finance', 'App\Services\FinanceService')

@php
    $warnings = [];
    if (auth()->user()->canAccess('peringatan_budget')) {
        $warnings = $finance->allWarnings(auth()->id());
    }
@endphp

<div class="notif-wrapper">
    <button class="btn-icon" id="notifBtn" title="Notifikasi">
        🔔 @if(count($warnings) > 0) <span class="notif-dot"></span> @endif
    </button>
    
    <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-header">
            <h3>Notifikasi</h3>
            @if(count($warnings) > 0)
                <span class="notif-count">{{ count($warnings) }} Baru</span>
            @endif
        </div>
        <div class="notif-list">
            @forelse($warnings as $notif)
            <div class="notif-item {{ $notif['type'] }}">
                <div class="notif-icon">{{ $notif['icon'] }}</div>
                <div class="notif-content">
                    <div class="notif-title">{{ $notif['title'] }}</div>
                    <div class="notif-message">{!! $notif['message'] !!}</div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
                        <span class="notif-time">{{ $notif['time'] }}</span>
                        @if(str_starts_with($notif['id'], 'budget'))
                            <a href="/budget" style="font-size:0.7rem; color:var(--primary); font-weight:700; text-decoration:none;">Kelola Budget →</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <div class="notif-empty">
                    <div class="empty-icon">📭</div>
                    <p>Tidak ada notifikasi saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .notif-wrapper { position: relative; }
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 320px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid var(--border);
        z-index: 1000;
        display: none;
        overflow: hidden;
        animation: slideIn 0.2s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .notif-dropdown.show { display: block; }
    
    .notif-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--light);
    }
    .notif-header h3 { font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--dark); }
    .notif-count { font-size: 0.7rem; background: var(--danger); color: white; padding: 2px 8px; border-radius: 20px; font-weight: 700; }
    
    .notif-list { max-height: 350px; overflow-y: auto; }
    .notif-item {
        padding: 1rem 1.25rem;
        display: flex;
        gap: 0.85rem;
        border-bottom: 1px solid var(--border);
        transition: background 0.2s;
        text-align: left;
    }
    .notif-item:hover { background: #f8fafc; }
    .notif-item.warning { border-left: 4px solid #f59e0b; }
    .notif-item.danger { border-left: 4px solid #ef4444; }
    
    .notif-icon { font-size: 1.25rem; flex-shrink: 0; }
    .notif-content { flex: 1; }
    .notif-title { font-size: 0.85rem; font-weight: 700; color: var(--dark); margin-bottom: 2px; }
    .notif-message { font-size: 0.78rem; color: var(--gray); line-height: 1.5; }
    .notif-time { font-size: 0.7rem; color: #94a3b8; margin-top: 6px; }
    
    .notif-empty { padding: 3rem 1.5rem; text-align: center; color: var(--gray); }
    .notif-empty .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.3; }
    .notif-empty p { font-size: 0.85rem; margin: 0; }
</style>

<script>
    (function() {
        function initNotif() {
            const btn = document.getElementById('notifBtn');
            const dropdown = document.getElementById('notifDropdown');
            
            if (btn && dropdown) {
                btn.onclick = function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                };
                
                document.addEventListener('click', function() {
                    dropdown.classList.remove('show');
                });
                
                dropdown.onclick = function(e) {
                    e.stopPropagation();
                };
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNotif);
        } else {
            initNotif();
        }
    })();
</script>
