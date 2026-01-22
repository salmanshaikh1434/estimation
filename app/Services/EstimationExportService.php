<?php

namespace App\Services;

use App\Models\Estimation;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EstimationExportService
{
    protected Spreadsheet $spreadsheet;
    protected Estimation $estimation;

    public function export(Estimation $estimation, array $selectedSheets): string
    {
        $this->estimation = $estimation;
        $this->estimation->load(['project', 'items.measurements', 'user']);
        
        $this->spreadsheet = new Spreadsheet();
        
        // Remove default sheet
        $this->spreadsheet->removeSheetByIndex(0);
        
        // Generate selected sheets in order
        $sheetOrder = ['cover', 'certificate', 'est', 'mts', 'abst', 'recap', 'ra', 'form63'];
        
        foreach ($sheetOrder as $sheetId) {
            if (in_array($sheetId, $selectedSheets)) {
                match($sheetId) {
                    'cover' => $this->generateCoverSheet(),
                    'certificate' => $this->generateCertificateSheet(),
                    'est' => $this->generateEstimationSheet(),
                    'mts' => $this->generateMeasurementSheet(),
                    'abst' => $this->generateAbstractSheet(),
                    'recap' => $this->generateRecapSheet(),
                    'ra' => $this->generateRateAnalysisSheet(),
                    'form63' => $this->generateForm63Sheet(),
                    default => null,
                };
            }
        }
        
        // Set first sheet as active
        $this->spreadsheet->setActiveSheetIndex(0);
        
        // Save to file
        $filename = 'estimation_' . $estimation->id . '_' . time() . '.xlsx';
        $filepath = storage_path('app/exports/' . $filename);
        
        // Create exports directory if it doesn't exist
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filepath);
        
        return $filepath;
    }

    protected function generateCoverSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Cover');
        
        // Title
        $sheet->setCellValue('A1', 'DETAILED ESTIMATE');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Project details
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Project Name:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->name);
        $sheet->mergeCells("B{$row}:F{$row}");
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Project Code:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->code);
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Location:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->location);
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Client:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->client);
        
        $row += 2;
        $sheet->setCellValue("A{$row}", 'Estimation Name:');
        $sheet->setCellValue("B{$row}", $this->estimation->name);
        $sheet->mergeCells("B{$row}:F{$row}");
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Description:');
        $sheet->setCellValue("B{$row}", $this->estimation->description);
        $sheet->mergeCells("B{$row}:F{$row}");
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Rate Type:');
        $sheet->setCellValue("B{$row}", strtoupper($this->estimation->rate_type));
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Status:');
        $sheet->setCellValue("B{$row}", strtoupper($this->estimation->status));
        
        $row += 2;
        $sheet->setCellValue("A{$row}", 'Total Amount:');
        $sheet->setCellValue("B{$row}", '₹ ' . number_format($this->estimation->total_amount, 2));
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true)->setSize(14);
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function generateCertificateSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Certificate');
        
        $sheet->setCellValue('A1', 'CERTIFICATE');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Certified that I have checked and verified the rates, quantities and calculations');
        $sheet->mergeCells("A{$row}:F{$row}");
        
        $row++;
        $sheet->setCellValue("A{$row}", 'of this estimate and found them to be correct.');
        $sheet->mergeCells("A{$row}:F{$row}");
        
        $row += 3;
        if ($this->estimation->project->prepared_by) {
            $sheet->setCellValue("A{$row}", 'Prepared By:');
            $sheet->setCellValue("B{$row}", $this->estimation->project->prepared_by);
            $row++;
        }
        
        if ($this->estimation->project->checked_by) {
            $sheet->setCellValue("A{$row}", 'Checked By:');
            $sheet->setCellValue("B{$row}", $this->estimation->project->checked_by);
            $row++;
        }
        
        if ($this->estimation->project->approved_by) {
            $sheet->setCellValue("A{$row}", 'Approved By:');
            $sheet->setCellValue("B{$row}", $this->estimation->project->approved_by);
        }
    }

    protected function generateEstimationSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('EST');
        
        // Headers
        $sheet->setCellValue('A1', 'ESTIMATION SUMMARY');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Sr. No.');
        $sheet->setCellValue("B{$row}", 'Item Code');
        $sheet->setCellValue("C{$row}", 'Description');
        $sheet->setCellValue("D{$row}", 'Quantity');
        $sheet->setCellValue("E{$row}", 'Unit');
        $sheet->setCellValue("F{$row}", 'Rate');
        $sheet->setCellValue("G{$row}", 'Amount');
        
        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('CCCCCC');
        
        $row++;
        $srNo = 1;
        
        foreach ($this->estimation->items as $item) {
            $rateModel = $item->getRateModel();
            
            $sheet->setCellValue("A{$row}", $srNo++);
            $sheet->setCellValue("B{$row}", $rateModel->item_code ?? 'N/A');
            $sheet->setCellValue("C{$row}", $rateModel->description ?? 'N/A');
            $sheet->setCellValue("D{$row}", $item->quantity);
            $sheet->setCellValue("E{$row}", $rateModel->unit ?? 'N/A');
            $sheet->setCellValue("F{$row}", $item->rate);
            $sheet->setCellValue("G{$row}", $item->amount);
            
            $row++;
        }
        
        // Sub-total
        $sheet->setCellValue("F{$row}", 'Sub-Total:');
        $sheet->setCellValue("G{$row}", $this->estimation->sub_total);
        $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true);
        $row++;
        
        // Royalty
        if ($this->estimation->royalty_amount > 0) {
            $sheet->setCellValue("F{$row}", 'Royalty:');
            $sheet->setCellValue("G{$row}", $this->estimation->royalty_amount);
            $row++;
        }
        
        // Contingency
        $contingencyAmount = ($this->estimation->sub_total + $this->estimation->royalty_amount) * 
                            ($this->estimation->contingency_percentage / 100);
        $sheet->setCellValue("F{$row}", "Contingency ({$this->estimation->contingency_percentage}%):");
        $sheet->setCellValue("G{$row}", $contingencyAmount);
        $row++;
        
        // GST
        $afterContingency = $this->estimation->sub_total + $this->estimation->royalty_amount + $contingencyAmount;
        $gstAmount = $afterContingency * ($this->estimation->gst_percentage / 100);
        $sheet->setCellValue("F{$row}", "GST ({$this->estimation->gst_percentage}%):");
        $sheet->setCellValue("G{$row}", $gstAmount);
        $row++;
        
        // Grand Total
        $sheet->setCellValue("F{$row}", 'Grand Total:');
        $sheet->setCellValue("G{$row}", $this->estimation->total_amount);
        $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("G{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00');
        
        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function generateMeasurementSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('MTS');
        
        $sheet->setCellValue('A1', 'MEASUREMENT SHEET');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Project: ' . $this->estimation->project->name);
        $sheet->mergeCells("A{$row}:H{$row}");
        $row++;
        $sheet->setCellValue("A{$row}", 'Estimation: ' . $this->estimation->name);
        $sheet->mergeCells("A{$row}:H{$row}");
        $row += 2;
        
        foreach ($this->estimation->items as $item) {
            $rateModel = $item->getRateModel();
            
            // Item header
            $sheet->setCellValue("A{$row}", $rateModel->item_code ?? 'N/A');
            $sheet->setCellValue("B{$row}", $rateModel->description ?? 'N/A');
            $sheet->mergeCells("B{$row}:H{$row}");
            $sheet->getStyle("A{$row}:H{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}:H{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E0E0E0');
            $row++;
            
            // Measurement table headers
            $sheet->setCellValue("A{$row}", 'No.');
            $sheet->setCellValue("B{$row}", 'Length (m)');
            $sheet->setCellValue("C{$row}", 'Breadth (m)');
            $sheet->setCellValue("D{$row}", 'Height (m)');
            $sheet->setCellValue("E{$row}", 'Number');
            $sheet->setCellValue("F{$row}", 'Quantity');
            $sheet->setCellValue("G{$row}", 'Unit');
            $sheet->setCellValue("H{$row}", 'Remarks');
            $sheet->getStyle("A{$row}:H{$row}")->getFont()->setBold(true);
            $row++;
            
            // Measurement rows
            if ($item->measurements->count() > 0) {
                foreach ($item->measurements as $measurement) {
                    $sheet->setCellValue("A{$row}", $measurement->row_number);
                    $sheet->setCellValue("B{$row}", $measurement->length ?? '-');
                    $sheet->setCellValue("C{$row}", $measurement->breadth ?? '-');
                    $sheet->setCellValue("D{$row}", $measurement->height ?? '-');
                    $sheet->setCellValue("E{$row}", $measurement->number);
                    $sheet->setCellValue("F{$row}", number_format($measurement->quantity, 3));
                    $sheet->setCellValue("G{$row}", $rateModel->unit ?? 'N/A');
                    $sheet->setCellValue("H{$row}", $measurement->remarks);
                    $row++;
                }
            } else {
                // No measurements, show direct quantity
                $sheet->setCellValue("A{$row}", '1');
                $sheet->setCellValue("B{$row}", '-');
                $sheet->setCellValue("C{$row}", '-');
                $sheet->setCellValue("D{$row}", '-');
                $sheet->setCellValue("E{$row}", '1');
                $sheet->setCellValue("F{$row}", number_format($item->quantity, 3));
                $sheet->setCellValue("G{$row}", $rateModel->unit ?? 'N/A');
                $sheet->setCellValue("H{$row}", 'Direct quantity');
                $row++;
            }
            
            // Total row
            $sheet->setCellValue("E{$row}", 'Total:');
            $sheet->setCellValue("F{$row}", number_format($item->quantity, 3));
            $sheet->setCellValue("G{$row}", $rateModel->unit ?? 'N/A');
            $sheet->getStyle("E{$row}:G{$row}")->getFont()->setBold(true);
            $sheet->getStyle("E{$row}:G{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D0E0FF');
            $row++;
            
            // Rate and Amount rows
            $sheet->setCellValue("E{$row}", 'Rate:');
            $sheet->setCellValue("F{$row}", '₹ ' . number_format($item->rate, 2));
            $sheet->setCellValue("G{$row}", 'per ' . ($rateModel->unit ?? 'unit'));
            $row++;
            
            $sheet->setCellValue("E{$row}", 'Amount:');
            $sheet->setCellValue("F{$row}", '₹ ' . number_format($item->amount, 2));
            $sheet->getStyle("E{$row}:F{$row}")->getFont()->setBold(true);
            $sheet->getStyle("F{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('90EE90');
            $row += 2; // Space between items
        }
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function generateAbstractSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('ABST');
        
        $sheet->setCellValue('A1', 'ABSTRACT');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Sr. No.');
        $sheet->setCellValue("B{$row}", 'Description');
        $sheet->setCellValue("C{$row}", 'Amount');
        $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $srNo = 1;
        $sheet->setCellValue("A{$row}", $srNo++);
        $sheet->setCellValue("B{$row}", 'Sub-Total (Items)');
        $sheet->setCellValue("C{$row}", number_format($this->estimation->sub_total, 2));
        $row++;
        
        if ($this->estimation->royalty_amount > 0) {
            $sheet->setCellValue("A{$row}", $srNo++);
            $sheet->setCellValue("B{$row}", 'Royalty');
            $sheet->setCellValue("C{$row}", number_format($this->estimation->royalty_amount, 2));
            $row++;
        }
        
        $contingencyAmount = ($this->estimation->sub_total + $this->estimation->royalty_amount) * 
                            ($this->estimation->contingency_percentage / 100);
        $sheet->setCellValue("A{$row}", $srNo++);
        $sheet->setCellValue("B{$row}", "Contingency @ {$this->estimation->contingency_percentage}%");
        $sheet->setCellValue("C{$row}", number_format($contingencyAmount, 2));
        $row++;
        
        $afterContingency = $this->estimation->sub_total + $this->estimation->royalty_amount + $contingencyAmount;
        $gstAmount = $afterContingency * ($this->estimation->gst_percentage / 100);
        $sheet->setCellValue("A{$row}", $srNo++);
        $sheet->setCellValue("B{$row}", "GST @ {$this->estimation->gst_percentage}%");
        $sheet->setCellValue("C{$row}", number_format($gstAmount, 2));
        $row++;
        
        $sheet->setCellValue("B{$row}", 'Grand Total');
        $sheet->setCellValue("C{$row}", number_format($this->estimation->total_amount, 2));
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true)->setSize(12);
        
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function generateRecapSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('RECAP');
        
        $sheet->setCellValue('A1', 'RECAPITULATION');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Total Items:');
        $sheet->setCellValue("B{$row}", $this->estimation->items->count());
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Total Quantity:');
        $sheet->setCellValue("B{$row}", number_format($this->estimation->items->sum('quantity'), 3));
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Estimated Amount:');
        $sheet->setCellValue("B{$row}", '₹ ' . number_format($this->estimation->total_amount, 2));
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
    }

    protected function generateRateAnalysisSheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('RA');
        
        $sheet->setCellValue('A1', 'RATE ANALYSIS');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Item Code');
        $sheet->setCellValue("B{$row}", 'Description');
        $sheet->setCellValue("C{$row}", 'Rate Type');
        $sheet->setCellValue("D{$row}", 'Rate');
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $row++;
        
        foreach ($this->estimation->items as $item) {
            $rateModel = $item->getRateModel();
            $sheet->setCellValue("A{$row}", $rateModel->item_code ?? 'N/A');
            $sheet->setCellValue("B{$row}", $rateModel->description ?? 'N/A');
            $sheet->setCellValue("C{$row}", strtoupper($item->rate_type));
            $sheet->setCellValue("D{$row}", '₹ ' . number_format($item->rate, 2));
            $row++;
        }
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function generateForm63Sheet(): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Form-63');
        
        $sheet->setCellValue('A1', 'FORM-63');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet->setCellValue("A{$row}", 'Government Form for Detailed Estimate');
        $sheet->mergeCells("A{$row}:F{$row}");
        $row += 2;
        
        $sheet->setCellValue("A{$row}", 'Project:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->name);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Estimated Cost:');
        $sheet->setCellValue("B{$row}", '₹ ' . number_format($this->estimation->total_amount, 2));
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Financial Year:');
        $sheet->setCellValue("B{$row}", $this->estimation->project->financial_year ?? 'N/A');
    }
}
