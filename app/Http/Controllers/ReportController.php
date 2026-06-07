<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('inventory.reports', $this->reportData($request, true));
    }

    public function print(Request $request): View
    {
        return view('inventory.report-print', $this->reportData($request, false));
    }

    /**
     * @return array<string, mixed>
     */
    private function reportData(Request $request, bool $paginate): array
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = isset($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : null;
        $to = isset($filters['to']) ? Carbon::parse($filters['to'])->endOfDay() : null;

        $movementQuery = StockMovement::query();
        $this->applyPeriod($movementQuery, $from, $to);

        $rows = (clone $movementQuery)
            ->with('item')
            ->latest('occurred_at');

        /** @var LengthAwarePaginator|\Illuminate\Support\Collection<int, StockMovement> $movements */
        $movements = $paginate
            ? $rows->paginate(15)->withQueryString()
            : $rows->get();

        return [
            'filters' => $filters,
            'items' => Item::query()->orderBy('name')->get(),
            'movements' => $movements,
            'summary' => [
                'totalItems' => Item::query()->count(),
                'lowStock' => Item::query()->whereColumn('stock', '<=', 'min_stock')->count(),
                'incoming' => (clone $movementQuery)->where('type', 'in')->sum('quantity'),
                'outgoing' => (clone $movementQuery)->where('type', 'out')->sum('quantity'),
                'adjustments' => (clone $movementQuery)->where('type', 'adjustment')->count(),
                'movementCount' => (clone $movementQuery)->count(),
            ],
            'periodLabel' => $this->periodLabel($from, $to),
        ];
    }

    private function applyPeriod(Builder $query, ?Carbon $from, ?Carbon $to): void
    {
        if ($from !== null) {
            $query->where('occurred_at', '>=', $from);
        }

        if ($to !== null) {
            $query->where('occurred_at', '<=', $to);
        }
    }

    private function periodLabel(?Carbon $from, ?Carbon $to): string
    {
        if ($from === null && $to === null) {
            return 'Semua periode';
        }

        if ($from !== null && $to !== null) {
            return $from->format('d M Y').' sampai '.$to->format('d M Y');
        }

        if ($from !== null) {
            return 'Mulai '.$from->format('d M Y');
        }

        return 'Sampai '.$to?->format('d M Y');
    }
}
