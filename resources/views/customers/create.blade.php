@extends('layouts.app')

@section('content')
    <div class="report-box"
        style="max-width:650px; margin:50px auto; padding:35px; border-radius:15px;
               background:linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
               box-shadow:0 8px 20px rgba(0,0,0,0.15); animation:fadeIn 1s ease-in-out;">

        <h2 style="color:#333; font-family:'Segoe UI', sans-serif; margin-bottom:25px; text-align:center; font-size:24px;">
            ➕ Add Customer
        </h2>

        <form method="POST" action="{{ route('customers.store') }}" style="display:flex; flex-direction:column; gap:15px;">
            @csrf

            <input type="text" name="name" placeholder="Customer Name" required
                style="padding:12px; border:1px solid #ccc; border-radius:8px; font-size:15px; transition:0.3s; outline:none;"
                onfocus="this.style.borderColor='#ff7043'">

            <input type="text" name="mobile" placeholder="Mobile" required
                style="padding:12px; border:1px solid #ccc; border-radius:8px; font-size:15px; transition:0.3s; outline:none;"
                onfocus="this.style.borderColor='#ff7043'">

            <input type="email" name="email" placeholder="Email"
                style="padding:12px; border:1px solid #ccc; border-radius:8px; font-size:15px; transition:0.3s; outline:none;"
                onfocus="this.style.borderColor='#ff7043'">

            <input type="text" name="gst_no" placeholder="GST Number"
                style="padding:12px; border:1px solid #ccc; border-radius:8px; font-size:15px; transition:0.3s; outline:none;"
                onfocus="this.style.borderColor='#ff7043'">

            <textarea name="address" placeholder="Address"
                style="padding:12px; border:1px solid #ccc; border-radius:8px; font-size:15px; min-height:90px; transition:0.3s; outline:none;"
                onfocus="this.style.borderColor='#ff7043'"></textarea>

            <button type="submit" id="saveBtn"
                style="background:#ff7043; color:#fff; border:none; padding:12px;
           border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;">
                💾 Save
            </button>

        </form>

        <script>
            document.querySelector('form').addEventListener('submit', function() {
                const btn = document.getElementById('saveBtn');
                btn.disabled = true;
                btn.innerText = 'Saving...';
                btn.style.opacity = '0.7';
            });
        </script>


    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
