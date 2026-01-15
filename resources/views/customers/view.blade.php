<td style="padding:10px;">

    {{-- VIEW (DETAIL PAGE) --}}
    <a href="{{ route('sales.view', $sale->id) }}" style="margin-right:10px; text-decoration:none;">
        👁️ View
    </a>

    {{-- PRINT --}}
    <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" style="margin-right:10px; text-decoration:none;">
        🖨 Invoice
    </a>

    {{-- EDIT --}}
    <a href="{{ route('sales.edit', $sale->id) }}" style="text-decoration:none;">
        ✏️ Edit
    </a>

</td>
