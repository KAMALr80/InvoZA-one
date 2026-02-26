@extends('layouts.app')

@section('page-title', 'Customer Management')

@section('content')
<style>
    /* ================= PROFESSIONAL DESIGN SYSTEM ================= */
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --secondary: #334155;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #059669;
        --danger: #dc2626;
        --warning: #d97706;
        --info: #2563eb;
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #64748b;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --border: #e2e8f0;
        --border-dark: #cbd5e1;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        --radius-xl: 12px;
        --font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-secondary);
        font-family: var(--font-sans);
        color: var(--text-primary);
        line-height: 1.5;
    }

    /* ================= MAIN CONTAINER ================= */
    .customer-dashboard {
        max-width: 1440px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        width: 100%;
    }

    /* ================= HEADER SECTION ================= */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem;
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        width: 100%;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .header-title h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem;
        letter-spacing: -0.01em;
    }

    .header-title p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .btn-primary:hover {
        background: var(--info);
        border-color: var(--info);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--bg-secondary);
        color: var(--text-primary);
        border-color: var(--border);
    }

    .btn-secondary:hover {
        background: var(--bg-tertiary);
        border-color: var(--border-dark);
        transform: translateY(-1px);
    }

    /* ================= METRICS GRID ================= */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
        width: 100%;
    }

    .metric-card {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        transition: all 0.2s;
        width: 100%;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent-light);
    }

    .metric-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .metric-icon.total { background: #e0f2fe; color: #0369a1; }
    .metric-icon.balance { background: #dcfce7; color: #059669; }
    .metric-icon.wallet { background: #fef3c7; color: #b45309; }
    .metric-icon.transactions { background: #e0e7ff; color: #4f46e5; }

    .metric-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.2;
        word-break: break-word;
    }

    .metric-sub {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        word-break: break-word;
    }

    /* ================= FILTERS SECTION ================= */
    .filters-section {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        width: 100%;
    }

    .filter-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--bg-primary);
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .filter-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .filter-btn.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--bg-secondary);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        width: auto;
        min-width: 250px;
    }

    .search-box input {
        border: none;
        background: none;
        outline: none;
        font-size: 0.9rem;
        color: var(--text-primary);
        width: 100%;
    }

    .search-box input::placeholder {
        color: var(--text-muted);
    }

    .search-box span {
        color: var(--text-muted);
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ================= TABLE SECTION ================= */
    .table-container {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        width: 100%;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-secondary);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .table-header h2 span {
        color: var(--text-muted);
        font-weight: normal;
        font-size: 0.9rem;
    }

    .export-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--bg-primary);
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .export-btn:hover {
        background: var(--bg-secondary);
        border-color: var(--border-dark);
    }

    /* ================= CUSTOM TABLE ================= */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .data-table thead th {
        background: var(--bg-secondary);
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        color: var(--text-primary);
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: var(--bg-secondary);
    }

    /* ================= CUSTOMER INFO ================= */
    .customer-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
        white-space: nowrap;
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .customer-details {
        line-height: 1.4;
    }

    .customer-name {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.15rem;
    }

    .customer-id {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* ================= WALLET BADGE ================= */
    .wallet-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-lg);
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .wallet-badge.positive {
        background: #dcfce7;
        color: #059669;
        border-color: #86efac;
    }

    .wallet-badge.zero {
        background: var(--bg-tertiary);
        color: var(--text-muted);
        border-color: var(--border);
    }

    .wallet-badge:hover {
        transform: scale(1.02);
        filter: brightness(0.98);
    }

    /* ================= ACTION DROPDOWN ================= */
    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .action-trigger {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--bg-primary);
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .action-trigger:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .action-menu {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        min-width: 200px;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s;
    }

    .action-dropdown.active .action-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
        border-bottom: 1px solid var(--border);
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
    }

    .action-item:last-child {
        border-bottom: none;
    }

    .action-item:hover {
        background: var(--bg-secondary);
    }

    .action-item.view { color: var(--info); }
    .action-item.edit { color: var(--warning); }
    .action-item.wallet-add { color: var(--success); }
    .action-item.wallet-use { color: var(--primary); }
    .action-item.delete { color: var(--danger); }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    /* ================= PAGINATION ================= */
    .pagination {
        display: flex;
        justify-content: flex-end;
        gap: 0.25rem;
        padding: 1.5rem;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: var(--bg-primary);
        display: inline-block;
    }

    .pagination .page-link:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .pagination .active .page-link {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    /* ================= MODALS ================= */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
        position: relative;
        box-shadow: var(--shadow-lg);
        margin: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-muted);
        line-height: 1;
        padding: 0.5rem;
    }

    .modal-close:hover {
        color: var(--danger);
    }

    .balance-info {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 2rem;
        border-radius: var(--radius-lg);
        text-align: center;
        margin-bottom: 2rem;
    }

    .balance-label {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .balance-amount {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        word-break: break-word;
    }

    .balance-customer {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .wallet-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .wallet-action-card {
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .wallet-action-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .wallet-action-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .wallet-action-icon.credit { color: var(--success); }
    .wallet-action-icon.debit { color: var(--danger); }

    .wallet-action-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .wallet-action-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .transaction-history {
        border-top: 1px solid var(--border);
        padding-top: 1.5rem;
    }

    .transaction-history h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .transaction-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .transaction-icon.credit { background: #dcfce7; color: var(--success); }
    .transaction-icon.debit { background: #fee2e2; color: var(--danger); }

    .transaction-details {
        flex: 1;
        min-width: 150px;
    }

    .transaction-type {
        font-weight: 500;
        margin-bottom: 0.15rem;
    }

    .transaction-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .transaction-amount {
        font-weight: 600;
        white-space: nowrap;
    }

    .transaction-amount.credit { color: var(--success); }
    .transaction-amount.debit { color: var(--danger); }

    /* ================= FORM ELEMENTS ================= */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .quick-amounts {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .quick-amount-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--bg-primary);
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1 1 auto;
    }

    .quick-amount-btn:hover {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .info-box {
        background: #eff6ff;
        padding: 1rem;
        border-radius: var(--radius-md);
        color: #1e40af;
        margin: 1rem 0;
        font-size: 0.9rem;
        border-left: 4px solid var(--info);
    }

    .modal-footer {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .modal-footer button {
        flex: 1;
        padding: 0.875rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        cursor: pointer;
        border: none;
        font-size: 0.95rem;
        transition: all 0.2s;
        min-width: 120px;
    }

    .btn-confirm {
        background: var(--accent);
        color: white;
    }

    .btn-confirm:hover {
        background: var(--info);
    }

    .btn-cancel {
        background: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    .btn-cancel:hover {
        background: var(--border);
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        background: white;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 11000;
        min-width: 300px;
        max-width: 90%;
        border-left: 4px solid;
        animation: slideIn 0.3s ease;
    }

    .toast-notification.success { border-left-color: var(--success); }
    .toast-notification.error { border-left-color: var(--danger); }
    .toast-notification.warning { border-left-color: var(--warning); }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ================= RESPONSIVE BREAKPOINTS ================= */
    
    /* Large Desktop (1200px and above) */
    @media (min-width: 1200px) {
        .metrics-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Desktop (992px to 1199px) */
    @media (max-width: 1199px) {
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .metric-value {
            font-size: 1.5rem;
        }
    }

    /* Tablet (768px to 991px) */
    @media (max-width: 991px) {
        .customer-dashboard {
            padding: 0 1rem;
        }

        .dashboard-header {
            padding: 1.25rem;
        }

        .header-title h1 {
            font-size: 1.5rem;
        }

        .metrics-grid {
            gap: 1rem;
        }

        .metric-card {
            padding: 1.25rem;
        }

        .metric-value {
            font-size: 1.25rem;
        }

        .filter-group {
            width: 100%;
            justify-content: flex-start;
        }

        .search-box {
            width: 100%;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .export-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Mobile Landscape (576px to 767px) */
    @media (max-width: 767px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn {
            flex: 1;
            justify-content: center;
        }

        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .filters-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-group {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
        }

        .filter-btn {
            flex-shrink: 0;
        }

        .wallet-actions {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer button {
            width: 100%;
        }

        .toast-notification {
            left: 20px;
            right: 20px;
            min-width: auto;
        }
    }

    /* Mobile Portrait (up to 575px) */
    @media (max-width: 575px) {
        .customer-dashboard {
            margin: 1rem auto;
            padding: 0 0.75rem;
        }

        .dashboard-header {
            padding: 1rem;
        }

        .header-left {
            width: 100%;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .header-title h1 {
            font-size: 1.25rem;
        }

        .header-title p {
            font-size: 0.8rem;
        }

        .header-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .metric-card {
            padding: 1rem;
        }

        .metric-header {
            gap: 0.5rem;
        }

        .metric-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }

        .metric-label {
            font-size: 0.75rem;
        }

        .metric-value {
            font-size: 1.1rem;
        }

        .filters-section {
            padding: 1rem;
        }

        .table-header {
            padding: 1rem;
        }

        .table-header h2 {
            font-size: 1rem;
        }

        .table-header h2 span {
            font-size: 0.8rem;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .balance-amount {
            font-size: 2rem;
        }

        .balance-info {
            padding: 1.5rem;
        }

        .wallet-action-card {
            padding: 1rem;
        }

        .transaction-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-details {
            width: 100%;
        }

        .transaction-amount {
            align-self: flex-end;
        }
    }

    /* Extra Small Devices (up to 360px) */
    @media (max-width: 360px) {
        .customer-dashboard {
            padding: 0 0.5rem;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
        }

        .search-box {
            padding: 0.4rem 0.8rem;
        }

        .search-box input {
            font-size: 0.8rem;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="customer-dashboard">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="modal-overlay">
        <div class="modal-content" style="max-width: 300px; text-align: center;">
            <div style="width: 40px; height: 40px; border: 3px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
            <p style="color: var(--text-secondary);">Loading...</p>
        </div>
    </div>

    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-left">
            <div class="header-icon">👥</div>
            <div class="header-title">
                <h1>Customer Management</h1>
                <p>Manage customers and wallet balances</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('wallet.report') }}" class="btn btn-secondary">
                <span>📊</span> Wallet Report
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <span>+</span> Add Customer
            </a>
        </div>
    </div>

    <!-- Metrics -->
    @php
        $totalWalletBalance = 0;
        $customersWithWallet = 0;
        $totalTransactions = 0;

        foreach ($customers as $customer) {
            $balance = $customer->getCurrentWalletBalanceAttribute();
            if ($balance > 0) {
                $totalWalletBalance += $balance;
                $customersWithWallet++;
            }
            $totalTransactions += $customer->wallet()->count();
        }
    @endphp

    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon total">👥</div>
                <span class="metric-label">Total Customers</span>
            </div>
            <div class="metric-value">{{ $customers->total() }}</div>
            <div class="metric-sub">Registered customers</div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon balance">💰</div>
                <span class="metric-label">Wallet Balance</span>
            </div>
            <div class="metric-value">₹{{ number_format($totalWalletBalance, 2) }}</div>
            <div class="metric-sub">{{ $customersWithWallet }} customers with balance</div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon wallet">👛</div>
                <span class="metric-label">Active Wallets</span>
            </div>
            <div class="metric-value">{{ $customersWithWallet }}</div>
            <div class="metric-sub">Customers with wallet</div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon transactions">📋</div>
                <span class="metric-label">Transactions</span>
            </div>
            <div class="metric-value">{{ $totalTransactions }}</div>
            <div class="metric-sub">Wallet transactions</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <button class="filter-btn active" onclick="filterCustomers('all')">All Customers</button>
            <button class="filter-btn" onclick="filterCustomers('with-balance')">With Balance</button>
            <button class="filter-btn" onclick="filterCustomers('zero-balance')">Zero Balance</button>
        </div>
        <div class="search-box">
            <span>🔍</span>
            <input type="text" id="searchInput" placeholder="Search customers..." onkeyup="searchCustomers()">
        </div>
    </div>

    <!-- Customers Table -->
    <div class="table-container">
        <div class="table-header">
            <h2>
                Customer List
                <span>({{ $customers->total() }} records)</span>
            </h2>
            <button class="export-btn" onclick="exportCustomers()">
                <span>📥</span> Export CSV
            </button>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="customersTable">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>GST Number</th>
                        <th>Wallet Balance</th>
                        <th>Last Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $index => $customer)
                        @php
                            $walletBalance = $customer->getCurrentWalletBalanceAttribute();
                            $lastTransaction = $customer->wallet()->first();
                        @endphp
                        <tr data-wallet="{{ $walletBalance > 0 ? 'positive' : 'zero' }}">
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $customer->name }}</div>
                                        <div class="customer-id">ID: {{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem;">{{ $customer->mobile ?? 'N/A' }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $customer->email ?? 'No email' }}</div>
                            </td>
                            <td>{{ $customer->gst_no ?? '—' }}</td>
                            <td>
                                <button class="wallet-badge {{ $walletBalance > 0 ? 'positive' : 'zero' }}" 
                                        onclick="showWalletModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                    <span>💰</span>
                                    ₹{{ number_format($walletBalance, 2) }}
                                </button>
                            </td>
                            <td>
                                @if($lastTransaction)
                                    <span style="font-size: 0.85rem;">{{ $lastTransaction->created_at->diffForHumans() }}</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">No activity</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-dropdown" data-index="{{ $index }}">
                                    <button class="action-trigger" onclick="toggleDropdown(this, event)">
                                        Actions <span>▼</span>
                                    </button>
                                    <div class="action-menu">
                                        <a href="{{ route('customers.sales', $customer->id) }}" class="action-item view">
                                            <span>👁️</span> View Details
                                        </a>
                                        <button class="action-item wallet-add" onclick="showAddAdvanceModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                            <span>➕</span> Add to Wallet
                                        </button>
                                        @if($walletBalance > 0)
                                        <button class="action-item wallet-use" onclick="showUseAdvanceModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                            <span>🔄</span> Use from Wallet
                                        </button>
                                        @endif
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="action-item edit">
                                            <span>✏️</span> Edit Customer
                                        </a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" style="margin:0;" onsubmit="return confirmDelete(event, '{{ addslashes($customer->name) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-item delete">
                                                <span>🗑️</span> Delete Customer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">📭</div>
                                    <div class="empty-title">No customers found</div>
                                    <div class="empty-text">Add your first customer to get started</div>
                                    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($customers, 'links') && $customers->hasPages())
            <div class="pagination">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Wallet Modal -->
<div id="walletModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>💰 Wallet Details</h3>
            <button class="modal-close" onclick="closeWalletModal()">&times;</button>
        </div>

        <div id="walletInfo" class="balance-info"></div>

        <div class="wallet-actions">
            <div class="wallet-action-card" onclick="proceedToAdd()">
                <div class="wallet-action-icon credit">➕</div>
                <div class="wallet-action-title">Add Money</div>
                <div class="wallet-action-desc">Credit to wallet</div>
            </div>
            <div class="wallet-action-card" id="useWalletCard" onclick="proceedToUse()">
                <div class="wallet-action-icon debit">➖</div>
                <div class="wallet-action-title">Use Money</div>
                <div class="wallet-action-desc">Debit from wallet</div>
            </div>
        </div>

        <div class="transaction-history">
            <h4>
                <span>📋</span> Recent Transactions
            </h4>
            <div id="transactionList" class="transaction-list" style="min-height: 100px;">
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">Loading...</div>
            </div>
        </div>
    </div>
</div>

<!-- Amount Modal -->
<div id="amountModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="amountModalTitle">Wallet Transaction</h3>
            <button class="modal-close" onclick="closeAmountModal()">&times;</button>
        </div>

        <form id="walletForm" method="POST">
            @csrf
            <input type="hidden" name="customer_id" id="walletCustomerId">

            <div class="form-group">
                <label id="amountLabel">Amount (₹)</label>
                <input type="number" name="amount" id="walletAmount" class="form-control" 
                       step="0.01" min="1" required placeholder="Enter amount">
            </div>

            <div class="quick-amounts">
                <button type="button" class="quick-amount-btn" onclick="setAmount(500)">₹500</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(1000)">₹1,000</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(2000)">₹2,000</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(5000)">₹5,000</button>
            </div>

            <div id="paymentSection">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="method" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Reference (Optional)</label>
                    <input type="text" name="reference" class="form-control" placeholder="Transaction ID / UTR">
                </div>
            </div>

            <div class="form-group">
                <label>Remarks (Optional)</label>
                <textarea name="remarks" class="form-control" rows="2" placeholder="Add note..."></textarea>
            </div>

            <div id="useAdvanceInfo" class="info-box" style="display: none;">
                <strong>Note:</strong> This amount will be deducted from wallet balance
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAmountModal()">Cancel</button>
                <button type="submit" class="btn-confirm" id="confirmBtn">Process Transaction</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast-notification" style="display: none;">
    <span id="toastIcon">✅</span>
    <span id="toastMessage"></span>
</div>

<script>
    // Global variables
    let currentCustomerId = null;
    let currentCustomerName = '';
    let currentBalance = 0;

    // Toggle dropdown
    function toggleDropdown(button, event) {
        event.stopPropagation();
        const container = button.closest('.action-dropdown');
        document.querySelectorAll('.action-dropdown').forEach(el => {
            if (el !== container) el.classList.remove('active');
        });
        container.classList.toggle('active');
    }

    // Close dropdowns on click outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.action-dropdown').forEach(el => {
            el.classList.remove('active');
        });
    });

    // Search customers
    function searchCustomers() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#customersTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    // Filter customers
    function filterCustomers(type) {
        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        const rows = document.querySelectorAll('#customersTable tbody tr');
        
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'with-balance') {
                row.style.display = row.dataset.wallet === 'positive' ? '' : 'none';
            } else if (type === 'zero-balance') {
                row.style.display = row.dataset.wallet === 'zero' ? '' : 'none';
            }
        });
    }

    // Export customers
    function exportCustomers() {
        const rows = [];
        document.querySelectorAll('#customersTable tbody tr').forEach(row => {
            if (row.style.display !== 'none') {
                const customerName = row.querySelector('.customer-name')?.textContent || '';
                const mobile = row.querySelector('td:nth-child(2) div:first-child')?.textContent || '';
                const email = row.querySelector('td:nth-child(2) div:last-child')?.textContent || '';
                const gst = row.querySelector('td:nth-child(3)')?.textContent || '';
                const balance = row.querySelector('.wallet-badge')?.textContent.replace('💰', '').trim() || '0';
                
                rows.push([customerName, mobile, email, gst, balance]);
            }
        });

        if (rows.length === 0) {
            showToast('No customers to export', 'warning');
            return;
        }

        const csv = ['Name,Mobile,Email,GST,Wallet Balance', ...rows.map(r => r.join(','))].join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'customers.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Confirm delete
    function confirmDelete(event, customerName) {
        if (!confirm(`Are you sure you want to delete "${customerName}"? This will delete all associated records.`)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Show toast
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const icon = document.getElementById('toastIcon');
        const msg = document.getElementById('toastMessage');

        msg.textContent = message;
        icon.textContent = type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️';
        toast.className = `toast-notification ${type}`;
        toast.style.display = 'flex';

        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Wallet modal functions
    function showWalletModal(customerId, customerName, balance) {
        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        document.getElementById('walletInfo').innerHTML = `
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">₹${balance.toFixed(2)}</div>
            <div class="balance-customer">${customerName}</div>
        `;

        document.getElementById('useWalletCard').style.display = balance > 0 ? 'block' : 'none';
        document.getElementById('walletModal').classList.add('active');
        
        loadWalletHistory(customerId);
    }

    function closeWalletModal() {
        document.getElementById('walletModal').classList.remove('active');
    }

    function loadWalletHistory(customerId) {
        const list = document.getElementById('transactionList');
        list.innerHTML = '<div style="text-align: center; padding: 2rem;">Loading...</div>';

        fetch(`/wallet/history/${customerId}`)
            .then(res => res.json())
            .then(data => {
                if (data.history?.length > 0) {
                    list.innerHTML = data.history.map(trans => `
                        <div class="transaction-item">
                            <div class="transaction-icon ${trans.type}">
                                ${trans.type === 'credit' ? '➕' : '➖'}
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-type">${trans.type === 'credit' ? 'Credit' : 'Debit'}</div>
                                <div class="transaction-meta">${trans.reference || 'No reference'}</div>
                                <div class="transaction-meta">Balance: ₹${parseFloat(trans.balance).toFixed(2)}</div>
                            </div>
                            <div class="transaction-amount ${trans.type}">
                                ${trans.type === 'credit' ? '+' : '-'}₹${parseFloat(trans.amount).toFixed(2)}
                            </div>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-muted);">No transactions yet</div>';
                }
            })
            .catch(() => {
                list.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading transactions</div>';
            });
    }

    // Amount modal functions
    function showAddAdvanceModal(customerId, customerName, balance) {
        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        document.getElementById('walletCustomerId').value = customerId;
        document.getElementById('amountModalTitle').textContent = 'Add to Wallet';
        document.getElementById('amountLabel').textContent = 'Amount to Add (₹)';
        document.getElementById('confirmBtn').textContent = 'Add to Wallet';
        document.getElementById('paymentSection').style.display = 'block';
        document.getElementById('useAdvanceInfo').style.display = 'none';
        document.getElementById('walletForm').action = '{{ route("wallet.add") }}';
        document.getElementById('walletForm').reset();

        closeWalletModal();
        document.getElementById('amountModal').classList.add('active');
    }

    function showUseAdvanceModal(customerId, customerName, balance) {
        if (balance <= 0) {
            showToast('No wallet balance available', 'warning');
            return;
        }

        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        document.getElementById('walletCustomerId').value = customerId;
        document.getElementById('amountModalTitle').textContent = 'Use from Wallet';
        document.getElementById('amountLabel').textContent = 'Amount to Use (₹)';
        document.getElementById('confirmBtn').textContent = 'Use from Wallet';
        document.getElementById('paymentSection').style.display = 'none';
        document.getElementById('useAdvanceInfo').style.display = 'block';
        document.getElementById('walletForm').action = '{{ route("wallet.use") }}';
        document.getElementById('walletForm').reset();

        closeWalletModal();
        document.getElementById('amountModal').classList.add('active');
    }

    function closeAmountModal() {
        document.getElementById('amountModal').classList.remove('active');
        document.getElementById('walletForm').reset();
    }

    function setAmount(amount) {
        document.getElementById('walletAmount').value = amount;
    }

    function proceedToAdd() {
        showAddAdvanceModal(currentCustomerId, currentCustomerName, currentBalance);
    }

    function proceedToUse() {
        if (currentBalance <= 0) {
            showToast('No wallet balance available', 'warning');
            return;
        }
        showUseAdvanceModal(currentCustomerId, currentCustomerName, currentBalance);
    }

    // Form submission
    document.getElementById('walletForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const amount = document.getElementById('walletAmount').value;
        if (!amount || amount <= 0) {
            showToast('Please enter a valid amount', 'warning');
            return;
        }

        if (this.action.includes('wallet.use') && amount > currentBalance) {
            showToast('Insufficient wallet balance', 'error');
            return;
        }

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: document.getElementById('walletCustomerId').value,
                amount: amount,
                method: document.querySelector('[name="method"]')?.value || 'cash',
                reference: document.querySelector('[name="reference"]')?.value || '',
                remarks: document.querySelector('[name="remarks"]')?.value || ''
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Transaction successful!');
                closeAmountModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Transaction failed', 'error');
            }
        })
        .catch(() => {
            showToast('Transaction failed', 'error');
        });
    });

    // Check for session messages
    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif

    @if(session('warning'))
        showToast("{{ session('warning') }}", 'warning');
    @endif
</script>
@endsection