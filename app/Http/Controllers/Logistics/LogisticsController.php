<?php
// app/Http/Controllers/Logistics/LogisticsController.php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\ShipmentTracking;
use App\Models\DeliveryAgent;
use App\Models\CourierPartner;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LogisticsController extends Controller
{
    /**
     * Display shipments listing
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['customer', 'deliveryAgent', 'sale']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('courier_partner')) {
            $query->where('courier_partner', $request->courier_partner);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $shipments = $query->paginate(15)->withQueryString();

        // Stats for dashboard
        $stats = [
            'total' => Shipment::count(),
            'pending' => Shipment::where('status', 'pending')->count(),
            'in_transit' => Shipment::whereIn('status', ['picked', 'in_transit', 'out_for_delivery'])->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
            'delivered_today' => Shipment::whereDate('actual_delivery_date', today())->count(),
        ];

        return view('logistics.shipments.index', compact('shipments', 'stats'));
    }

    /**
     * Show create shipment form
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        // Get sales that don't have shipments or have undelivered shipments
        $sales = Sale::with('customer')
            ->where(function($query) {
                $query->whereDoesntHave('shipments')
                    ->orWhereHas('shipments', function($q) {
                        $q->where('status', '!=', 'delivered');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $courierPartners = CourierPartner::where('is_active', true)->get();
        $deliveryAgents = DeliveryAgent::where('is_active', true)
            ->where('status', 'available')
            ->get();

        return view('logistics.shipments.create', compact('customers', 'sales', 'courierPartners', 'deliveryAgents'));
    }

    /**
     * Store new shipment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'nullable|exists:sales,id',
            'customer_id' => 'required|exists:customers,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_alternate_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'package_type' => 'nullable|string|in:box,envelope,pallet',
            'declared_value' => 'required|numeric|min:0',
            'shipping_method' => 'required|in:standard,express,overnight',
            'courier_partner' => 'nullable|string',
            'payment_mode' => 'required|in:prepaid,cod',
            'estimated_delivery_date' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Calculate shipping charges
            $shippingCharge = $this->calculateShippingCharge($request);
            $codCharge = $request->payment_mode === 'cod' ? $this->calculateCODCharge($request) : 0;
            $insuranceCharge = $this->calculateInsuranceCharge($request);
            $totalCharge = $shippingCharge + $codCharge + $insuranceCharge;

            $shipment = new Shipment();
            $shipment->shipment_number = (new Shipment())->generateShipmentNumber();
            $shipment->fill($request->except(['_token', '_method']));
            $shipment->shipping_charge = $shippingCharge;
            $shipment->cod_charge = $codCharge;
            $shipment->insurance_charge = $insuranceCharge;
            $shipment->total_charge = $totalCharge;
            $shipment->created_by = auth()->id() ?? 1;
            $shipment->save();

            // Add initial tracking
            $shipment->updateTracking('pending', $request->city, 'Shipment created');

            // If this shipment is linked to a sale, update sale's shipping status
            if ($request->sale_id) {
                $sale = Sale::find($request->sale_id);
                if ($sale && !$sale->requires_shipping) {
                    $sale->requires_shipping = true;
                    $sale->save();
                }
            }

            DB::commit();

            Log::info('Shipment created successfully', [
                'shipment_id' => $shipment->id,
                'shipment_number' => $shipment->shipment_number,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('logistics.shipments.show', $shipment->id)
                ->with('success', 'Shipment created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Shipment creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating shipment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show shipment details
     */
    public function show($id)
    {
        $shipment = Shipment::with(['customer', 'deliveryAgent', 'sale', 'trackings', 'creator'])
            ->findOrFail($id);

        // Calculate delivery progress
        $deliveryProgress = $this->calculateDeliveryProgress($shipment);

        // Get related shipments for same customer/sale
        $relatedShipments = Shipment::where('customer_id', $shipment->customer_id)
            ->where('id', '!=', $shipment->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('logistics.shipments.show', compact('shipment', 'deliveryProgress', 'relatedShipments'));
    }

    /**
     * Update shipment status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,picked,in_transit,out_for_delivery,delivered,failed,returned',
            'location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $shipment = Shipment::findOrFail($id);
            $oldStatus = $shipment->status;

            $shipment->updateTracking($request->status, $request->location, $request->remarks);

            // If status changed to delivered, update delivery agent stats
            if ($request->status === 'delivered' && $oldStatus !== 'delivered' && $shipment->assigned_to) {
                $agent = DeliveryAgent::where('user_id', $shipment->assigned_to)->first();
                if ($agent) {
                    $agent->total_deliveries++;
                    $agent->successful_deliveries++;
                    $agent->status = 'available';
                    $agent->save();
                }
            }

            // If status changed to failed/returned, free up the agent
            if (in_array($request->status, ['failed', 'returned']) && $shipment->assigned_to) {
                $agent = DeliveryAgent::where('user_id', $shipment->assigned_to)->first();
                if ($agent) {
                    $agent->status = 'available';
                    $agent->save();
                }
            }

            DB::commit();

            Log::info('Shipment status updated', [
                'shipment_id' => $shipment->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipment status updated successfully',
                'shipment' => $shipment->load('trackings')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Shipment status update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign delivery agent
     */
    public function assignAgent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:delivery_agents,user_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $shipment = Shipment::findOrFail($id);

            // Check if agent is available
            $agent = DeliveryAgent::where('user_id', $request->agent_id)->first();
            if ($agent->status !== 'available') {
                return redirect()->back()->with('error', 'Agent is not available');
            }

            $shipment->assigned_to = $request->agent_id;
            $shipment->save();

            $agent->status = 'busy';
            $agent->save();

            $shipment->updateTracking('assigned', null, "Assigned to delivery agent: " . $agent->name);

            DB::commit();

            Log::info('Delivery agent assigned', [
                'shipment_id' => $shipment->id,
                'agent_id' => $agent->id,
                'agent_name' => $agent->name
            ]);

            return redirect()->back()->with('success', 'Delivery agent assigned successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agent assignment failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error assigning agent: ' . $e->getMessage());
        }
    }

    /**
     * Upload proof of delivery
     */
  /**
 * Upload proof of delivery
 */
public function uploadPOD(Request $request, $id)
{
    // Log the request for debugging
    Log::info('POD upload started', [
        'shipment_id' => $id,
        'has_signature' => $request->hasFile('signature'),
        'has_photo' => $request->hasFile('photo'),
        'content_type' => $request->header('Content-Type')
    ]);

    $validator = Validator::make($request->all(), [
        'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        'delivery_notes' => 'nullable|string|max:1000',
    ]);

    if ($validator->fails()) {
        Log::warning('POD validation failed', ['errors' => $validator->errors()]);

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $shipment = Shipment::findOrFail($id);

        // Check if shipment is delivered
        if ($shipment->status !== 'delivered') {
            $message = 'Shipment must be delivered before uploading POD';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return redirect()->back()->with('error', $message);
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $file = $request->file('signature');

            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('Invalid signature file');
            }

            // Generate unique filename
            $filename = 'signature_' . $shipment->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs('pod/signatures', $filename, 'public');

            if (!$path) {
                throw new \Exception('Failed to store signature file');
            }

            // Delete old signature if exists
            if ($shipment->pod_signature && Storage::disk('public')->exists($shipment->pod_signature)) {
                Storage::disk('public')->delete($shipment->pod_signature);
            }

            $shipment->pod_signature = $path;

            Log::info('Signature uploaded', ['path' => $path]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            if (!$file->isValid()) {
                throw new \Exception('Invalid photo file');
            }

            $filename = 'photo_' . $shipment->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pod/photos', $filename, 'public');

            if (!$path) {
                throw new \Exception('Failed to store photo file');
            }

            // Delete old photo if exists
            if ($shipment->pod_photo && Storage::disk('public')->exists($shipment->pod_photo)) {
                Storage::disk('public')->delete($shipment->pod_photo);
            }

            $shipment->pod_photo = $path;

            Log::info('Photo uploaded', ['path' => $path]);
        }

        $shipment->delivery_notes = $request->delivery_notes;
        $shipment->save();

        // Add tracking entry
        $shipment->updateTracking('pod_uploaded', null, 'Proof of delivery uploaded');

        Log::info('POD uploaded successfully', [
            'shipment_id' => $shipment->id,
            'has_signature' => $request->hasFile('signature'),
            'has_photo' => $request->hasFile('photo'),
            'notes' => $request->delivery_notes
        ]);

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Proof of delivery uploaded successfully!',
                'data' => [
                    'signature_url' => $shipment->pod_signature ? asset('storage/' . $shipment->pod_signature) : null,
                    'photo_url' => $shipment->pod_photo ? asset('storage/' . $shipment->pod_photo) : null,
                    'notes' => $shipment->delivery_notes
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Proof of delivery uploaded successfully');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Shipment not found: ' . $e->getMessage());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment not found'
            ], 404);
        }

        return redirect()->back()->with('error', 'Shipment not found');

    } catch (\Exception $e) {
        Log::error('POD upload failed: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'shipment_id' => $id
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading POD: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Error uploading POD: ' . $e->getMessage());
    }
}

    /**
     * Track shipment (Public API)
     */
   /**
 * Track shipment (Public API) - WITH LIVE LOCATION
 */
public function track($trackingNumber)
{
    try {
        $shipment = Shipment::with(['trackings' => function($q) {
            $q->orderBy('tracked_at', 'desc');
        }])->where('tracking_number', $trackingNumber)
          ->orWhere('shipment_number', $trackingNumber)
          ->firstOrFail();

        // Get latest tracking for live location
        $latestTracking = $shipment->trackings()->latest()->first();

        return response()->json([
            'success' => true,
            'shipment_number' => $shipment->shipment_number,
            'tracking_number' => $shipment->tracking_number,
            'status' => $shipment->status,
            'status_badge' => $shipment->status_badge,
            'estimated_delivery' => $shipment->estimated_delivery_date?->format('Y-m-d'),
            'current_location' => $shipment->full_address,

            // ✅ LIVE LOCATION COORDINATES
            'latitude' => $shipment->current_latitude ?? $latestTracking?->latitude ?? 22.524768, // Default Gujarat
            'longitude' => $shipment->current_longitude ?? $latestTracking?->longitude ?? 72.955568, // Default Gujarat
            'accuracy' => $shipment->location_accuracy ?? $latestTracking?->accuracy ?? 100,
            'last_location_update' => $shipment->last_location_update?->diffForHumans() ?? 'Never',

            'receiver_name' => $shipment->receiver_name,
            'receiver_phone' => $shipment->receiver_phone,
            'destination' => $shipment->city . ', ' . $shipment->state . ' - ' . $shipment->pincode,

            'tracking_history' => $shipment->trackings->map(function($track) {
                return [
                    'status' => $track->status,
                    'status_badge' => $this->getStatusBadge($track->status),
                    'location' => $track->location,
                    'remarks' => $track->remarks,
                    'tracked_at' => $track->tracked_at->format('Y-m-d H:i:s'),
                    'tracked_at_human' => $track->tracked_at->diffForHumans(),
                    'latitude' => $track->latitude,
                    'longitude' => $track->longitude
                ];
            })
        ]);

    } catch (\Exception $e) {
        Log::error('Tracking lookup failed: ' . $e->getMessage(), [
            'tracking_number' => $trackingNumber,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Shipment not found'
        ], 404);
    }
}

/**
 * Helper function to get status badge color
 */
private function getStatusBadge($status)
{
    $badges = [
        'pending' => 'warning',
        'picked' => 'info',
        'in_transit' => 'primary',
        'out_for_delivery' => 'secondary',
        'delivered' => 'success',
        'failed' => 'danger',
        'returned' => 'dark'
    ];

    return $badges[$status] ?? 'secondary';
}

    /**
     * Update live location (Delivery boy API)
     */
  public function updateLocation(Request $request, $id)
{
    $shipment = Shipment::findOrFail($id);
    $shipment->current_latitude = $request->latitude;
    $shipment->current_longitude = $request->longitude;
    $shipment->location_accuracy = $request->accuracy;
    $shipment->last_location_update = now();
    $shipment->save();

    return response()->json(['success' => true]);
}

    /**
     * Calculate shipping charge
     */
    private function calculateShippingCharge($request)
    {
        $baseRate = 50; // Base rate
        $weight = $request->weight ?? 1;

        // Simple calculation - can be made complex based on courier partner
        if ($request->shipping_method === 'express') {
            $rate = $baseRate * 1.5;
        } elseif ($request->shipping_method === 'overnight') {
            $rate = $baseRate * 2;
        } else {
            $rate = $baseRate;
        }

        return $rate * $weight;
    }

    /**
     * Calculate COD charge
     */
    private function calculateCODCharge($request)
    {
        if ($request->payment_mode !== 'cod') {
            return 0;
        }

        $amount = $request->declared_value;
        if ($amount <= 5000) {
            return 30;
        } elseif ($amount <= 10000) {
            return 50;
        } elseif ($amount <= 25000) {
            return 100;
        } else {
            return $amount * 0.005; // 0.5%
        }
    }

    /**
     * Calculate insurance charge
     */
    private function calculateInsuranceCharge($request)
    {
        $amount = $request->declared_value;
        if ($amount > 10000) {
            return $amount * 0.001; // 0.1%
        }
        return 0;
    }

    /**
     * Bulk shipment creation from sales
     */
    public function bulkCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_ids' => 'required|array',
            'sale_ids.*' => 'exists:sales,id',
            'shipping_method' => 'required|in:standard,express,overnight',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $sales = Sale::with('customer')->whereIn('id', $request->sale_ids)->get();
            $created = [];
            $skipped = [];

            foreach ($sales as $sale) {
                // Check if shipment already exists
                if (Shipment::where('sale_id', $sale->id)->exists()) {
                    $skipped[] = $sale->invoice_no;
                    continue;
                }

                // Use Sale model's createShipment method if available
                if (method_exists($sale, 'createShipment')) {
                    $shipment = $sale->createShipment([
                        'shipping_method' => $request->shipping_method
                    ]);
                } else {
                    // Fallback to manual creation
                    $shipment = new Shipment();
                    $shipment->shipment_number = (new Shipment())->generateShipmentNumber();
                    $shipment->sale_id = $sale->id;
                    $shipment->customer_id = $sale->customer_id;
                    $shipment->receiver_name = $sale->customer->name ?? 'Customer';
                    $shipment->receiver_phone = $sale->customer->mobile ?? '';
                    $shipment->shipping_address = $sale->shipping_address ?? $sale->customer->address ?? '';
                    $shipment->city = $sale->city ?? $sale->customer->city ?? '';
                    $shipment->state = $sale->state ?? $sale->customer->state ?? '';
                    $shipment->pincode = $sale->pincode ?? $sale->customer->pincode ?? '';
                    $shipment->declared_value = $sale->grand_total;
                    $shipment->shipping_method = $request->shipping_method;
                    $shipment->payment_mode = $sale->payment_status === 'paid' ? 'prepaid' : 'cod';
                    $shipment->created_by = auth()->id() ?? 1;
                    $shipment->save();

                    $shipment->updateTracking('pending', $shipment->city, 'Bulk shipment created');
                }

                $created[] = $shipment->id;
            }

            DB::commit();

            $message = count($created) . ' shipments created successfully!';
            if (count($skipped) > 0) {
                $message .= ' Skipped ' . count($skipped) . ' sales (already have shipments): ' . implode(', ', $skipped);
            }

            Log::info('Bulk shipment creation completed', [
                'created' => count($created),
                'skipped' => count($skipped)
            ]);

            return redirect()->route('logistics.shipments.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk shipment creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating bulk shipments: ' . $e->getMessage());
        }
    }

    /**
     * Delivery agents management
     */
    public function agents(Request $request)
    {
        $query = DeliveryAgent::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('agent_code', 'like', "%{$search}%");
            });
        }

        $agents = $query->orderBy('name')->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => DeliveryAgent::count(),
            'available' => DeliveryAgent::where('status', 'available')->count(),
            'busy' => DeliveryAgent::where('status', 'busy')->count(),
            'offline' => DeliveryAgent::where('status', 'offline')->count(),
            'total_deliveries' => DeliveryAgent::sum('successful_deliveries')
        ];

        return view('logistics.agents.index', compact('agents', 'stats'));
    }

    /**
     * Shipment reports
     */
    public function reports(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));

        $query = Shipment::whereBetween('created_at', [
            Carbon::parse($fromDate)->startOfDay(),
            Carbon::parse($toDate)->endOfDay()
        ]);

        $stats = [
            'total_shipments' => $query->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'in_transit' => (clone $query)->whereIn('status', ['picked', 'in_transit', 'out_for_delivery'])->count(),
            'failed' => (clone $query)->whereIn('status', ['failed', 'returned'])->count(),
            'total_revenue' => (clone $query)->sum('total_charge'),
            'avg_delivery_time' => (clone $query)->where('status', 'delivered')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, actual_delivery_date)) as avg_time'))
                ->first()->avg_time ?? 0,
            'delivery_rate' => $query->count() > 0
                ? round((clone $query)->where('status', 'delivered')->count() / $query->count() * 100, 2)
                : 0
        ];

        // By courier partner
        $byCourier = (clone $query)
            ->select('courier_partner', DB::raw('count(*) as total'), DB::raw('sum(total_charge) as revenue'))
            ->whereNotNull('courier_partner')
            ->groupBy('courier_partner')
            ->orderBy('total', 'desc')
            ->get();

        // By city
        $byCity = (clone $query)
            ->select('city', DB::raw('count(*) as total'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Daily trend
        $dailyTrend = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('logistics.reports.index', compact(
            'stats',
            'byCourier',
            'byCity',
            'dailyTrend',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Calculate delivery progress percentage
     */
    private function calculateDeliveryProgress($shipment)
    {
        $statusOrder = [
            'pending' => 0,
            'picked' => 20,
            'in_transit' => 40,
            'out_for_delivery' => 70,
            'delivered' => 100,
            'failed' => 100,
            'returned' => 100
        ];

        return $statusOrder[$shipment->status] ?? 0;
    }

    /**
     * Get shipment by tracking number (web view)
     */
    public function trackWeb($trackingNumber)
    {
        $shipment = Shipment::with(['trackings' => function($q) {
                $q->orderBy('tracked_at', 'desc');
            }])
            ->where('tracking_number', $trackingNumber)
            ->orWhere('shipment_number', $trackingNumber)
            ->firstOrFail();

        return view('logistics.track-public', compact('shipment'));
    }

    /**
     * Get delivery agents for map
     */
    public function getAgentsForMap()
    {
        $agents = DeliveryAgent::where('is_active', true)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->get(['id', 'name', 'current_latitude', 'current_longitude', 'status']);

        return response()->json([
            'success' => true,
            'agents' => $agents
        ]);
    }
}
