<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionLogAdminController extends Controller
{
    /**
     * Apply shared filters to query (reused by index & export).
     */
    private function applyFilters(Request $request)
    {
        $query = TransactionLog::with('order')->latest('created_at');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->applyFilters($request);
        $logs  = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'           => TransactionLog::count(),
            'today'           => TransactionLog::whereDate('created_at', today())->count(),
            'payment_success' => TransactionLog::where('event', 'payment_success')->count(),
            'payment_failed'  => TransactionLog::where('event', 'payment_failed')
                                    ->orWhere('event', 'payment_expired')
                                    ->count(),
        ];

        return view('admin.transaction-logs.index', compact('logs', 'stats'));
    }

    public function show(TransactionLog $log)
    {
        $log->load('order.items');
        return view('admin.transaction-logs.show', compact('log'));
    }

    /**
     * Export transaction logs to CSV.
     * Respects the same filters as the index page.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->applyFilters($request);

        // Build filename with date range
        $parts = ['log-transaksi'];
        if ($request->filled('date_from')) {
            $parts[] = $request->date_from;
        }
        if ($request->filled('date_to')) {
            $parts[] = $request->date_to;
        }
        if (count($parts) === 1) {
            $parts[] = now()->format('Y-m-d');
        }
        $filename = implode('_', $parts) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'No',
                'Waktu',
                'Kode Order',
                'Customer',
                'Event',
                'Sumber',
                'Tipe Pembayaran',
                'Status Midtrans',
                'Payment Sebelum',
                'Payment Sesudah',
                'Order Status Sebelum',
                'Order Status Sesudah',
                'IP Address',
                'Total Pesanan',
            ]);

            // Stream data in chunks to avoid memory issues
            $no = 0;
            $query->chunk(500, function ($logs) use ($handle, &$no) {
                foreach ($logs as $log) {
                    $no++;
                    fputcsv($handle, [
                        $no,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->order_code,
                        $log->order->customer_name ?? '-',
                        $log->event_label,
                        $log->source_label,
                        $log->payment_type ?? '-',
                        $log->transaction_status ?? '-',
                        $log->payment_status_from ? strtoupper($log->payment_status_from) : '-',
                        $log->payment_status_to ? strtoupper($log->payment_status_to) : '-',
                        $log->order_status_from ? ucfirst($log->order_status_from) : '-',
                        $log->order_status_to ? ucfirst($log->order_status_to) : '-',
                        $log->ip_address ?? '-',
                        $log->order ? $log->order->total : 0,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
