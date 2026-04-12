<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Trait\ApiResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponse;

    public function overview()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['pending', 'sending', 'completed', 'refunded'];

        $formatted = [];

        foreach ($statuses as $status) {
            $formatted[] = [
                'status' => $status,
                'total' => $orders[$status] ?? 0,
            ];
        }

        return $this->successResponse([
            'user' => $user,
            'orders' => $formatted,
        ], 'Dashboard overview retrieved successfully');
    }

    public function myOrders(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');

        $user = Auth::user();

        $ordersQuery = $user->orders()
            ->with('product')

            ->when($filter, function ($q) use ($filter) {
                $filters = explode(',', $filter);
                $q->whereIn('status', $filters);
            })

            ->when($search, function ($q) use ($search) {
                $q->whereHasMorph(
                    'product',
                    ['App\Models\Card', 'App\Models\DirectTopUp'],
                    function ($query) use ($search) {
                        $query->where(function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                    }
                );
            });

        $orders = $ordersQuery->latest()->get();

        return $this->successResponse($orders, 'My orders retrieved successfully');
    }

    public function myCards()
    {
        $user = Auth::user();

        $cards = $user->userCards()->latest()->get();

        return $this->successResponse($cards, 'My cards retrieved successfully');
    }

    public function invoices()
    {
        $user = Auth::user();

        $invoices = $user->invoices()
            ->with('payment', 'items')
            ->latest()
            ->get();

        return $this->successResponse($invoices, 'My invoices retrieved successfully');
    }

    public function downloadInvoice(Request $request)
    {
        $user = Auth::user();

        $ids = $request->invoice_ids;

        $invoices = Invoice::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->get();

        if ($invoices->isEmpty()) {
            return $this->errorResponse('No invoices found', 404);
        }

        $data = [];

        foreach ($invoices as $invoice) {
            $data[] = $invoice->toPdf();
        }

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoices' => $data,
        ]);

        return $pdf->download('bulk-invoices.pdf');
    }
}
