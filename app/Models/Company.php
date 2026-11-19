<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'vendor_no',
        'ntn',
        'gst',
        'sst',
        'invoice_template',
        'letterhead_type',
        'header_image',
        'footer_image',
        'full_letterhead_image',
        'header_height',
        'footer_height',
        'body_top_margin',
        'body_bottom_margin',
        'body_side_margin',
        'show_on_print',
        'default_subject',
        'default_terms',
        'signatory_title',
        'body_style',
        'show_unit_column',
        'show_sr_column',
        'total_layout',
        'address',
        'contact_number',
        'email',
        'status',
        'is_default',
        'created_by',
    ];

    /**
     * Accessor so $company->name works seamlessly alongside $company->company_name
     */
    public function getNameAttribute(): string
    {
        return (string) ($this->attributes['company_name'] ?? '');
    }

    public static function templateOptions(): array
    {
        return [
            'navy_wave' => [
                'name' => 'Executive Navy Wave',
                'color' => '#1e3a8a',
                'badge' => 'Navy Blue',
                'desc' => 'Curved deep navy wave header with modern clean tables',
            ],
            'classic_mono' => [
                'name' => 'Corporate Monochrome',
                'color' => '#0f172a',
                'badge' => 'Minimal B&W',
                'desc' => 'Crisp black & white typography with minimalist rule lines',
            ],
            'vibrant_orange' => [
                'name' => 'Modern Terracotta & Orange',
                'color' => '#ea580c',
                'badge' => 'Vibrant Orange',
                'desc' => 'Modern orange geometric accent shapes and bold total highlights',
            ],
            'crimson_rose' => [
                'name' => 'Crimson Rose & Stripe',
                'color' => '#be123c',
                'badge' => 'Crimson Red',
                'desc' => 'Alternating rose line items, crimson headers, and elegant cards',
            ],
            'geometric_amber' => [
                'name' => 'Geometric Circle & Amber',
                'color' => '#d97706',
                'badge' => 'Amber Gold',
                'desc' => 'Modern circular header graphics with warm gold total badge',
            ],
            'lime_navy' => [
                'name' => 'Lime Green & Navy Diagonal',
                'color' => '#65a30d',
                'badge' => 'Lime & Navy',
                'desc' => 'Dynamic diagonal corporate ribbon header with lime green accents',
            ],
            'charcoal_wave' => [
                'name' => 'Minimal Charcoal Wave',
                'color' => '#334155',
                'badge' => 'Charcoal Wave',
                'desc' => 'Clean bold typography with dual-tone curved wave at the bottom',
            ],
        ];
    }

    public static function bodyStyleOptions(): array
    {
        return [
            'corporate_navy' => [
                'name' => 'Corporate Navy Elegance',
                'badge' => 'Navy Accents',
                'color' => '#1e3a8a',
                'desc' => 'Solid navy table header, clean soft zebra alternating rows, modern boxed total card.',
            ],
            'classic_mono' => [
                'name' => 'Monochrome Minimalist (Legal / Govt)',
                'badge' => 'Classic B&W',
                'color' => '#0f172a',
                'desc' => 'Double black horizontal rule lines, boxed thin grid, legal double-underline grand total.',
            ],
            'modern_slate' => [
                'name' => 'Rounded Slate Tech',
                'badge' => 'Slate Tech',
                'color' => '#475569',
                'desc' => 'Soft slate rounded header, pill borders, modern floating grand total card.',
            ],
            'warm_crimson' => [
                'name' => 'Crimson & Terracotta',
                'badge' => 'Crimson Bar',
                'color' => '#9e1c24',
                'desc' => 'Rich crimson header bar, soft rose tinted alternating rows, solid colored total summary.',
            ],
            'forest_green' => [
                'name' => 'Forest Green Contractor',
                'badge' => 'Forest Green',
                'color' => '#065f46',
                'desc' => 'Industrial dark green header, thin green divider borders, highlighted green grand total.',
            ],
            'ledger_grid' => [
                'name' => 'Traditional Accounting Ledger',
                'badge' => 'Ledger Boxed',
                'color' => '#334155',
                'desc' => 'Boxed accounting table with strong outer borders and classic 3-line ledger total box.',
            ],
            'minimal_dotted' => [
                'name' => 'Minimalist Dotted Lines',
                'badge' => 'Dotted Minimal',
                'color' => '#64748b',
                'desc' => 'Understated clean typography with dotted horizontal dividing lines and clean summary.',
            ],
        ];
    }

    public static function totalLayoutOptions(): array
    {
        return [
            'auto' => [
                'name' => 'Theme Default (Natural)',
                'badge' => 'Default',
                'desc' => 'Har theme apne mutabiq natural format use karegi (e.g. Ledger tfoot use karega, Navy card use karega).',
            ],
            'card_box' => [
                'name' => 'Right Floating Card Box',
                'badge' => 'Card Box',
                'desc' => 'Right side per modern floating card summary with rounded borders aur badges.',
            ],
            'in_table_footer' => [
                'name' => 'In-Table Accounting Rows (tfoot)',
                'badge' => 'In Table',
                'desc' => 'Subtotal, Tax aur Grand Total direct items table k neeche wali rows mein columns k sath span hongay.',
            ],
            'split_banner' => [
                'name' => 'Split Two-Column Footer',
                'badge' => 'Two-Col',
                'desc' => 'Left side per payment terms aur amount in words, right side per Subtotal aur Grand Total.',
            ],
            'wide_bar' => [
                'name' => 'Full-Width Highlight Strip',
                'badge' => 'Full Strip',
                'desc' => 'Puri sheet ki width per 3-column horizontal strip (Subtotal | GST 18% | Grand Total).',
            ],
        ];
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'show_on_print' => 'boolean',
            'show_unit_column' => 'boolean',
            'show_sr_column' => 'boolean',
            'header_height' => 'integer',
            'footer_height' => 'integer',
            'body_top_margin' => 'integer',
            'body_bottom_margin' => 'integer',
            'body_side_margin' => 'integer',
        ];
    }

    public function getHeaderImageUrlAttribute(): ?string
    {
        return $this->header_image ? asset('storage/' . $this->header_image) : null;
    }

    public function getFooterImageUrlAttribute(): ?string
    {
        return $this->footer_image ? asset('storage/' . $this->footer_image) : null;
    }

    public function getFullLetterheadImageUrlAttribute(): ?string
    {
        return $this->full_letterhead_image ? asset('storage/' . $this->full_letterhead_image) : null;
    }

    public function hasCustomLetterhead(): bool
    {
        if ($this->letterhead_type === 'full_page') {
            return !empty($this->full_letterhead_image);
        }
        if ($this->letterhead_type === 'header_footer') {
            return !empty($this->header_image) || !empty($this->footer_image);
        }
        return false;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
