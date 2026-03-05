@extends('layouts.app')

@section('page-title', 'Leave Details')

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
        --text-main: #333;
        --text-muted: #666;
        --border: #ddd;
        --bg-light: #f9f9f9;
        --bg-white: #ffffff;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 8px rgba(0,0,0,0.1);
        --shadow-lg: 0 8px 16px rgba(0,0,0,0.1);
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        --radius-xl: 12px;
        --font-sans: Arial, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: var(--font-sans);
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        color: var(--text-main);
        line-height: 1.5;
    }

    /* ================= MAIN CONTAINER ================= */
    .leave-detail-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: clamp(16px, 3vw, 30px);
        width: 100%;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }

    /* ================= HEADER CARD ================= */
    .header-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-lg);
        padding: clamp(20px, 4vw, 25px);
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
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(24px, 4vw, 28px);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .header-title h1 {
        margin: 0;
        font-size: clamp(22px, 5vw, 26px);
        font-weight: 700;
        color: white;
    }

    .header-title p {
        margin: 5px 0 0 0;
        font-size: clamp(13px, 3vw, 14px);
        opacity: 0.9;
    }

    .status-badge-large {
        padding: 10px 25px;
        border-radius: 40px;
        font-weight: 700;
        font-size: 16px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .status-badge-large.pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }

    .status-badge-large.approved {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }

    .status-badge-large.rejected {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .status-badge-large.cancelled {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
    }

    /* ================= LEAVE NUMBER CARD ================= */
    .leave-number-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .leave-number-label {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    .leave-number-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        font-family: monospace;
        background: #e3f2fd;
        padding: 5px 15px;
        border-radius: var(--radius-md);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-action.print {
        background: var(--warning);
        color: #333;
    }

    .btn-action.download {
        background: var(--success);
        color: white;
    }

    .btn-action.back {
        background: #6c757d;
        color: white;
    }

    .btn-action.cancel {
        background: var(--danger);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ================= MAIN GRID ================= */
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    /* ================= INFO CARDS ================= */
    .info-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 25px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        margin-bottom: 25px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .card-title span {
        width: 6px;
        height: 24px;
        background: var(--primary);
        border-radius: 3px;
        display: inline-block;
    }

    /* ================= INFO ROWS ================= */
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px dashed var(--border);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 40%;
        font-weight: 600;
        color: var(--text-muted);
        font-size: 14px;
    }

    .info-value {
        width: 60%;
        font-weight: 500;
        color: var(--text-main);
        font-size: 14px;
    }

    .info-value.highlight {
        color: var(--primary);
        font-weight: 700;
    }

    /* ================= STATUS TIMELINE ================= */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 25px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 0;
        width: 2px;
        height: 100%;
        background: var(--border);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--border);
        z-index: 1;
    }

    .timeline-item.completed .timeline-icon {
        background: var(--success);
        border-color: var(--success-dark);
    }

    .timeline-item.current .timeline-icon {
        background: var(--primary);
        border-color: var(--primary-dark);
        animation: pulse 2s infinite;
    }

    .timeline-item.rejected .timeline-icon {
        background: var(--danger);
        border-color: var(--danger-dark);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    .timeline-content {
        background: var(--bg-light);
        padding: 15px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .timeline-title {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline-date {
        font-size: 12px;
        color: var(--text-muted);
    }

    .timeline-remarks {
        margin-top: 8px;
        padding: 8px;
        background: white;
        border-radius: var(--radius-sm);
        font-size: 13px;
        border-left: 3px solid var(--primary);
    }

    /* ================= DOCUMENT CARD ================= */
    .document-card {
        background: var(--bg-light);
        border-radius: var(--radius-md);
        padding: 15px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        border: 1px solid var(--border);
    }

    .document-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .document-icon {
        width: 40px;
        height: 40px;
        background: var(--primary);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .document-name {
        font-weight: 600;
        color: var(--text-main);
        word-break: break-word;
    }

    .document-size {
        font-size: 12px;
        color: var(--text-muted);
    }

    .btn-download {
        padding: 8px 16px;
        background: var(--success);
        color: white;
        text-decoration: none;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    /* ================= CANCEL FORM ================= */
    .cancel-form {
        margin-top: 25px;
        padding: 20px;
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: var(--radius-md);
    }

    .cancel-form textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ffc107;
        border-radius: var(--radius-md);
        margin: 10px 0;
        resize: vertical;
    }

    .btn-cancel-submit {
        background: var(--danger);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel-submit:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    /* ================= ALERTS ================= */
    .alert {
        padding: 15px 20px;
        border-radius: var(--radius-md);
        margin-bottom: 25px;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .header-card {
            flex-direction: column;
            text-align: center;
        }

        .header-left {
            justify-content: center;
        }

        .info-row {
            flex-direction: column;
            gap: 5px;
        }

        .info-label, .info-value {
            width: 100%;
        }

        .leave-number-card {
            flex-direction: column;
            text-align: center;
        }

        .action-buttons {
            justify-content: center;
        }
    }
</style>

<div class="leave-detail-page">
    <div class="container">
        <!-- Header Card -->
        <div class="header-card">
            <div class="header-left">
                <div class="header-icon">📋</div>
                <div class="header-title">
                    <h1>Leave Details</h1>
                    <p>{{ $leave->employee->name }} • {{ $leave->employee->employee_code }}</p>
                </div>
            </div>
            <div class="status-badge-large {{ $leave->status }}">
                @if($leave->status == 'pending')
                    ⏳ Pending
                @elseif($leave->status == 'approved')
                    ✅ Approved
                @elseif($leave->status == 'rejected')
                    ❌ Rejected
                @elseif($leave->status == 'cancelled')
                    ↩️ Cancelled
                @endif
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <span>⚠️</span> {{ session('warning') }}
            </div>
        @endif

        <!-- Leave Number Card -->
        <div class="leave-number-card">
            <div>
                <div class="leave-number-label">Leave Number</div>
                <div class="leave-number-value">{{ $leave->leave_number ?? 'N/A' }}</div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('leaves.print', $leave->id) }}" class="btn-action print" target="_blank">
                    <span>🖨️</span> Print
                </a>
                @if($leave->document_path)
                    <a href="{{ route('leaves.download', $leave->id) }}" class="btn-action download">
                        <span>📎</span> Download
                    </a>
                @endif
                <a href="{{ route('leaves.my') }}" class="btn-action back">
                    <span>↩️</span> Back
                </a>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="detail-grid">
            <!-- Left Column -->
            <div>
                <!-- Leave Information -->
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Leave Information
                    </div>

                    <div class="info-row">
                        <div class="info-label">Leave Type</div>
                        <div class="info-value highlight">{{ $leave->leave_type_label ?? $leave->leave_type }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Duration Type</div>
                        <div class="info-value">{{ $leave->duration_label ?? $leave->duration_type }}</div>
                    </div>

                    @if($leave->session)
                    <div class="info-row">
                        <div class="info-label">Session</div>
                        <div class="info-value">{{ $leave->session == 'first_half' ? 'First Half' : 'Second Half' }}</div>
                    </div>
                    @endif

                    @if($leave->start_time && $leave->end_time)
                    <div class="info-row">
                        <div class="info-label">Time</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($leave->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($leave->end_time)->format('h:i A') }}</div>
                    </div>
                    @endif

                    <div class="info-row">
                        <div class="info-label">From Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($leave->from_date)->format('l, d F Y') }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">To Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($leave->to_date)->format('l, d F Y') }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Total Days</div>
                        <div class="info-value highlight">{{ $leave->total_days ?? 1 }} day(s)</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Applied On</div>
                        <div class="info-value">{{ $leave->applied_on ? $leave->applied_on->format('l, d F Y h:i A') : 'N/A' }}</div>
                    </div>

                    @if($leave->ip_address)
                    <div class="info-row">
                        <div class="info-label">Applied From</div>
                        <div class="info-value">{{ $leave->ip_address }} <small>({{ $leave->user_agent ?? 'N/A' }})</small></div>
                    </div>
                    @endif
                </div>

                <!-- Reason Information -->
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Reason & Details
                    </div>

                    <div class="info-row">
                        <div class="info-label">Reason</div>
                        <div class="info-value">{{ $leave->reason ?? 'N/A' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value">{{ $leave->contact_number ?? 'N/A' }}</div>
                    </div>

                    @if($leave->emergency_contact)
                    <div class="info-row">
                        <div class="info-label">Emergency Contact</div>
                        <div class="info-value">{{ $leave->emergency_contact }}</div>
                    </div>
                    @endif
                </div>

                <!-- Handover Information -->
                @if($leave->handover_notes || $leave->handover_person || $leave->alternate_arrangements)
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Work Handover
                    </div>

                    @if($leave->handover_notes)
                    <div class="info-row">
                        <div class="info-label">Handover Notes</div>
                        <div class="info-value">{{ $leave->handover_notes }}</div>
                    </div>
                    @endif

                    @if($leave->handover_person)
                    <div class="info-row">
                        <div class="info-label">Handover Person</div>
                        <div class="info-value">{{ $leave->handover_person }}</div>
                    </div>
                    @endif

                    @if($leave->alternate_arrangements)
                    <div class="info-row">
                        <div class="info-label">Alternate Arrangements</div>
                        <div class="info-value">{{ $leave->alternate_arrangements }}</div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Document Information -->
                @if($leave->document_path)
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Supporting Document
                    </div>

                    <div class="document-card">
                        <div class="document-info">
                            <div class="document-icon">📄</div>
                            <div>
                                <div class="document-name">{{ $leave->document_name ?? 'Document' }}</div>
                                @php
                                    $filePath = storage_path('app/public/' . $leave->document_path);
                                    $fileSize = file_exists($filePath) ? round(filesize($filePath) / 1024, 1) . ' KB' : 'N/A';
                                @endphp
                                <div class="document-size">{{ $fileSize }}</div>
                            </div>
                        </div>
                        <a href="{{ route('leaves.download', $leave->id) }}" class="btn-download">
                            <span>📥</span> Download
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <!-- Status Timeline -->
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Status Timeline
                    </div>

                    <div class="timeline">
                        <!-- Applied -->
                        <div class="timeline-item completed">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <span>📝</span> Leave Applied
                                </div>
                                <div class="timeline-date">
                                    {{ $leave->applied_on ? $leave->applied_on->format('d M Y, h:i A') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Approved/Rejected/Cancelled -->
                        @if($leave->status == 'approved')
                        <div class="timeline-item completed">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <span>✅</span> Approved
                                </div>
                                <div class="timeline-date">
                                    By: {{ $leave->approver->name ?? 'N/A' }} on {{ $leave->approved_at ? $leave->approved_at->format('d M Y, h:i A') : 'N/A' }}
                                </div>
                                @if($leave->approval_remarks)
                                <div class="timeline-remarks">
                                    <strong>Remarks:</strong> {{ $leave->approval_remarks }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($leave->status == 'rejected')
                        <div class="timeline-item rejected">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <span>❌</span> Rejected
                                </div>
                                <div class="timeline-date">
                                    By: {{ $leave->rejector->name ?? 'N/A' }} on {{ $leave->rejected_at ? $leave->rejected_at->format('d M Y, h:i A') : 'N/A' }}
                                </div>
                                @if($leave->rejection_reason)
                                <div class="timeline-remarks">
                                    <strong>Reason:</strong> {{ $leave->rejection_reason }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($leave->status == 'cancelled')
                        <div class="timeline-item rejected">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <span>↩️</span> Cancelled
                                </div>
                                <div class="timeline-date">
                                    By: {{ $leave->canceller->name ?? 'N/A' }} on {{ $leave->cancelled_at ? $leave->cancelled_at->format('d M Y, h:i A') : 'N/A' }}
                                </div>
                                @if($leave->cancellation_reason)
                                <div class="timeline-remarks">
                                    <strong>Reason:</strong> {{ $leave->cancellation_reason }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($leave->status == 'pending')
                        <div class="timeline-item current">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <span>⏳</span> Pending Approval
                                </div>
                                <div class="timeline-date">
                                    Waiting for admin review
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Balance Information -->
                @if(isset($leaveBalance))
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Leave Balance
                    </div>

                    @foreach($leaveBalance as $type => $balance)
                        @if($balance['entitled'] > 0)
                        <div class="info-row">
                            <div class="info-label">{{ ucfirst($type) }} Leave</div>
                            <div class="info-value">
                                {{ $balance['used'] }} / {{ $balance['entitled'] }} used
                                <br>
                                <small>Available: {{ $balance['available'] }} days</small>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif

                <!-- Cancel Form (Only for Pending) -->
                @if($leave->status == 'pending' && Auth::user()->role != 'admin')
                <div class="info-card">
                    <div class="card-title">
                        <span></span> Cancel Request
                    </div>

                    <form method="POST" action="{{ route('leaves.cancel', $leave->id) }}" class="cancel-form"
                          onsubmit="return confirm('Are you sure you want to cancel this leave request? This action cannot be undone.');">
                        @csrf
                        <label style="font-weight: 600; color: #856404;">Cancellation Reason:</label>
                        <textarea name="cancellation_reason" rows="3" placeholder="Please provide reason for cancellation..." required minlength="10"></textarea>
                        <button type="submit" class="btn-cancel-submit">
                            <span>✖️</span> Cancel Leave Request
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Character counter for cancellation reason
    const cancelTextarea = document.querySelector('textarea[name="cancellation_reason"]');
    if (cancelTextarea) {
        const counterDiv = document.createElement('div');
        counterDiv.style.fontSize = '12px';
        counterDiv.style.marginTop = '5px';
        counterDiv.style.color = 'var(--text-muted)';
        cancelTextarea.parentNode.insertBefore(counterDiv, cancelTextarea.nextSibling);

        cancelTextarea.addEventListener('input', function() {
            const length = this.value.length;
            counterDiv.innerHTML = `${length}/500 characters`;

            if (length < 10) {
                counterDiv.style.color = 'var(--danger)';
            } else {
                counterDiv.style.color = 'var(--success)';
            }
        });
    }

    // Auto-refresh for pending status (optional)
    @if($leave->status == 'pending')
    setTimeout(function() {
        location.reload();
    }, 30000); // Refresh every 30 seconds
    @endif
</script>
@endsection
