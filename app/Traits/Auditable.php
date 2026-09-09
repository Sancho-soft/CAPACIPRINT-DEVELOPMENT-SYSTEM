<?php

namespace App\Traits;

use App\Models\AuditLog;

/**
 * Automatically logs create, update, and delete events on any Eloquent model
 * that uses this trait. Captures old/new values for change tracking.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            self::logAuditEvent($model, 'Created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) return;

            $original = collect($model->getOriginal())
                ->only(array_keys($dirty))
                ->toArray();

            self::logAuditEvent($model, 'Updated', $original, $dirty);
        });

        static::deleted(function ($model) {
            self::logAuditEvent($model, 'Deleted', $model->getOriginal(), null);
        });
    }

    private static function logAuditEvent($model, string $action, ?array $oldValues, ?array $newValues): void
    {
        // Don't log audit logs themselves (infinite loop prevention)
        if ($model instanceof AuditLog) return;

        // Skip if running in console (seeders/migrations) to avoid noise
        if (app()->runningInConsole()) return;

        $className = class_basename($model);
        $module = self::resolveModule($className);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'event'       => "{$className} {$action}",
            'module'      => $module,
            'ip_address'  => request()->ip(),
            'description' => "{$className} #{$model->getKey()} was {$action} by " . (auth()->user()->name ?? 'System'),
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
        ]);
    }

    /**
     * Map model class names to their system module names.
     */
    private static function resolveModule(string $className): string
    {
        return match ($className) {
            'Order'               => 'Order Management',
            'PrintRequest'        => 'Print Request Management',
            'Quotation'           => 'Quotation & Estimation',
            'ProductionJob'       => 'Production Management',
            'Payment'             => 'Payment & Transactions',
            'Machine'             => 'Equipment Management',
            'MachineLog'          => 'Equipment Management',
            'Branch'              => 'Branch Management',
            'BranchInventory'     => 'Inventory Management',
            'StockMovement'       => 'Inventory Management',
            'PurchaseRequest'     => 'Procurement',
            'User'                => 'User Management',
            'Employee'            => 'Staff Management',
            'DesignProof'         => 'Design & Layout',
            'ClaimReference'      => 'Order Fulfillment',
            'CapacityEvaluation'  => 'Capacity Management',
            'BranchRecommendation'=> 'Capacity Management',
            default               => 'System',
        };
    }
}
