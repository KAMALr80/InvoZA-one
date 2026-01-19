@extends('layouts.app')

@section('content')
<div style="max-width:700px;margin:40px auto;">
    <h2>🤖 AI Sales Prediction</h2>
    <hr>

    <p>
        <strong>Expected Sales (Next 30 Days):</strong>
        ₹ {{ number_format($prediction['next_30_days_total'], 2) }}
    </p>

    <p>
        <strong>Average Daily Sales:</strong>
        ₹ {{ number_format($prediction['daily_prediction_avg'], 2) }}
    </p>

    <small style="color:gray">
        Prediction is based on historical sales data.
    </small>
</div>
@endsection
