<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('finance.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">&larr; Finance Overview</a>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Treasury & Vaults</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Bank Accounts & Cash in Hand') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Corporate bank accounts, Islamic accounts, cash drawers and security deposit vaults
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('finance.cashbook') }}" class="px-3.5 py-2 text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Cashbook Ledger</span>
                </a>

                <button type="button" onclick="document.getElementById('addBankModal').classList.remove('hidden')" class="px-4 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add Bank Account</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-bold flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- 1. Top Balances Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Treasury Balance</span>
                    <div class="text-2xl font-black text-slate-900 font-mono mt-1">
                        PKR {{ number_format($totalBalance, 2) }}
                    </div>
                    <div class="text-[11px] text-slate-500 mt-1">Total liquid across all vaults & banks</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Commercial Bank Accounts</span>
                    <div class="text-2xl font-black text-indigo-700 font-mono mt-1">
                        PKR {{ number_format($bankBalance, 2) }}
                    </div>
                    <div class="text-[11px] text-indigo-600 mt-1">Active current & savings bank accounts</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Petty Cash in Hand</span>
                    <div class="text-2xl font-black text-emerald-700 font-mono mt-1">
                        PKR {{ number_format($cashBalance, 2) }}
                    </div>
                    <div class="text-[11px] text-emerald-600 mt-1">Office cash registers & daily cash</div>
                </div>
            </div>

            <!-- 2. Accounts List Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">All Accounts & Cash Drawers</h3>
                    <span class="text-xs font-bold text-slate-500 font-mono">{{ $accounts->count() }} Accounts Configured</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200 text-[10px]">
                                <th class="p-3 text-center">#</th>
                                <th class="p-3">Bank / Account Name</th>
                                <th class="p-3">Account Title</th>
                                <th class="p-3 font-mono">Account / IBAN Number</th>
                                <th class="p-3">Type</th>
                                <th class="p-3 text-right">Available Balance</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-sans">
                            @forelse($accounts as $index => $acc)
                                @php $badge = $acc->getAccountTypeBadge(); @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <div class="font-extrabold text-sm text-slate-900">{{ $acc->bank_name }}</div>
                                            @if($acc->is_default)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-indigo-100 text-indigo-700">Default</span>
                                            @endif
                                        </div>
                                        @if($acc->branch_name)
                                            <div class="text-[10px] text-slate-400">Branch: {{ $acc->branch_name }} {{ $acc->branch_code ? "({$acc->branch_code})" : '' }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 font-semibold text-slate-800">
                                        {{ $acc->account_title }}
                                    </td>
                                    <td class="p-3 font-mono text-slate-700 whitespace-nowrap">
                                        <div class="font-bold">{{ $acc->account_number }}</div>
                                        @if($acc->iban)
                                            <div class="text-[10px] text-slate-400">{{ $acc->iban }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $badge['class'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right font-mono font-black text-sm whitespace-nowrap {{ $acc->current_balance < 0 ? 'text-rose-600' : 'text-slate-900' }}">
                                        PKR {{ number_format($acc->current_balance, 2) }}
                                        <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $acc->transactions_count }} txns</div>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $acc->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                            {{ $acc->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('banks.statement', $acc) }}" class="px-2.5 py-1 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-200">
                                                Ledger &rarr;
                                            </a>
                                            
                                            <button type="button" 
                                                    onclick="openEditBankModal({{ json_encode($acc) }})"
                                                    class="p-1 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition" 
                                                    title="Edit Account">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>

                                            @if($acc->transactions_count <= 1)
                                                <form method="POST" action="{{ route('banks.destroy', $acc) }}" onsubmit="return confirm('Delete this bank account?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-6 text-center text-slate-400">
                                        No bank accounts found. Click "+ Add Bank Account" above.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL 1: ADD BANK ACCOUNT -->
    <div id="addBankModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center font-bold">
                        🏛️
                    </div>
                    <div>
                        <h3 class="font-black text-sm text-slate-900 uppercase">Add Bank Account / Cash Vault</h3>
                        <p class="text-[11px] text-slate-500">Add corporate account, Islamic bank or petty cash</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('addBankModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('banks.store') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bank Name / Cash Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="bank_name" required placeholder="e.g. Meezan Bank, HBL, Cash in Hand" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="account_title" required placeholder="e.g. Three Stars Medical / ProWave Technologies" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_number" required placeholder="e.g. 0102-0100456789" class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">IBAN (Optional)</label>
                        <input type="text" name="iban" placeholder="PK65MEZN000..." class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Branch Name</label>
                        <input type="text" name="branch_name" placeholder="e.g. Main Clifton Karachi" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Branch Code</label>
                        <input type="text" name="branch_code" placeholder="e.g. 0102" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Type <span class="text-rose-500">*</span></label>
                        <select name="account_type" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="current">Current Business Account</option>
                            <option value="petty_cash">Cash in Hand (Petty Cash)</option>
                            <option value="savings">Savings Account</option>
                            <option value="escrow">Escrow / Bid Security</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Opening Balance (PKR) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" name="opening_balance" value="0.00" required class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-slate-700">Set as Primary / Default Account</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes / Remarks</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Used for Sindh Health Department procurement tenders" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addBankModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-xs">
                        Save Bank Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT BANK ACCOUNT -->
    <div id="editBankModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-black text-sm text-slate-900 uppercase">Edit Bank Account</h3>
                <button type="button" onclick="document.getElementById('editBankModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form id="editBankForm" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bank Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_bank_name" name="bank_name" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Title <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_account_title" name="account_title" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_account_number" name="account_number" required class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">IBAN</label>
                        <input type="text" id="edit_iban" name="iban" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Branch Name</label>
                        <input type="text" id="edit_branch_name" name="branch_name" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Branch Code</label>
                        <input type="text" id="edit_branch_code" name="branch_code" class="w-full text-xs font-mono font-semibold rounded-lg border-slate-300 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Type <span class="text-rose-500">*</span></label>
                        <select id="edit_account_type" name="account_type" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                            <option value="current">Current Business Account</option>
                            <option value="petty_cash">Cash in Hand (Petty Cash)</option>
                            <option value="savings">Savings Account</option>
                            <option value="escrow">Escrow / Bid Security</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status <span class="text-rose-500">*</span></label>
                        <select id="edit_status" name="status" required class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edit_is_default" name="is_default" value="1" class="text-indigo-600 rounded border-slate-300">
                        <span class="text-xs font-bold text-slate-700">Set as Primary / Default Account</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes / Remarks</label>
                    <textarea id="edit_notes" name="notes" rows="2" class="w-full text-xs font-semibold rounded-lg border-slate-300 py-2"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('editBankModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-xs">
                        Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditBankModal(acc) {
            document.getElementById('editBankForm').action = '/banks/' + acc.id;
            document.getElementById('edit_bank_name').value = acc.bank_name || '';
            document.getElementById('edit_account_title').value = acc.account_title || '';
            document.getElementById('edit_account_number').value = acc.account_number || '';
            document.getElementById('edit_iban').value = acc.iban || '';
            document.getElementById('edit_branch_name').value = acc.branch_name || '';
            document.getElementById('edit_branch_code').value = acc.branch_code || '';
            document.getElementById('edit_account_type').value = acc.account_type || 'current';
            document.getElementById('edit_status').value = acc.status || 'active';
            document.getElementById('edit_notes').value = acc.notes || '';
            document.getElementById('edit_is_default').checked = !!acc.is_default;
            document.getElementById('editBankModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
