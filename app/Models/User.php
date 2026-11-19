<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'custom_permissions',
        'last_login_at',
        'last_login_ip',
        'company_name',
        'phone',
        'address',
        'tax_number',
        'strn_number',
        'agpr_vendor_no',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'custom_permissions' => 'array',
            'last_login_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isAuditor(): bool
    {
        return $this->role === 'auditor';
    }

    public function isEvaluator(): bool
    {
        return $this->role === 'evaluator';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isActive(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Inactive users have zero permissions
        if (!$this->isActive()) {
            return false;
        }

        // Super Admin has all permissions automatically
        if ($this->isAdmin()) {
            return true;
        }

        // Custom permissions explicit grant / deny
        if (is_array($this->custom_permissions)) {
            if (in_array("!{$permission}", $this->custom_permissions, true)) {
                return false; // explicitly denied
            }
            if (in_array($permission, $this->custom_permissions, true)) {
                return true; // explicitly granted
            }
        }

        // Fall back to role permissions
        $rolePermissions = \App\Services\PermissionService::getPermissionsForRole($this->role ?? 'vendor');
        return in_array($permission, $rolePermissions, true);
    }

    /**
     * Check if user has one of the given roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return in_array($this->role, $roles, true);
    }

    /**
     * Get clean display name for role
     */
    public function getRoleDisplayName(): string
    {
        return \App\Services\PermissionService::ROLES[$this->role]['name'] ?? ucfirst($this->role ?? 'User');
    }

    /**
     * Get badge styling for role
     */
    public function getRoleBadge(): array
    {
        return \App\Services\PermissionService::ROLES[$this->role] ?? [
            'name' => ucfirst($this->role ?? 'User'),
            'badge' => 'bg-slate-100 text-slate-800 border-slate-200',
            'dot' => 'bg-slate-400',
            'description' => 'User account',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function tenders(): HasMany
    {
        return $this->hasMany(Tender::class, 'created_by');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'user_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'vendor_id');
    }
}
