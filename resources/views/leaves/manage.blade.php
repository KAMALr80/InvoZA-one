@extends('layouts.app')

@section('page-title', 'Manage Leave Requests')

@section('content')
<style>
    /* ================= PROFESSIONAL DESIGN SYSTEM ================= */
    :root {
        --primary: #007bff;
        --primary-dark: #0056b3;
        --success: #28a745;
        --success-dark: #218838;
        --danger: #dc3545;
        --danger-dark: #c82333;
        --warning: #ffc107;
        --warning-dark: #e0a800;
        --info: #17a2b8;
        --text-main: #2c3e50;
        --text-muted: #6b7280;
        --border: #ddd;
        --bg-light: #f8f9fa;
        --bg-white: #ffffff;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        --radius-xl: 12px;
        --font-sans: 'Segoe UI', Arial, -apple-system, BlinkMacSystemFont, sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: var(--font-sans);
        background: #f4f6f9;
        color: var(--text-main);
        line-height: 1.5;
    }

    /* ================= MAIN CONTAINER ================= */
    .leave-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: clamp(16px, 3vw, 30px);
        width: 100%;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* ================= HEADER CARD ================= */
    .header-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-xl);
        padding: clamp(20px, 4vw, 30px);
        margin-bottom: 30px;
        box-shadow: var(--shadow-lg);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .header-icon {
        width: clamp(50px, 8vw, 60px);
        height: clamp(50px, 8vw, 60px);
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(24px, 4vw, 30px);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .header-title h1 {
        margin: 0;
        font-size: clamp(24px, 5vw, 28px);
        font-weight: 700;
        color: white;
        word-break: break-word;
    }

    .header-title p {
        margin: 5px 0 0 0;
        font-size: clamp(14px, 3vw, 16px);
        opacity: 0.9;
        word-break: break-word;
    }

    .stats-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 600;
        font-size: clamp(14px, 3vw, 16px);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        white-space: nowrap;
    }

    /* ================= STATS CARDS ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: clamp(16px, 3vw, 20px);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .stat-icon {
        width: clamp(45px, 7vw, 50px);
        height: clamp(45px, 7vw, 50px);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(20px, 4vw, 24px);
        flex-shrink: 0;
    }

    .stat-icon.pending {
        background: #fff3cd;
        color: #856404;
    }

    .stat-icon.approved {
        background: #d4edda;
        color: #155724;
    }

    .stat-icon.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: clamp(12px, 2.5vw, 13px);
        color: var(--text-muted);
        margin-bottom: 5px;
        font-weight: 500;
        word-break: break-word;
    }

    .stat-value {
        font-size: clamp(24px, 5vw, 28px);
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
        word-break: break-word;
    }

    .stat-total {
        font-size: clamp(11px, 2vw, 12px);
        color: var(--text-muted);
        margin-top: 5px;
    }

    /* ================= FILTERS SECTION ================= */
    .filters-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: clamp(16px, 3vw, 20px);
        margin-bottom: 30px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .filters-wrapper {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .filters-left {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-light);
        padding: 5px 10px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .filter-select {
        padding: 8px 12px;
        border: none;
        background: transparent;
        font-size: 14px;
        color: var(--text-main);
        cursor: pointer;
        outline: none;
        min-width: 130px;
    }

    .search-box {
        position: relative;
        min-width: 250px;
    }

    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    /* ================= REPORT CARD ================= */
    .report-card {
        background: var(--bg-white);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        width: 100%;
    }

    .report-header {
        padding: clamp(16px, 3vw, 20px) clamp(20px, 4vw, 25px);
        background: linear-gradient(135deg, var(--bg-light) 0%, #e9ecef 100%);
        border-bottom: 2px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .report-title {
        margin: 0;
        font-size: clamp(18px, 4vw, 20px);
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-title span {
        background: var(--primary);
        width: 6px;
        height: 24px;
        border-radius: 3px;
        display: inline-block;
    }

    .entries-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .entries-label {
        font-size: 14px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .entries-select {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 14px;
        outline: none;
        background: white;
        cursor: pointer;
    }

    .entries-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    /* ================= TABLE ================= */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .leave-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .leave-table thead tr {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }

    .leave-table th {
        padding: 15px 12px;
        font-size: 14px;
        font-weight: 600;
        text-align: left;
        border: 1px solid var(--primary-dark);
        white-space: nowrap;
    }

    .leave-table td {
        padding: 15px 12px;
        border: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
        transition: background 0.3s ease;
    }

    .leave-table tbody tr:hover td {
        background: #f1f9ff;
    }

    /* ================= EMPLOYEE INFO ================= */
    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
    }

    .employee-name {
        font-weight: 600;
        color: var(--text-main);
        word-break: break-word;
    }

    /* ================= DATE BADGE ================= */
    .date-badge {
        background: var(--bg-light);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-main);
        border: 1px solid var(--border);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .date-badge i {
        font-size: 12px;
        color: var(--primary);
    }

    /* =====