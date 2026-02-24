@extends('layouts.app')

@section('page-title', 'Customer Management')

@section('content')
<style>
    /* ================= PROFESSIONAL DESIGN SYSTEM ================= */
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --success: #16a34a;
        --danger: #dc2626;
        --warning: #d97706;
        --info: #3b82f6;
        --purple: #7c3aed;
        --pink: #ec4899;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --radius-xl: 16px;
        --radius-2xl: 24px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f1f5f9;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        color: var(--text-main);
        line-height: 1.5;
    }

    /* ================= CONTAINER ================= */
    .customers-wrapper {
        min-height: 100vh;
        background: #f1f5f9;
        padding: 2rem 1rem;
    }

    .customers-container {
        max-width: 1400px;
        margin: 0 auto;
        background: var(--bg-white);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        padding: 2rem;
    }

    /* ================= HEADER SECTION ================= */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--border);
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .title-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .title-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: var(--shadow-md);
    }

    .title-content h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .title-content p {
        margin: 0.25rem 0 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        background: white;
        color: var(--text-main);
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        border: 1px solid var(--border);
        transition: all 0.2s;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* ================= SUMMARY CARDS ================= */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .summary-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .summary-icon.advance { background: #dcfce7; color: var(--success); }
    .summary-icon.customers { background: #dbeafe; color: var(--primary); }
    .summary-icon.transactions { background: #fef3c7; color: var(--warning); }

    .summary-details {
        flex: 1;
    }

    .summary-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .summary-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
    }

    .summary-sub {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    /* ================= QUICK ACTIONS ================= */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .quick-action {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .quick-action:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .quick-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }

    .quick-title {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .quick-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-white);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(37,99,235,0.05), transparent 70%);
        border-radius: 50%;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ================= TABLE SECTION ================= */
    .table-wrapper {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-top: 1.5rem;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-search {
        display: flex;
        gap: 0.5rem;
    }

    .table-search input {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        width: 250px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .table-search input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    /* ================= CUSTOM TABLE ================= */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .custom-table thead th {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }

    .custom-table tbody td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: var(--bg-light);
    }

    .customer-info {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
        box-shadow: var(--shadow-sm);
    }

    .customer-details {
        line-height: 1.4;
    }

    .customer-name {
        font-weight: 600;
        color: var(--text-main);
        font-size: 1rem;
    }

    .customer-id {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* ================= WALLET BADGE ================= */
    .wallet-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        background: #f1f5f9;
        color: var(--text-main);
    }

    .wallet-badge.positive {
        background: #dcfce7;
        color: var(--success);
        border-color: #86efac;
    }

    .wallet-badge.zero {
        background: #f1f5f9;
        color: var(--text-muted);
        border-color: var(--border);
    }

    .wallet-badge:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-sm);
    }

    /* ================= ACTION DROPDOWN ================= */
    .action-container {
        position: relative;
        display: inline-block;
    }

    .main-action-btn {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: var(--shadow-sm);
    }

    .main-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        min-width: 220px;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .action-container.active .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: var(--bg-light);
    }

    .dropdown-item.view { color: var(--info); }
    .dropdown-item.edit { color: var(--primary); }
    .dropdown-item.wallet-add { color: var(--success); }
    .dropdown-item.wallet-use { color: var(--purple); }
    .dropdown-item.delete { color: var(--danger); }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-muted);
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
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
        background: white;
    }

    .pagination .page-link:hover {
        border-color: var(--primary);
        background: var(--bg-light);
    }

    .pagination .active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* ================= MODALS ================= */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: var(--radius-xl);
        max-width: 500px;
        width: 90%;
        max-height: 85vh;
        overflow-y: auto;
        padding: 2rem;
        position: relative;
        animation: modalSlideUp 0.3s ease;
        box-shadow: var(--shadow-lg);
    }

    @keyframes modalSlideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: var(--text-muted);
        line-height: 1;
    }

    .modal-close:hover {
        color: var(--danger);
    }

    .balance-display {
        background: linear-gradient(135deg, var(--purple), var(--primary));
        color: white;
        padding: 2rem;
        border-radius: var(--radius-lg);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .balance-label {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .balance-amount {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .balance-customer {
        font-size: 1rem;
        opacity: 0.9;
    }

    .wallet-action-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .wallet-action-item {
        background: var(--bg-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border);
    }

    .wallet-action-item:hover {
        border-color: var(--primary);
        background: white;
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .wallet-action-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .wallet-action-icon.credit { color: var(--success); }
    .wallet-action-icon.debit { color: var(--danger); }

    .wallet-action-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .wallet-action-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .transaction-history {
        margin-top: 1.5rem;
        border-top: 1px solid var(--border);
        padding-top: 1.5rem;
    }

    .transaction-history h4 {
        margin: 0 0 1rem;
        font-size: 1.1rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        transition: all 0.2s;
    }

    .transaction-item:hover {
        background: var(--bg-light);
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .transaction-icon.credit { background: #dcfce7; color: var(--success); }
    .transaction-icon.debit { background: #fee2e2; color: var(--danger); }

    .transaction-details {
        flex: 1;
    }

    .transaction-type {
        font-weight: 600;
        margin-bottom: 0.15rem;
    }

    .transaction-ref {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .transaction-amount {
        font-weight: 700;
        font-size: 1rem;
    }

    .transaction-amount.credit { color: var(--success); }
    .transaction-amount.debit { color: var(--danger); }

    .transaction-balance {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    .amount-input {
        margin-bottom: 1.25rem;
    }

    .amount-input label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .amount-input input,
    .amount-input select,
    .amount-input textarea {
        width: 100%;
        padding: 0.875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .amount-input input:focus,
    .amount-input select:focus,
    .amount-input textarea:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .amount-input input[type="number"] {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .quick-amounts {
        display: flex;
        gap: 0.5rem;
        margin: 1rem 0;
        flex-wrap: wrap;
    }

    .quick-amount-btn {
        flex: 1;
        min-width: 70px;
        padding: 0.625rem;
        background: #f1f5f9;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-main);
    }

    .quick-amount-btn:hover {
        background: var(--primary);
        color: white;
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

    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .modal-actions button {
        flex: 1;
        padding: 1rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .btn-confirm {
        background: var(--primary);
        color: white;
    }

    .btn-confirm:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: var(--text-main);
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    /* ================= TOAST ================= */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        background: white;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 11000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
        min-width: 300px;
    }

    .toast.success { border-left-color: var(--success); }
    .toast.error { border-left-color: var(--danger); }
    .toast.warning { border-left-color: var(--warning); }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ================= LOADING SPINNER ================= */
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 1rem auto;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1024px) {
        .customers-container {
            padding: 1.5rem;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-search {
            width: 100%;
        }

        .table-search input {
            width: 100%;
        }

        .custom-table {
            display: block;
            overflow-x: auto;
        }

        .wallet-action-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .customers-container {
            padding: 1rem;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .balance-amount {
            font-size: 2.5rem;
        }
    }
</style>

<div class="customers-wrapper">
    <div class="customers-container">
        {{-- Loading Overlay --}}
        <div id="loadingOverlay" class="modal-overlay">
            <div class="spinner"></div>
        </div>

        {{-- Header Section --}}
        <div class="header-section">
            <div class="title-wrapper">
                <div class="title-icon">👥</div>
                <div class="title-content">
                    <h1>Customer Management</h1>
                    <p>Manage customers, track wallet balances, and view transaction history</p>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('wallet.report') }}" class="btn-outline">
                    <span>📊</span> Wallet Report
                </a>
                <a href="{{ route('customers.create') }}" class="btn-primary">
                    <span>➕</span> Add Customer
                </a>
            </div>
        </div>

        {{-- Wallet Summary --}}
        @php
            $totalWalletBalance = 0;
            $customersWithWallet = 0;
            $totalTransactions = 0;

            foreach ($customers as $c) {
                $balance = $c->getCurrentWalletBalanceAttribute();
                if ($balance > 0) {
                    $totalWalletBalance += $balance;
                    $customersWithWallet++;
                }
                $totalTransactions += $c->wallet()->count();
            }
        @endphp

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon advance">💰</div>
                <div class="summary-details">
                    <div class="summary-label">Total Wallet Balance</div>
                    <div class="summary-value">₹{{ number_format($totalWalletBalance, 2) }}</div>
                    <div class="summary-sub">Across {{ $customersWithWallet }} customers</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon customers">👥</div>
                <div class="summary-details">
                    <div class="summary-label">Total Customers</div>
                    <div class="summary-value">{{ $customers->total() }}</div>
                    <div class="summary-sub">{{ $customersWithWallet }} with wallet balance</div>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon transactions">📝</div>
                <div class="summary-details">
                    <div class="summary-label">Total Transactions</div>
                    <div class="summary-value">{{ $totalTransactions }}</div>
                    <div class="summary-sub">Wallet transactions</div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="quick-actions">
            <div class="quick-action" onclick="filterByWallet('positive')">
                <div class="quick-icon">💰</div>
                <div class="quick-title">With Balance</div>
                <div class="quick-desc">Show customers having wallet balance</div>
            </div>
            <div class="quick-action" onclick="filterByWallet('zero')">
                <div class="quick-icon">🔄</div>
                <div class="quick-title">Zero Balance</div>
                <div class="quick-desc">Show customers with zero balance</div>
            </div>
            <div class="quick-action" onclick="exportCustomerList()">
                <div class="quick-icon">📥</div>
                <div class="quick-title">Export</div>
                <div class="quick-desc">Download customer list</div>
            </div>
            <div class="quick-action" onclick="resetFilters()">
                <div class="quick-icon">🔄</div>
                <div class="quick-title">Reset</div>
                <div class="quick-desc">Clear all filters</div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--primary)">👥</div>
                <div class="stat-value">{{ $customers->total() }}</div>
                <div class="stat-label">Total Customers</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--success)">💰</div>
                <div class="stat-value">₹{{ number_format($totalRevenue ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--purple)">👛</div>
                <div class="stat-value">{{ $customersWithWallet }}</div>
                <div class="stat-label">Active Wallets</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--info)">📊</div>
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Transactions</div>
            </div>
        </div>

        {{-- Customers Table --}}
        <div class="table-wrapper">
            <div class="table-header">
                <h2>
                    <span>👥</span> Customer List
                </h2>
                <div class="table-search">
                    <input type="text" id="tableSearch" placeholder="Search customers..." onkeyup="searchTable(this.value)">
                </div>
            </div>

            <table class="custom-table" id="customersTable">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>GST</th>
                        <th>Wallet Balance</th>
                        <th>Last Transaction</th>
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
                                <div class="customer-info">
                                    <div class="customer-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $customer->name }}</div>
                                        <div class="customer-id">ID: {{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->mobile ?? 'N/A' }}</td>
                            <td>{{ $customer->email ?? 'N/A' }}</td>
                            <td>{{ $customer->gst_no ?? 'N/A' }}</td>
                            <td>
                                <button class="wallet-badge {{ $walletBalance > 0 ? 'positive' : 'zero' }}"
                                        onclick="showWalletModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                    <span>💰</span>
                                    ₹{{ number_format($walletBalance, 2) }}
                                    <span style="font-size: 10px;">▼</span>
                                </button>
                            </td>
                            <td>
                                @if($lastTransaction)
                                    <span title="{{ $lastTransaction->created_at->format('d M Y H:i') }}">
                                        {{ $lastTransaction->created_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">No transactions</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-container" data-row-index="{{ $index }}">
                                    <button class="main-action-btn" onclick="toggleDropdown(this, event)">
                                        <span>Actions</span>
                                        <span style="font-size: 10px;">▼</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('customers.sales', $customer->id) }}" class="dropdown-item view">
                                            <span>👁️</span> View Details
                                        </a>
                                        <button class="dropdown-item wallet-add" onclick="showAddAdvanceModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                            <span>➕</span> Add to Wallet
                                        </button>
                                        @if($walletBalance > 0)
                                        <button class="dropdown-item wallet-use" onclick="showUseAdvanceModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $walletBalance }})">
                                            <span>🔄</span> Use from Wallet
                                        </button>
                                        @endif
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="dropdown-item edit">
                                            <span>✏️</span> Edit Customer
                                        </a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" style="margin:0;" onsubmit="return confirmDelete(event, '{{ $customer->name }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item delete">
                                                <span>🗑️</span> Delete Customer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">📭</div>
                                    <div class="empty-title">No customers found</div>
                                    <div class="empty-text">Add your first customer to get started</div>
                                    <a href="{{ route('customers.create') }}" class="btn-primary">Add Customer</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if(method_exists($customers, 'links'))
                <div class="pagination">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Wallet Modal --}}
<div id="walletModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>💰 Customer Wallet</h3>
            <button class="modal-close" onclick="closeWalletModal()">&times;</button>
        </div>

        <div id="walletCustomerInfo" class="balance-display">
            <!-- Filled by JS -->
        </div>

        <div class="wallet-action-grid">
            <div class="wallet-action-item" onclick="proceedToAdd()">
                <div class="wallet-action-icon credit">➕</div>
                <div class="wallet-action-title">Add Money</div>
                <div class="wallet-action-desc">Credit to wallet</div>
            </div>
            <div class="wallet-action-item" onclick="proceedToUse()" id="useWalletBtn">
                <div class="wallet-action-icon debit">➖</div>
                <div class="wallet-action-title">Use Money</div>
                <div class="wallet-action-desc">Debit from wallet</div>
            </div>
        </div>

        <div class="transaction-history">
            <h4>
                <span>📋</span> Recent Transactions
            </h4>
            <div id="transactionList" class="transaction-list">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
</div>

{{-- Amount Modal --}}
<div id="amountModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="amountModalTitle">Add to Wallet</h3>
            <button class="modal-close" onclick="closeAmountModal()">&times;</button>
        </div>

        <form id="walletForm" method="POST" action="">
            @csrf
            <input type="hidden" name="customer_id" id="walletCustomerId">

            <div class="amount-input">
                <label id="amountLabel">Enter Amount (₹)</label>
                <input type="number" name="amount" id="walletAmount" step="0.01" min="1" required
                       placeholder="0.00">
            </div>

            <div class="quick-amounts">
                <button type="button" class="quick-amount-btn" onclick="setAmount(500)">₹500</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(1000)">₹1,000</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(2000)">₹2,000</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(5000)">₹5,000</button>
                <button type="button" class="quick-amount-btn" onclick="setAmount(10000)">₹10,000</button>
            </div>

            <div id="paymentMethodSection">
                <div class="amount-input">
                    <label>Payment Method</label>
                    <select name="method" id="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="net_banking">Net Banking</option>
                    </select>
                </div>

                <div class="amount-input">
                    <label>Transaction Reference (Optional)</label>
                    <input type="text" name="transaction_id" placeholder="UTR / Reference No.">
                </div>
            </div>

            <div class="amount-input">
                <label>Remarks (Optional)</label>
                <textarea name="remarks" rows="2" placeholder="Add note..."></textarea>
            </div>

            <div id="useAdvanceInfo" class="info-box" style="display: none;">
                <strong>⚠️ Note:</strong> This will deduct amount from wallet balance
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeAmountModal()">Cancel</button>
                <button type="submit" class="btn-confirm" id="confirmBtn">Add to Wallet</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast" style="display: none;">
    <span id="toastIcon">✅</span>
    <span id="toastMessage"></span>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    // Global variables
    let currentCustomerId = null;
    let currentCustomerName = '';
    let currentBalance = 0;

    // Toggle dropdown
    function toggleDropdown(button, event) {
        event.stopPropagation();
        var container = $(button).closest('.action-container');
        $('.action-container').not(container).removeClass('active');
        container.toggleClass('active');
    }

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.action-container').length) {
            $('.action-container').removeClass('active');
        }
    });

    // Search table
    function searchTable(query) {
        query = query.toLowerCase();
        $('#customersTable tbody tr').each(function() {
            let text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    }

    // Filter by wallet status
    function filterByWallet(type) {
        $('#customersTable tbody tr').each(function() {
            if (type === 'positive') {
                $(this).toggle($(this).data('wallet') === 'positive');
            } else if (type === 'zero') {
                $(this).toggle($(this).data('wallet') === 'zero');
            }
        });
    }

    // Reset filters
    function resetFilters() {
        $('#customersTable tbody tr').show();
        $('#tableSearch').val('');
    }

    // Export customer list
    function exportCustomerList() {
        let csv = "Name,Mobile,Email,GST,Wallet Balance\n";
        $('#customersTable tbody tr').each(function() {
            let name = $(this).find('.customer-name').text().replace(/,/g, ' ');
            let mobile = $(this).find('td:eq(1)').text();
            let email = $(this).find('td:eq(2)').text();
            let gst = $(this).find('td:eq(3)').text();
            let balance = $(this).find('.wallet-badge').text().replace('₹', '').replace('▼', '').trim();

            csv += `"${name}",${mobile},${email},${gst},${balance}\n`;
        });

        let blob = new Blob([csv], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'customers.csv';
        a.click();
    }

    // Confirm delete
    function confirmDelete(event, customerName) {
        if (!confirm(`Are you sure you want to delete ${customerName}? This will delete all associated sales, payments, and wallet transactions.`)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        let toast = $('#toast');
        $('#toastMessage').text(message);

        toast.removeClass('success error warning').addClass(type);

        if (type === 'success') $('#toastIcon').text('✅');
        else if (type === 'error') $('#toastIcon').text('❌');
        else if (type === 'warning') $('#toastIcon').text('⚠️');

        toast.fadeIn(300);

        setTimeout(() => {
            toast.fadeOut(300);
        }, 3000);
    }

    // Show wallet modal
    function showWalletModal(customerId, customerName, balance) {
        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        $('#walletCustomerInfo').html(`
            <div class="balance-label">Current Wallet Balance</div>
            <div class="balance-amount">₹${balance.toFixed(2)}</div>
            <div class="balance-customer">${customerName}</div>
        `);

        if (balance > 0) {
            $('#useWalletBtn').show();
        } else {
            $('#useWalletBtn').hide();
        }

        $('#walletModal').addClass('active');

        // Load transaction history
        loadWalletHistory(customerId);
    }

    function closeWalletModal() {
        $('#walletModal').removeClass('active');
    }

    // Load wallet history
    function loadWalletHistory(customerId) {
        $('#transactionList').html('<div class="spinner"></div>');

        fetch(`/customers/wallet/history/${customerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.history && data.history.length > 0) {
                    let html = '';
                    data.history.forEach(trans => {
                        html += `
                            <div class="transaction-item">
                                <div class="transaction-icon ${trans.type}">
                                    ${trans.type === 'credit' ? '➕' : '➖'}
                                </div>
                                <div class="transaction-details">
                                    <div class="transaction-type">${trans.type === 'credit' ? 'Credit' : 'Debit'}</div>
                                    <div class="transaction-ref">${trans.reference || 'No reference'}</div>
                                    <div class="transaction-balance">Balance: ₹${parseFloat(trans.balance).toFixed(2)}</div>
                                </div>
                                <div class="transaction-amount ${trans.type}">
                                    ${trans.type === 'credit' ? '+' : '-'}₹${parseFloat(trans.amount).toFixed(2)}
                                </div>
                            </div>
                        `;
                    });
                    $('#transactionList').html(html);
                } else {
                    $('#transactionList').html('<div style="text-align: center; padding: 2rem; color: var(--text-muted);">No transactions yet</div>');
                }
            })
            .catch(error => {
                $('#transactionList').html('<div style="color: var(--danger); text-align: center; padding: 1rem;">Error loading transactions</div>');
                showToast('Error loading transaction history', 'error');
            });
    }

    // Show add advance modal
    function showAddAdvanceModal(customerId, customerName, balance) {
        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        $('#walletCustomerId').val(customerId);
        $('#amountModalTitle').text('💰 Add to Wallet');
        $('#amountLabel').text('Enter Amount to Add (₹)');
        $('#confirmBtn').text('Add to Wallet');
        $('#paymentMethodSection').show();
        $('#useAdvanceInfo').hide();

        $('#walletForm').attr('action', '{{ route("wallet.add") }}');
        $('#walletForm')[0].reset();

        closeWalletModal();
        $('#amountModal').addClass('active');
    }

    // Show use advance modal
    function showUseAdvanceModal(customerId, customerName, balance) {
        if (balance <= 0) {
            showToast('No wallet balance available!', 'warning');
            return;
        }

        currentCustomerId = customerId;
        currentCustomerName = customerName;
        currentBalance = balance;

        $('#walletCustomerId').val(customerId);
        $('#amountModalTitle').text('🔄 Use from Wallet');
        $('#amountLabel').text('Enter Amount to Use (₹)');
        $('#confirmBtn').text('Use from Wallet');
        $('#paymentMethodSection').hide();
        $('#useAdvanceInfo').show();

        $('#walletForm').attr('action', '{{ route("wallet.use") }}');
        $('#walletForm')[0].reset();

        closeWalletModal();
        $('#amountModal').addClass('active');
    }

    function closeAmountModal() {
        $('#amountModal').removeClass('active');
        $('#walletForm')[0].reset();
    }

    function setAmount(amount) {
        $('#walletAmount').val(amount);
    }

    function proceedToAdd() {
        showAddAdvanceModal(currentCustomerId, currentCustomerName, currentBalance);
    }

    function proceedToUse() {
        if (currentBalance <= 0) {
            showToast('No wallet balance available!', 'warning');
            return;
        }
        showUseAdvanceModal(currentCustomerId, currentCustomerName, currentBalance);
    }

    // Handle form submission
    $('#walletForm').on('submit', function(e) {
        e.preventDefault();

        let amount = $('#walletAmount').val();
        if (!amount || amount <= 0) {
            showToast('Please enter a valid amount', 'warning');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                closeAmountModal();
                showToast(response.message || 'Transaction successful!');

                // Reload the page to update balances
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                let message = xhr.responseJSON?.message || 'Transaction failed!';
                showToast(message, 'error');
            }
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
