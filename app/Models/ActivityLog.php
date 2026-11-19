<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'subject_id',
        'subject_type',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper to easily record a user action anywhere in the system
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        mixed $subject = null,
        ?array $properties = null,
        ?User $user = null
    ): ?self {
        try {
            $currentUser = $user ?: Auth::user();
            
            $subjectId = null;
            $subjectType = null;
            if ($subject && is_object($subject)) {
                $subjectType = get_class($subject);
                if (isset($subject->id)) {
                    $subjectId = $subject->id;
                }
            }

            return self::create([
                'user_id' => $currentUser?->id,
                'user_name' => $currentUser?->name ?: 'System / Guest',
                'user_role' => $currentUser?->role ?: 'system',
                'action' => $action,
                'module' => $module,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
                'description' => $description,
                'properties' => $properties,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Silently fail so activity logging never crashes a business transaction
            logger()->error('Failed to record activity log: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * UI Badge helper for Action type
     */
    public function getActionBadge(): array
    {
        return match (strtolower($this->action)) {
            'created', 'create' => [
                'label' => 'Created',
                'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'dot' => 'bg-emerald-500',
                'icon' => 'plus',
            ],
            'updated', 'update' => [
                'label' => 'Updated',
                'class' => 'bg-blue-50 text-blue-800 border-blue-200',
                'dot' => 'bg-blue-500',
                'icon' => 'pencil',
            ],
            'deleted', 'delete' => [
                'label' => 'Deleted',
                'class' => 'bg-rose-50 text-rose-800 border-rose-200',
                'dot' => 'bg-rose-500',
                'icon' => 'trash',
            ],
            'status_changed', 'status' => [
                'label' => 'Status Changed',
                'class' => 'bg-amber-50 text-amber-900 border-amber-200',
                'dot' => 'bg-amber-500',
                'icon' => 'refresh',
            ],
            'login' => [
                'label' => 'Logged In',
                'class' => 'bg-purple-50 text-purple-800 border-purple-200',
                'dot' => 'bg-purple-500',
                'icon' => 'login',
            ],
            'logout' => [
                'label' => 'Logged Out',
                'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                'dot' => 'bg-slate-400',
                'icon' => 'logout',
            ],
            'imported', 'import' => [
                'label' => 'Imported',
                'class' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                'dot' => 'bg-cyan-500',
                'icon' => 'upload',
            ],
            default => [
                'label' => ucfirst($this->action),
                'class' => 'bg-slate-50 text-slate-700 border-slate-200',
                'dot' => 'bg-slate-400',
                'icon' => 'check',
            ],
        };
    }

    /**
     * UI Badge helper for Module
     */
    public function getModuleBadge(): array
    {
        return match (strtolower($this->module)) {
            'bills', 'bill' => [
                'label' => 'Bills & Invoices',
                'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'icon' => 'document-text',
            ],
            'cheques', 'cheque' => [
                'label' => 'Cheques & AGPR',
                'class' => 'bg-amber-50 text-amber-800 border-amber-200',
                'icon' => 'credit-card',
            ],
            'departments', 'department' => [
                'label' => 'Departments',
                'class' => 'bg-teal-50 text-teal-800 border-teal-200',
                'icon' => 'building-office',
            ],
            'companies', 'company' => [
                'label' => 'Companies & Letters',
                'class' => 'bg-blue-50 text-blue-800 border-blue-200',
                'icon' => 'building-office-2',
            ],
            'products', 'product' => [
                'label' => 'Products Catalog',
                'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'icon' => 'cube',
            ],
            'users', 'user' => [
                'label' => 'User Management',
                'class' => 'bg-rose-50 text-rose-800 border-rose-200',
                'icon' => 'users',
            ],
            'auth' => [
                'label' => 'Authentication',
                'class' => 'bg-purple-50 text-purple-800 border-purple-200',
                'icon' => 'shield-check',
            ],
            default => [
                'label' => ucfirst($this->module),
                'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                'icon' => 'folder',
            ],
        };
    }
}
