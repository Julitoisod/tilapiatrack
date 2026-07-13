{{-- TilapiaTrack visual redesign — CSS only, no markup/behaviour changes. --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

/* ---------- Base ---------- */
body.fi-body {
    font-family: 'Poppins', 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif !important;
    background:
        radial-gradient(1100px 560px at 100% -12%, rgba(20,184,166,0.08), transparent 60%),
        radial-gradient(900px 480px at -12% 112%, rgba(14,165,233,0.06), transparent 60%),
        #eef2f5 !important;
}

.fi-main { max-width: 84rem !important; }

/* ---------- Topbar (glassy) ---------- */
.fi-topbar nav,
.fi-topbar > div {
    background: rgba(255,255,255,0.72) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(15,23,42,0.06) !important;
    box-shadow: 0 6px 22px -16px rgba(15,23,42,0.5) !important;
}

/* ---------- Sidebar ---------- */
.fi-sidebar, .fi-main-sidebar {
    background: rgba(255,255,255,0.94) !important;
    border-right: 1px solid rgba(15,23,42,0.06);
    backdrop-filter: blur(8px);
}
.fi-sidebar-header { background: transparent !important; box-shadow: none !important; }

.fi-sidebar-item-button {
    border-radius: 0.7rem !important;
    font-weight: 500 !important;
    color: #475569 !important;
}
.fi-sidebar-item-button:hover {
    background: rgba(20,184,166,0.08) !important;
    color: #0f766e !important;
}
.fi-sidebar-item-active .fi-sidebar-item-button,
.fi-sidebar-item-button.bg-gray-100 {
    background: linear-gradient(90deg, rgba(20,184,166,0.16), rgba(20,184,166,0.02)) !important;
    color: #0f766e !important;
    box-shadow: inset 3px 0 0 0 #14b8a6;
    font-weight: 600 !important;
}
.fi-sidebar-item-button .fi-sidebar-item-icon { transition: transform .15s ease; }
.fi-sidebar-item-button:hover .fi-sidebar-item-icon { transform: translateX(1px) scale(1.05); }

.fi-sidebar-group-label {
    text-transform: uppercase !important;
    letter-spacing: 0.07em !important;
    font-size: 0.66rem !important;
    font-weight: 700 !important;
    color: #94a3b8 !important;
}

/* ---------- Headings ---------- */
.fi-header-heading { font-weight: 800 !important; letter-spacing: -0.025em !important; }

/* ---------- Cards / sections / tables ---------- */
.fi-section,
.fi-wi-stats-overview-stat,
.fi-ta,
.fi-modal-window {
    border-radius: 1.1rem !important;
    border: 1px solid rgba(15,23,42,0.05) !important;
    box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 22px 42px -30px rgba(15,23,42,0.30) !important;
}

/* ---------- Stat cards ---------- */
.fi-wi-stats-overview-stat {
    background: #ffffff !important;
    padding: 1.25rem 1.4rem !important;
    transition: transform .22s cubic-bezier(.16,1,.3,1), box-shadow .22s ease !important;
    position: relative;
    overflow: hidden;
}
.fi-wi-stats-overview-stat::before {
    content: '';
    position: absolute; top: 0; bottom: 0; left: 0; width: 4px;
    background: linear-gradient(180deg, #14b8a6, #0ea5e9);
}
.fi-wi-stats-overview-stat:hover {
    transform: translateY(-4px);
    box-shadow: 0 26px 46px -22px rgba(13,148,136,0.42) !important;
}
.fi-wi-stats-overview-stat-value { font-weight: 800 !important; letter-spacing: -0.03em !important; }

/* ---------- Buttons ---------- */
.fi-btn { border-radius: 0.7rem !important; font-weight: 600 !important; }
.fi-btn.fi-color-primary { box-shadow: 0 8px 18px -10px rgba(13,148,136,0.65) !important; }

/* ---------- Inputs ---------- */
.fi-input-wrp, .fi-fo-field-wrp .fi-input, .fi-select-input { border-radius: 0.7rem !important; }

/* ---------- Table polish ---------- */
.fi-ta-header-cell { text-transform: uppercase; letter-spacing: .04em; font-size: .68rem; }
.fi-ta-row { transition: background .12s ease; }
.fi-ta-row:hover { background: rgba(20,184,166,0.045) !important; }

/* ---------- Badges ---------- */
.fi-badge { border-radius: 9999px !important; }
</style>
