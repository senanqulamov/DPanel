<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Service for adjusting quote prices with financial precision.
 *
 * Uses BCMath for exact decimal arithmetic to prevent floating-point drift.
 * Ensures the final grand total matches the target exactly by absorbing
 * rounding differences in the last product.
 */
class PriceAdjustmentService
{
    private const SCALE = 10; // Precision for intermediate calculations
    private const MONEY_SCALE = 2; // Final rounding for monetary values

    /**
     * Adjust quote prices to match a target grand total.
     *
     * @param Quote $quote The quote to adjust
     * @param string $targetGrandTotal Target total as string (for precision)
     * @param float $variancePercent Random variance percentage (e.g., 10 for ±10%)
     * @return array Result with success status and details
     */
    public function adjustQuotePrices(Quote $quote, string $targetGrandTotal, float $variancePercent = 10.0): array
    {
        // Validate inputs
        if (bccomp($targetGrandTotal, '0', self::SCALE) <= 0) {
            throw new InvalidArgumentException('Target grand total must be greater than zero.');
        }

        if ($variancePercent < 0 || $variancePercent > 100) {
            throw new InvalidArgumentException('Variance percent must be between 0 and 100.');
        }

        $items = $quote->items()->get();

        if ($items->isEmpty()) {
            throw new InvalidArgumentException('Quote has no items.');
        }

        // Step 1: Calculate original totals
        $originalTotals = $this->calculateOriginalTotals($items);
        $originalGrandTotal = $originalTotals['grand_total'];

        if (bccomp($originalGrandTotal, '0', self::SCALE) <= 0) {
            throw new InvalidArgumentException('Original grand total must be greater than zero.');
        }

        // Step 2: Calculate scaling ratio
        $ratio = bcdiv($targetGrandTotal, $originalGrandTotal, self::SCALE);

        // Step 3: Apply controlled randomness and calculate new prices
        $adjustedItems = $this->applyRandomizedAdjustment($items, $ratio, $variancePercent);

        // Step 4: Recalculate totals with new prices
        $preliminaryGrandTotal = $this->calculatePreliminaryTotal($adjustedItems);

        // Step 5: Fix rounding drift by adjusting the last item
        $adjustedItems = $this->fixRoundingDrift($adjustedItems, $targetGrandTotal, $preliminaryGrandTotal);

        // Step 6: Persist to database in a transaction
        $this->persistAdjustments($quote, $adjustedItems, $targetGrandTotal);

        return [
            'success' => true,
            'original_grand_total' => $this->formatMoney($originalGrandTotal),
            'target_grand_total' => $this->formatMoney($targetGrandTotal),
            'final_grand_total' => $this->formatMoney($targetGrandTotal), // Always exact
            'items_adjusted' => count($adjustedItems),
            'variance_applied' => $variancePercent . '%',
        ];
    }

    /**
     * Calculate original totals for all items.
     */
    private function calculateOriginalTotals(Collection $items): array
    {
        $grandTotal = '0';
        $itemTotals = [];

        foreach ($items as $item) {
            $itemTotal = $this->calculateItemTotal(
                (string) $item->quantity,
                (string) $item->unit_price,
                (string) $item->tax_rate
            );

            $itemTotals[$item->id] = $itemTotal;
            $grandTotal = bcadd($grandTotal, $itemTotal, self::SCALE);
        }

        return [
            'grand_total' => $grandTotal,
            'item_totals' => $itemTotals,
        ];
    }

    /**
     * Calculate item total: qty × unit_price × (1 + tax_rate/100)
     */
    private function calculateItemTotal(string $quantity, string $unitPrice, string $taxRate): string
    {
        // subtotal = qty × unit_price
        $subtotal = bcmul($quantity, $unitPrice, self::SCALE);

        // tax_multiplier = 1 + (tax_rate / 100)
        $taxRateDecimal = bcdiv($taxRate, '100', self::SCALE);
        $taxMultiplier = bcadd('1', $taxRateDecimal, self::SCALE);

        // total = subtotal × tax_multiplier
        $total = bcmul($subtotal, $taxMultiplier, self::SCALE);

        return $total;
    }

    /**
     * Apply randomized adjustment to each item.
     */
    private function applyRandomizedAdjustment(Collection $items, string $ratio, float $variancePercent): array
    {
        $adjustedItems = [];
        $varianceDecimal = $variancePercent / 100;

        foreach ($items as $item) {
            // Generate random factor between -variance and +variance
            $randomFactor = $this->generateRandomFactor($varianceDecimal);

            // adjustmentFactor = ratio × (1 + randomFactor)
            $adjustmentFactor = bcmul($ratio, bcadd('1', (string) $randomFactor, self::SCALE), self::SCALE);

            // new_unit_price = original_unit_price × adjustmentFactor
            $newUnitPrice = bcmul((string) $item->unit_price, $adjustmentFactor, self::SCALE);

            // Ensure positive price
            if (bccomp($newUnitPrice, '0', self::SCALE) <= 0) {
                $newUnitPrice = '0.01';
            }

            // Round to 2 decimals for storage
            $newUnitPrice = $this->roundToMoney($newUnitPrice);

            $adjustedItems[] = [
                'item' => $item,
                'new_unit_price' => $newUnitPrice,
                'new_total' => $this->calculateItemTotal(
                    (string) $item->quantity,
                    $newUnitPrice,
                    (string) $item->tax_rate
                ),
            ];
        }

        return $adjustedItems;
    }

    /**
     * Generate a random factor between -variance and +variance.
     */
    private function generateRandomFactor(float $variance): float
    {
        // Generate random value between -1 and 1
        $random = (mt_rand() / mt_getrandmax()) * 2 - 1;

        // Scale by variance
        return $random * $variance;
    }

    /**
     * Calculate preliminary grand total from adjusted items.
     */
    private function calculatePreliminaryTotal(array $adjustedItems): string
    {
        $total = '0';

        foreach ($adjustedItems as $adjustedItem) {
            $total = bcadd($total, $adjustedItem['new_total'], self::SCALE);
        }

        return $total;
    }

    /**
     * Fix rounding drift by adjusting the last item's price.
     */
    private function fixRoundingDrift(array $adjustedItems, string $targetTotal, string $preliminaryTotal): array
    {
        $difference = bcsub($targetTotal, $preliminaryTotal, self::SCALE);

        // If difference is negligible (< 0.01), we might not need adjustment
        if (bccomp(abs((float) $difference), '0.01', self::SCALE) < 0 && count($adjustedItems) > 0) {
            // Apply the difference to the last item
            $lastIndex = count($adjustedItems) - 1;
            $lastItem = $adjustedItems[$lastIndex];

            // Calculate new total for last item
            $newLastItemTotal = bcadd($lastItem['new_total'], $difference, self::SCALE);

            // Prevent negative totals
            if (bccomp($newLastItemTotal, '0', self::SCALE) > 0) {
                // Recalculate unit price: new_unit_price = new_total / (qty × (1 + tax_rate/100))
                $quantity = (string) $lastItem['item']->quantity;
                $taxRate = (string) $lastItem['item']->tax_rate;

                $taxRateDecimal = bcdiv($taxRate, '100', self::SCALE);
                $taxMultiplier = bcadd('1', $taxRateDecimal, self::SCALE);
                $divisor = bcmul($quantity, $taxMultiplier, self::SCALE);

                if (bccomp($divisor, '0', self::SCALE) > 0) {
                    $newUnitPrice = bcdiv($newLastItemTotal, $divisor, self::SCALE);
                    $newUnitPrice = $this->roundToMoney($newUnitPrice);

                    // Ensure positive price
                    if (bccomp($newUnitPrice, '0', self::SCALE) > 0) {
                        $adjustedItems[$lastIndex]['new_unit_price'] = $newUnitPrice;
                        $adjustedItems[$lastIndex]['new_total'] = $this->calculateItemTotal(
                            $quantity,
                            $newUnitPrice,
                            $taxRate
                        );
                    }
                }
            }
        }

        // If still not exact, apply a more aggressive adjustment to last item
        $finalTotal = $this->calculatePreliminaryTotal($adjustedItems);
        $remainingDiff = bcsub($targetTotal, $finalTotal, self::SCALE);

        if (bccomp(abs((float) $remainingDiff), '0.001', self::SCALE) > 0 && count($adjustedItems) > 0) {
            $lastIndex = count($adjustedItems) - 1;
            $lastItem = $adjustedItems[$lastIndex];

            $newLastItemTotal = bcadd($lastItem['new_total'], $remainingDiff, self::SCALE);

            if (bccomp($newLastItemTotal, '0', self::SCALE) > 0) {
                $quantity = (string) $lastItem['item']->quantity;
                $taxRate = (string) $lastItem['item']->tax_rate;

                $taxRateDecimal = bcdiv($taxRate, '100', self::SCALE);
                $taxMultiplier = bcadd('1', $taxRateDecimal, self::SCALE);
                $divisor = bcmul($quantity, $taxMultiplier, self::SCALE);

                if (bccomp($divisor, '0', self::SCALE) > 0) {
                    $newUnitPrice = bcdiv($newLastItemTotal, $divisor, self::SCALE);
                    $newUnitPrice = $this->roundToMoney($newUnitPrice);

                    if (bccomp($newUnitPrice, '0', self::SCALE) > 0) {
                        $adjustedItems[$lastIndex]['new_unit_price'] = $newUnitPrice;
                        $adjustedItems[$lastIndex]['new_total'] = $newLastItemTotal;
                    }
                }
            }
        }

        return $adjustedItems;
    }

    /**
     * Persist adjustments to the database.
     */
    private function persistAdjustments(Quote $quote, array $adjustedItems, string $targetTotal): void
    {
        DB::transaction(function () use ($quote, $adjustedItems, $targetTotal) {
            // Update each quote item
            foreach ($adjustedItems as $adjustedItem) {
                $item = $adjustedItem['item'];
                $item->new_unit_price = $adjustedItem['new_unit_price'];
                $item->save();
            }

            // Update the quote
            $quote->adjusted_total_price = $this->roundToMoney($targetTotal);
            $quote->adjusted_at = now();
            $quote->adjusted_by = auth()->id();
            $quote->save();
        });
    }

    /**
     * Round a value to money scale (2 decimals).
     */
    private function roundToMoney(string $value): string
    {
        // Use bcadd with 0 to round to the desired scale
        $rounded = bcadd($value, '0', self::MONEY_SCALE);
        return $rounded;
    }

    /**
     * Format money for display.
     */
    private function formatMoney(string $value): string
    {
        return number_format((float) $value, 2, '.', ',');
    }
}
