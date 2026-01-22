<?php

namespace App\Services;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class CalculationService
{
    protected ExpressionLanguage $expressionLanguage;

    public function __construct()
    {
        $this->expressionLanguage = new ExpressionLanguage();
    }

    /**
     * Calculate amount based on calculation type and params
     */
    public function calculate(string $type, array $params, ?string $customFormula = null): float
    {
        return match($type) {
            'simple' => $this->calculateSimple($params),
            'area' => $this->calculateArea($params),
            'volume' => $this->calculateVolume($params),
            'perimeter' => $this->calculatePerimeter($params),
            'circumference' => $this->calculateCircumference($params),
            'custom' => $this->calculateCustom($params, $customFormula),
            default => $this->calculateSimple($params),
        };
    }

    protected function calculateSimple(array $params): float
    {
        $quantity = $params['quantity'] ?? 0;
        $rate = $params['rate'] ?? 0;
        
        return round($quantity * $rate, 2);
    }

    protected function calculateArea(array $params): float
    {
        $length = $params['length'] ?? 0;
        $width = $params['width'] ?? 0;
        $rate = $params['rate'] ?? 0;
        
        return round($length * $width * $rate, 2);
    }

    protected function calculateVolume(array $params): float
    {
        $length = $params['length'] ?? 0;
        $width = $params['width'] ?? 0;
        $height = $params['height'] ?? 0;
        $rate = $params['rate'] ?? 0;
        
        return round($length * $width * $height * $rate, 2);
    }

    protected function calculatePerimeter(array $params): float
    {
        $length = $params['length'] ?? 0;
        $width = $params['width'] ?? 0;
        $height = $params['height'] ?? 0;
        $rate = $params['rate'] ?? 0;
        
        $perimeter = 2 * ($length + $width);
        return round($perimeter * $height * $rate, 2);
    }

    protected function calculateCircumference(array $params): float
    {
        $diameter = $params['diameter'] ?? 0;
        $rate = $params['rate'] ?? 0;
        
        $circumference = pi() * $diameter;
        return round($circumference * $rate, 2);
    }

    protected function calculateCustom(array $params, ?string $formula): float
    {
        if (!$formula) {
            return 0;
        }

        try {
            // Evaluate the formula with parameters
            $result = $this->expressionLanguage->evaluate($formula, $params);
            return round($result, 2);
        } catch (\Exception $e) {
            \Log::error('Calculation error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Validate formula syntax
     */
    public function validateFormula(string $formula, array $params): bool
    {
        try {
            $this->expressionLanguage->evaluate($formula, $params);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
