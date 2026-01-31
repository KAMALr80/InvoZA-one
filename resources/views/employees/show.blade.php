@extends('layouts.app')

@section('content')
    <div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 24px;">
        <!-- Header (Same as before) -->
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 0 12px;">
                <div>
                    <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 8px; letter-spacing: -0.5px;">
                        Employee Details</h1>
                    <div style="display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px;">
                        <a href="{{ route('dashboard') }}"
                            style="color: #64748b; text-decoration: none; transition: color 0.2s;"
                            onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">Dashboard</a>
                        <span style="color: #cbd5e1;">/</span>
                        <a href="{{ route('employees.index') }}"
                            style="color: #64748b; text-decoration: none; transition: color 0.2s;"
                            onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">Employees</a>
                        <span style="color: #cbd5e1;">/</span>
                        <span style="color: #475569; font-weight: 500;">Details</span>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('employees.index') }}"
                        style="display: inline-flex; align-items: center; gap: 8px; background: white; color: #4b5563; border: 1px solid #e5e7eb; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.3s;"
                        onmouseover="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#d1d5db'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.transform='translateY(0)'">
                        <span style="font-size: 16px;">←</span> Back
                    </a>
                    @if (in_array(auth()->user()->role, ['admin', 'hr']))
                        <a href="{{ route('employees.edit', $employee->id) }}"
                            style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 10px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s;"
                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(59, 130, 246, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.3)'">
                            <span style="font-size: 16px;">✏️</span> Edit
                        </a>
                    @endif
                </div>
            </div>

            <!-- Main Card (Same as before) -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb; overflow: hidden;">
                <!-- Profile Header (Same as before) -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; position: relative;">
                    <div style="display: flex; align-items: center; gap: 24px;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 36px; color: #764ba2; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
                                <h2 style="font-size: 32px; font-weight: 800; color: white; margin: 0;">
                                    {{ $employee->name }}</h2>
                                <div style="display: flex; gap: 8px;">
                                    <span style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); color: white; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.3);">
                                        {{ $employee->employee_code }}
                                    </span>
                                    <span style="background: {{ $employee->status == 1 ? 'rgba(34, 197, 94, 0.2)' : 'rgba(107, 114, 128, 0.2)' }}; backdrop-filter: blur(10px); color: {{ $employee->status == 1 ? '#22c55e' : '#9ca3af' }}; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; border: 1px solid {{ $employee->status == 1 ? 'rgba(34, 197, 94, 0.3)' : 'rgba(156, 163, 175, 0.3)' }};">
                                        ● {{ $employee->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px; color: rgba(255, 255, 255, 0.9);">
                                    <span style="font-size: 18px;">📧</span>
                                    <span style="color: white; text-decoration: none; font-size: 16px; font-weight: 500;">
                                        {{ $employee->email }}
                                    </span>
                                </div>
                                @if ($employee->phone)
                                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255, 255, 255, 0.9);">
                                        <span style="font-size: 18px;">📱</span>
                                        <span style="color: white; text-decoration: none; font-size: 16px; font-weight: 500;">
                                            {{ $employee->phone }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Grid (Same as before) -->
                <!-- ... (previous information grid content remains same) ... -->

                <!-- Quick Actions Section - UPDATED -->
                <div style="padding: 40px;">
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 16px; padding: 32px; border: 2px solid #e2e8f0;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 20px;">⚡</span> Quick Actions
                        </h3>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <!-- Send Email Form -->
                            <div style="flex: 2; min-width: 300px;">
                                <form id="sendEmailForm" action="{{ route('employee.send.email', $employee->id) }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 16px;">
                                        <div style="font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 15px;">
                                            Email Template:</div>
                                        <select id="emailTemplate" name="template" onchange="updateEmailForm()"
                                            style="padding: 10px 16px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; color: #374151; background: white; cursor: pointer; appearance: none; width: 100%; transition: all 0.3s;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                            <option value="general">General Inquiry</option>
                                            <option value="meeting">Meeting Request</option>
                                            <option value="welcome">Welcome Email</option>
                                            <option value="followup">Follow-up</option>
                                            <option value="status">Status Update</option>
                                            <option value="custom">Custom Message</option>
                                        </select>
                                    </div>

                                    <!-- Hidden fields for custom message -->
                                    <input type="hidden" id="emailSubject" name="subject" value="">
                                    <input type="hidden" id="emailBody" name="body" value="">

                                    <button type="button" onclick="prepareAndSendEmail()"
                                        style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 16px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.3s; width: 100%; cursor: pointer; text-align: left;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.4)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <span style="font-size: 20px;">📧</span>
                                        <div>
                                            <div style="font-weight: 700;" id="emailButtonText">Send General Email</div>
                                            <div style="font-size: 13px; color: white; margin-top: 2px; opacity: 0.9;" id="emailButtonDesc">
                                                Send via Laravel Mail
                                            </div>
                                        </div>
                                    </button>

                                    <!-- Loading indicator -->
                                    <div id="loadingIndicator" style="display: none; text-align: center; margin-top: 12px;">
                                        <div style="color: #3b82f6; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <span style="font-size: 20px;">⏳</span> Sending email...
                                        </div>
                                    </div>

                                    <!-- Success/Error messages -->
                                    <div id="messageContainer" style="margin-top: 12px;"></div>
                                </form>
                            </div>

                            <!-- Other action buttons (remain same) -->
                            @if ($employee->phone)
                                <a href="tel:{{ $employee->phone }}"
                                    style="display: inline-flex; align-items: center; gap: 12px; background: white; color: #10b981; border: 2px solid #d1fae5; padding: 16px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.3s; flex: 1; min-width: 200px;"
                                    onmouseover="this.style.backgroundColor='#d1fae5'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.15)'"
                                    onmouseout="this.style.backgroundColor='white'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <span style="font-size: 20px;">📱</span>
                                    <div>
                                        <div style="font-weight: 700;">Make a Call</div>
                                        <div style="font-size: 13px; color: #64748b; margin-top: 2px;">Call {{ $employee->phone }}</div>
                                    </div>
                                </a>
                            @endif

                            @if (auth()->user()->role === 'admin')
                                <div style="flex: 1; min-width: 200px;">
                                    <button onclick="confirmDelete()"
                                        style="display: inline-flex; align-items: center; gap: 12px; background: white; color: #ef4444; border: 2px solid #fee2e2; padding: 16px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.3s; width: 100%; cursor: pointer; text-align: left;"
                                        onmouseover="this.style.backgroundColor='#fee2e2'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(239, 68, 68, 0.15)'"
                                        onmouseout="this.style.backgroundColor='white'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <span style="font-size: 20px;">🗑️</span>
                                        <div>
                                            <div style="font-weight: 700;">Delete Employee</div>
                                            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">Remove permanently</div>
                                        </div>
                                    </button>
                                    <form id="deleteForm" action="{{ route('employees.destroy', $employee->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Custom Message Modal -->
                    <div id="customMessageModal"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
                        <div style="background: white; border-radius: 20px; padding: 32px; width: 90%; max-width: 600px; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                                <h3 style="font-size: 22px; font-weight: 700; color: #1e293b;">Write Custom Message</h3>
                                <button onclick="closeCustomMessageModal()"
                                    style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; padding: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;"
                                    onmouseover="this.style.background='#f1f5f9'; this.style.color='#475569'"
                                    onmouseout="this.style.background='transparent'; this.style.color='#64748b'">
                                    ×
                                </button>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <div style="font-weight: 600; color: #475569; margin-bottom: 8px; font-size: 15px;">
                                    Subject:</div>
                                <input type="text" id="customSubject"
                                    style="padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; width: 100%; transition: all 0.3s; margin-bottom: 16px;"
                                    value="Regarding Employee: {{ $employee->name }} ({{ $employee->employee_code }})"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">

                                <div style="font-weight: 600; color: #475569; margin-bottom: 8px; font-size: 15px;">
                                    Message:</div>
                                <textarea id="customBody" rows="8"
                                    style="width: 100%; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 15px; resize: vertical; font-family: inherit; line-height: 1.5; transition: all 0.3s;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">Dear {{ $employee->name }},

I hope this email finds you well.

Regarding your employment details at our company, I wanted to discuss a few important matters with you.

Please let me know your availability for a brief meeting next week.

Best regards,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}</textarea>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                <button onclick="closeCustomMessageModal()"
                                    style="background: #f1f5f9; color: #475569; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#e2e8f0'"
                                    onmouseout="this.style.backgroundColor='#f1f5f9'">
                                    Cancel
                                </button>
                                <button onclick="sendCustomEmail()"
                                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 12px 32px; border-radius: 10px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(59, 130, 246, 0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    Send Email
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Email Templates
                        const emailTemplates = {
                            general: {
                                name: "General Inquiry",
                                description: "Send via Laravel Mail",
                                subject: "Regarding Employee: {{ $employee->name }} ({{ $employee->employee_code }})",
                                body: `Dear {{ $employee->name }},

I hope this email finds you well.

Regarding your employment details at our company, I wanted to discuss a few important matters with you.

Please let me know your availability for a brief meeting next week.

Best regards,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}`
                            },
                            meeting: {
                                name: "Meeting Request",
                                description: "Send via Laravel Mail",
                                subject: "Meeting Request - {{ $employee->name }} ({{ $employee->employee_code }})",
                                body: `Dear {{ $employee->name }},

I hope you are doing well.

I would like to schedule a meeting with you to discuss your recent work and future projects. Please let me know your availability for the coming week.

Looking forward to our discussion.

Best regards,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}`
                            },
                            welcome: {
                                name: "Welcome Email",
                                description: "Send via Laravel Mail",
                                subject: "Welcome to the Team - {{ $employee->name }}",
                                body: `Dear {{ $employee->name }},

Welcome to our team! We are excited to have you on board as part of the {{ $employee->department ?? 'our' }} team.

Your employee code is: {{ $employee->employee_code }}

If you have any questions or need assistance, please don't hesitate to reach out.

Warm regards,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}`
                            },
                            followup: {
                                name: "Follow-up",
                                description: "Send via Laravel Mail",
                                subject: "Follow-up: {{ $employee->name }}",
                                body: `Hi {{ $employee->name }},

Just following up on our previous conversation. Please provide an update when you get a chance.

Thank you.

Best,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}`
                            },
                            status: {
                                name: "Status Update",
                                description: "Send via Laravel Mail",
                                subject: "Status Update Request - {{ $employee->name }}",
                                body: `Hello {{ $employee->name }},

Could you please provide a status update on your current projects?

Please share your progress and any blockers you might be facing.

Thanks,
{{ auth()->user()->name ?? 'Your Name' }}
{{ auth()->user()->role ? '(' . ucfirst(auth()->user()->role) . ')' : '' }}`
                            },
                            custom: {
                                name: "Custom Message",
                                description: "Write your own message",
                                subject: "",
                                body: ""
                            }
                        };

                        let selectedTemplate = 'general';

                        function updateEmailForm() {
                            const templateSelect = document.getElementById('emailTemplate');
                            selectedTemplate = templateSelect.value;
                            const template = emailTemplates[selectedTemplate];

                            document.getElementById('emailButtonText').textContent = `Send ${template.name}`;
                            document.getElementById('emailButtonDesc').textContent = template.description;

                            // Update hidden fields for non-custom templates
                            if (selectedTemplate !== 'custom') {
                                document.getElementById('emailSubject').value = template.subject;
                                document.getElementById('emailBody').value = template.body;
                            }
                        }

                        function prepareAndSendEmail() {
                            if (selectedTemplate === 'custom') {
                                openCustomMessageModal();
                            } else {
                                sendEmail();
                            }
                        }

                        function sendEmail() {
                            const form = document.getElementById('sendEmailForm');
                            const submitButton = form.querySelector('button[type="button"]');
                            const loadingIndicator = document.getElementById('loadingIndicator');
                            const messageContainer = document.getElementById('messageContainer');

                            // Show loading
                            submitButton.disabled = true;
                            submitButton.style.opacity = '0.7';
                            loadingIndicator.style.display = 'block';
                            messageContainer.innerHTML = '';

                            // Submit form via AJAX
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    subject: document.getElementById('emailSubject').value,
                                    body: document.getElementById('emailBody').value,
                                    template: selectedTemplate
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Hide loading
                                submitButton.disabled = false;
                                submitButton.style.opacity = '1';
                                loadingIndicator.style.display = 'none';

                                // Show message
                                if (data.success) {
                                    messageContainer.innerHTML = `
                                        <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px;">
                                            <span style="font-size: 18px;">✅</span>
                                            <div>
                                                <div style="font-weight: 600;">${data.message}</div>
                                                <div style="font-size: 12px; opacity: 0.8;">Email sent to {{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    messageContainer.innerHTML = `
                                        <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
                                            <span style="font-size: 18px;">❌</span>
                                            <div>
                                                <div style="font-weight: 600;">${data.message || 'Failed to send email'}</div>
                                                <div style="font-size: 12px; opacity: 0.8;">Please try again</div>
                                            </div>
                                        </div>
                                    `;
                                }

                                // Clear message after 5 seconds
                                setTimeout(() => {
                                    messageContainer.innerHTML = '';
                                }, 5000);
                            })
                            .catch(error => {
                                submitButton.disabled = false;
                                submitButton.style.opacity = '1';
                                loadingIndicator.style.display = 'none';

                                messageContainer.innerHTML = `
                                    <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
                                        <span style="font-size: 18px;">❌</span>
                                        <div>
                                            <div style="font-weight: 600;">Network error occurred</div>
                                            <div style="font-size: 12px; opacity: 0.8;">Please check your connection</div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        function openCustomMessageModal() {
                            const modal = document.getElementById('customMessageModal');
                            modal.style.display = 'flex';

                            // Set default values
                            document.getElementById('customSubject').value = emailTemplates.general.subject;
                            document.getElementById('customBody').value = emailTemplates.general.body;
                        }

                        function closeCustomMessageModal() {
                            document.getElementById('customMessageModal').style.display = 'none';
                        }

                        function sendCustomEmail() {
                            const subject = document.getElementById('customSubject').value;
                            const body = document.getElementById('customBody').value;

                            // Update hidden fields
                            document.getElementById('emailSubject').value = subject;
                            document.getElementById('emailBody').value = body;

                            closeCustomMessageModal();
                            sendEmail();
                        }

                        function confirmDelete() {
                            if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
                                document.getElementById('deleteForm').submit();
                            }
                        }

                        // Initialize
                        document.addEventListener('DOMContentLoaded', function() {
                            updateEmailForm();

                            // Close modal on outside click
                            document.getElementById('customMessageModal').addEventListener('click', function(e) {
                                if (e.target === this) {
                                    closeCustomMessageModal();
                                }
                            });

                            // Close modal on Escape key
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') {
                                    closeCustomMessageModal();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
