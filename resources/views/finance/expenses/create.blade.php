<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Expenses Registry</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Payment Disbursement</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Record New Expense Voucher') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Generate payment voucher, deduct from bank/cash, and attach transaction receipt
                </p>
            </div>

            <a href="{{ route('expenses.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- CARD 1: Voucher & Category Information -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Voucher Classification</h3>
                            <p class="text-[11px] text-slate-400">Voucher identification number, date and expense classification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Voucher No -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Voucher No <span class="text-rose-500">*</span></label>
                            <input type="text" name="voucher_no" value="{{ old('voucher_no', $suggestedVoucherNo) }}" required class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('voucher_no')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Expense Date -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('expense_date')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Category <span class="text-rose-500">*</span></label>
                            <select name="expense_category_id" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Title / Reason <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. PTCL Internet & Landline Bill for October" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Payee Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Paid To / Payee Name</label>
                            <input type="text" name="payee_name" value="{{ old('payee_name') }}" placeholder="e.g. PTCL Karachi, Landlord, Shell Station, Driver" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('payee_name')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Financial Details & Payment Method -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Payment Details & Bank Account</h3>
                            <p class="text-[11px] text-slate-400">Specify payment amount, source account to deduct from, and cheque reference</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Amount -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amount (PKR) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" placeholder="e.g. 15000" required class="w-full text-xs font-mono font-black text-rose-600 rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('amount')<span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Method <span class="text-rose-500">*</span></label>
                            <select name="payment_method" id="payment_method" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer / Online</option>
                                <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Status <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="paid" {{ old('status', 'paid') === 'paid' ? 'selected' : '' }}>Paid (Deduct from Account Now)</option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Approval / Not Yet Cleared</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Paid From Bank Account -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deduct From Account</label>
                            <select name="bank_account_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select account to deduct...</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}" {{ old('bank_account_id', $ba->is_default ? $ba->id : '') == $ba->id ? 'selected' : '' }}>
                                        {{ $ba->bank_name }} ({{ $ba->account_number }}) - Available: PKR {{ number_format($ba->current_balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-[10.5px] text-slate-400 mt-1 block">Account balance will be auto-debited when status is 'Paid'</span>
                        </div>

                        <!-- Cheque # -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cheque # (If Paid by Cheque)</label>
                            <input type="text" name="cheque_no" value="{{ old('cheque_no') }}" placeholder="e.g. 150029384" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Context, Department/Company & Receipt Attachment -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-purple-50 text-purple-700 font-bold text-xs flex items-center justify-center">3</span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Optional Context & Receipt Attachment</h3>
                            <p class="text-[11px] text-slate-400">Tag expense to a specific tender department or supplier company and attach receipt</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Company -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Associated Company (Optional)</label>
                            <select name="company_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">None / Corporate Head Office</option>
                                @foreach($companies as $cmp)
                                    <option value="{{ $cmp->id }}" {{ old('company_id') == $cmp->id ? 'selected' : '' }}>{{ $cmp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Associated Department (Optional)</label>
                            <select name="department_id" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">None / General Operations</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Receipt File Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Attach Receipt / Bill / Invoice Slip (Max 5MB)</label>
                        <input type="file" name="receipt" accept="image/*,.pdf" class="w-full text-xs rounded-lg border border-slate-300 p-2 bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>

                    <!-- Description / Notes -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description / Particulars / Remarks</label>
                        <textarea name="description" rows="3" placeholder="Enter any extra details, invoice breakdown, or milestone reference..." class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Cancel
                    </a>

                    <button type="submit" class="px-6 py-2.5 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Record Expense & Deduct</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
