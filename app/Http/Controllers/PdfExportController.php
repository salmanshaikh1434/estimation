<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportController extends Controller
{
    /**
     * Export Abstract of Cost PDF
     */
    public function abstractOfCost(Estimation $estimation)
    {
        $estimation->load(['project', 'items' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $data = [
            'estimation' => $estimation,
            'project' => $estimation->project,
            'items' => $estimation->items,
            'generated_date' => now()->format('d-m-Y'),
        ];

        $pdf = Pdf::loadView('pdf.abstract-of-cost', $data);
        
        $filename = 'Abstract_' . str_replace(' ', '_', $estimation->name) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export Measurement Sheet PDF
     */
    public function measurementSheet(Estimation $estimation)
    {
        $estimation->load(['project', 'items.measurements' => function ($query) {
            $query->orderBy('row_number');
        }]);

        // Only include items that have measurements
        $itemsWithMeasurements = $estimation->items->filter(function ($item) {
            return $item->measurements->count() > 0;
        });

        $data = [
            'estimation' => $estimation,
            'project' => $estimation->project,
            'items' => $itemsWithMeasurements,
            'generated_date' => now()->format('d-m-Y'),
        ];

        $pdf = Pdf::loadView('pdf.measurement-sheet', $data);
        
        $filename = 'Measurements_' . str_replace(' ', '_', $estimation->name) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export Complete Report PDF
     */
    public function completeReport(Estimation $estimation)
    {
        $estimation->load([
            'project',
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items.measurements' => function ($query) {
                $query->orderBy('row_number');
            }
        ]);

        $data = [
            'estimation' => $estimation,
            'project' => $estimation->project,
            'items' => $estimation->items,
            'generated_date' => now()->format('d-m-Y'),
        ];

        $pdf = Pdf::loadView('pdf.complete-report', $data)
            ->setPaper('a4', 'portrait');
        
        $filename = 'Complete_Report_' . str_replace(' ', '_', $estimation->name) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
