<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <h2 class="font-extrabold text-2xl text-stone-900 tracking-tight">
                        {{ __('To-Do List & Procurement Tasks') }}
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-stone-500 mt-1">
                    Manage tender submissions, quotation follow-ups, invoice milestone deadlines, and AGPR cheque tracking.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" @click="$dispatch('open-modal', 'create-task-modal')" 
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ New Detailed Task</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="todoApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- 1. Stats Counter Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- All Tasks -->
                <a href="{{ route('todos.index', ['filter' => 'all']) }}" 
                   class="bg-white p-4 rounded-2xl border transition shadow-2xs hover:shadow-md {{ $filter === 'all' ? 'border-orange-500 ring-2 ring-orange-200' : 'border-[#e5ded4]' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-wider text-stone-400">Total Tasks</span>
                        <div class="w-7 h-7 rounded-lg bg-stone-100 text-stone-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-stone-900 mt-2">{{ $counts['all'] }}</div>
                    <div class="text-[11px] text-stone-500 mt-0.5">All registered tasks</div>
                </a>

                <!-- Pending -->
                <a href="{{ route('todos.index', ['filter' => 'pending']) }}" 
                   class="bg-white p-4 rounded-2xl border transition shadow-2xs hover:shadow-md {{ $filter === 'pending' ? 'border-amber-500 ring-2 ring-amber-200' : 'border-[#e5ded4]' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-wider text-amber-600">Pending</span>
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-700 mt-2">{{ $counts['pending'] }}</div>
                    <div class="text-[11px] text-amber-600 mt-0.5">Active to complete</div>
                </a>

                <!-- Due Today -->
                <a href="{{ route('todos.index', ['filter' => 'due_today']) }}" 
                   class="bg-white p-4 rounded-2xl border transition shadow-2xs hover:shadow-md {{ $filter === 'due_today' ? 'border-blue-500 ring-2 ring-blue-200' : 'border-[#e5ded4]' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-wider text-blue-600">Due Today</span>
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-blue-700 mt-2">{{ $counts['due_today'] }}</div>
                    <div class="text-[11px] text-blue-600 mt-0.5">Action required today</div>
                </a>

                <!-- Overdue -->
                <a href="{{ route('todos.index', ['filter' => 'overdue']) }}" 
                   class="bg-white p-4 rounded-2xl border transition shadow-2xs hover:shadow-md {{ $filter === 'overdue' ? 'border-rose-500 ring-2 ring-rose-200' : 'border-[#e5ded4]' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-wider text-rose-600">Overdue</span>
                        <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-rose-700 mt-2">{{ $counts['overdue'] }}</div>
                    <div class="text-[11px] text-rose-600 mt-0.5">Missed deadlines</div>
                </a>

                <!-- Completed -->
                <a href="{{ route('todos.index', ['filter' => 'completed']) }}" 
                   class="col-span-2 sm:col-span-1 bg-white p-4 rounded-2xl border transition shadow-2xs hover:shadow-md {{ $filter === 'completed' ? 'border-emerald-500 ring-2 ring-emerald-200' : 'border-[#e5ded4]' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-wider text-emerald-600">Completed</span>
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-700 mt-2">{{ $counts['completed'] }}</div>
                    <div class="text-[11px] text-emerald-600 mt-0.5">Archived achievements</div>
                </a>
            </div>

            <!-- 2. Fast Quick-Add Task Bar (Productivity Style) -->
            <div class="bg-white rounded-2xl border border-[#e5ded4] p-4 sm:p-5 shadow-sm">
                <form action="{{ route('todos.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Task Title Input -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <input type="text" name="title" required placeholder="What needs to be done? (e.g. Submit Quotation #2 for Health Dept tender, Collect AGPR token...)" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 font-medium transition" />
                        </div>

                        <!-- Category Selector -->
                        <div class="sm:w-44">
                            <select name="category" class="w-full py-2.5 px-3 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                                <option value="general">📋 General Task</option>
                                <option value="tender">📄 Tender Bid</option>
                                <option value="quotation">📑 Quotation Follow-up</option>
                                <option value="bill_invoice">🧾 Bill & Invoice</option>
                                <option value="cheque_agpr">🏦 Cheque & AGPR</option>
                            </select>
                        </div>

                        <!-- Priority Selector -->
                        <div class="sm:w-36">
                            <select name="priority" class="w-full py-2.5 px-3 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                                <option value="medium">⚡ Medium Priority</option>
                                <option value="urgent">🔥 Urgent</option>
                                <option value="high">🔺 High Priority</option>
                                <option value="low">🌱 Low Priority</option>
                            </select>
                        </div>

                        <!-- Due Date -->
                        <div class="sm:w-40">
                            <input type="date" name="due_date" value="{{ date('Y-m-d') }}" 
                                   class="w-full py-2.5 px-3 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200" />
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="px-5 py-2.5 bg-stone-900 hover:bg-orange-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition shrink-0 flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Task</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Filters & Tabs Bar -->
            <div class="bg-white rounded-2xl border border-[#e5ded4] p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['filter' => 'pending'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'pending' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'text-stone-600 hover:bg-stone-100' }}">
                        Pending ({{ $counts['pending'] }})
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['filter' => 'due_today'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'due_today' ? 'bg-blue-100 text-blue-900 border border-blue-300' : 'text-stone-600 hover:bg-stone-100' }}">
                        Due Today ({{ $counts['due_today'] }})
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['filter' => 'overdue'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'overdue' ? 'bg-rose-100 text-rose-900 border border-rose-300' : 'text-stone-600 hover:bg-stone-100' }}">
                        Overdue ({{ $counts['overdue'] }})
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['filter' => 'completed'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'completed' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'text-stone-600 hover:bg-stone-100' }}">
                        Completed ({{ $counts['completed'] }})
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['filter' => 'all'])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'all' ? 'bg-stone-200 text-stone-900 border border-stone-300' : 'text-stone-600 hover:bg-stone-100' }}">
                        All ({{ $counts['all'] }})
                    </a>
                </div>

                <!-- Search & Dropdown Filters Form -->
                <form action="{{ route('todos.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                    <input type="hidden" name="filter" value="{{ $filter }}">

                    <!-- Search Input -->
                    <div class="relative flex-1 sm:w-48">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search tasks..." 
                               class="w-full pl-8 pr-3 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-800 placeholder-stone-400 focus:bg-white focus:border-orange-500">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <!-- Category Dropdown -->
                    <select name="category" onchange="this.form.submit()" class="py-1.5 px-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-medium text-stone-700">
                        <option value="all" {{ empty($category) || $category === 'all' ? 'selected' : '' }}>All Categories</option>
                        <option value="tender" {{ $category === 'tender' ? 'selected' : '' }}>Tender Bid</option>
                        <option value="quotation" {{ $category === 'quotation' ? 'selected' : '' }}>Quotation</option>
                        <option value="bill_invoice" {{ $category === 'bill_invoice' ? 'selected' : '' }}>Bill & Invoice</option>
                        <option value="cheque_agpr" {{ $category === 'cheque_agpr' ? 'selected' : '' }}>Cheque & AGPR</option>
                        <option value="general" {{ $category === 'general' ? 'selected' : '' }}>General</option>
                    </select>

                    <!-- Priority Dropdown -->
                    <select name="priority" onchange="this.form.submit()" class="py-1.5 px-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-medium text-stone-700">
                        <option value="all" {{ empty($priority) || $priority === 'all' ? 'selected' : '' }}>All Priorities</option>
                        <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                    </select>

                    @if($search || ($category && $category !== 'all') || ($priority && $priority !== 'all'))
                        <a href="{{ route('todos.index', ['filter' => $filter]) }}" class="px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- 4. Task Items List -->
            <div class="bg-white rounded-2xl border border-[#e5ded4] shadow-sm divide-y divide-[#f4efe6] overflow-hidden">
                @forelse($todos as $todo)
                    <div class="p-4 sm:p-5 flex items-start gap-3.5 transition group hover:bg-[#fcfaf7] {{ $todo->is_completed ? 'bg-stone-50/70' : '' }}"
                         id="todo-row-{{ $todo->id }}">
                        
                        <!-- Toggle Checkbox -->
                        <div class="pt-0.5 shrink-0">
                            <button type="button" 
                                    @click="toggleTodo({{ $todo->id }})" 
                                    class="w-6 h-6 rounded-lg border flex items-center justify-center transition-all duration-150 {{ $todo->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-stone-300 hover:border-orange-500 bg-white text-transparent' }}"
                                    title="{{ $todo->is_completed ? 'Click to mark pending' : 'Click to mark completed' }}">
                                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Title -->
                                <h3 class="text-sm font-bold leading-tight transition {{ $todo->is_completed ? 'line-through text-stone-400' : 'text-stone-900' }}"
                                    id="todo-title-{{ $todo->id }}">
                                    {{ $todo->title }}
                                </h3>

                                <!-- Priority Badge -->
                                @if($todo->priority === 'urgent')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                        Urgent
                                    </span>
                                @elseif($todo->priority === 'high')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-orange-800 border border-orange-200">
                                        High
                                    </span>
                                @elseif($todo->priority === 'medium')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                        Medium
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium uppercase tracking-wider bg-stone-100 text-stone-600 border border-stone-200">
                                        Low
                                    </span>
                                @endif

                                <!-- Category Badge -->
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-stone-100 text-stone-700 border border-stone-200">
                                    <span>{{ $todo->category_label }}</span>
                                </span>

                                <!-- Linked Context Badges (Tender, Bill, Company) -->
                                @if($todo->tender)
                                    <a href="{{ route('tenders.show', $todo->tender_id) }}" 
                                       class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition"
                                       title="Linked Tender: {{ $todo->tender->title }}">
                                        <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="truncate max-w-[140px]">{{ $todo->tender->reference_no ?: $todo->tender->title }}</span>
                                    </a>
                                @endif

                                @if($todo->bill)
                                    <a href="{{ route('bills.show', $todo->bill_id) }}" 
                                       class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition"
                                       title="Linked Bill: {{ $todo->bill->bill_number }}">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Bill #{{ $todo->bill->bill_number }}</span>
                                    </a>
                                @endif

                                @if($todo->company)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="truncate max-w-[120px]">{{ $todo->company->company_name }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Description if provided -->
                            @if($todo->description)
                                <p class="text-xs text-stone-600 mt-1.5 leading-relaxed {{ $todo->is_completed ? 'line-through text-stone-400' : '' }}">
                                    {{ $todo->description }}
                                </p>
                            @endif

                            <!-- Meta Footer (Due Date, Completion Info, Author) -->
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-stone-400">
                                <!-- Due Date Indicator -->
                                @if($todo->due_date)
                                    <div class="flex items-center gap-1 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @if($todo->is_completed)
                                            <span>Was due {{ $todo->due_date->format('M d, Y') }}</span>
                                        @elseif($todo->is_overdue)
                                            <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                                Overdue: {{ $todo->due_date->format('M d, Y') }} ({{ $todo->due_date->diffForHumans() }})
                                            </span>
                                        @elseif($todo->is_due_today)
                                            <span class="text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200">
                                                Due Today ({{ $todo->due_date->format('M d') }})
                                            </span>
                                        @else
                                            <span class="text-stone-600">Due {{ $todo->due_date->format('M d, Y') }} ({{ $todo->due_date->diffForHumans() }})</span>
                                        @endif
                                    </div>
                                @endif

                                @if($todo->is_completed && $todo->completed_at)
                                    <div class="flex items-center gap-1 text-emerald-600 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Completed {{ $todo->completed_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                @endif

                                @if($todo->creator)
                                    <div class="flex items-center gap-1">
                                        <span>Added by {{ $todo->creator->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Controls (Edit & Delete) -->
                        <div class="shrink-0 flex items-center gap-1 opacity-80 group-hover:opacity-100 transition">
                            <button type="button" 
                                    @click="openEditModal({{ json_encode($todo) }})" 
                                    class="p-1.5 text-stone-400 hover:text-stone-700 hover:bg-stone-200/60 rounded-lg transition"
                                    title="Edit Task Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" onsubmit="return confirm('Delete this task permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                        title="Delete Task">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-stone-900">No tasks found</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                            @if($filter === 'overdue')
                                Great job! You have zero overdue tasks.
                            @elseif($filter === 'completed')
                                No completed tasks in this view yet.
                            @else
                                All clear! Add a new task above to stay on top of tender deadlines and quotations.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($todos->hasPages())
                <div class="pt-2">
                    {{ $todos->links() }}
                </div>
            @endif

        </div>

        <!-- 5. Create Detailed Task Modal -->
        <x-modal name="create-task-modal" :show="false" maxWidth="2xl">
            <form action="{{ route('todos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="font-extrabold text-lg text-stone-900">Create New Procurement Task</h3>
                    </div>
                    <button type="button" @click="$dispatch('close-modal', 'create-task-modal')" class="text-stone-400 hover:text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Task Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Prepare comparative statement for Health Dept tender" 
                           class="w-full px-3.5 py-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-sm text-stone-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Description / Notes</label>
                    <textarea name="description" rows="3" placeholder="Add any specific instructions, CDR amounts, contact persons, or notes..." 
                              class="w-full px-3.5 py-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-sm text-stone-900 focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Category *</label>
                        <select name="category" required class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                            <option value="tender">📄 Tender Bid</option>
                            <option value="quotation">📑 Quotation Follow-up</option>
                            <option value="bill_invoice">🧾 Bill & Invoice</option>
                            <option value="cheque_agpr">🏦 Cheque & AGPR</option>
                            <option value="general" selected>📋 General Task</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Priority *</label>
                        <select name="priority" required class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                            <option value="low">🌱 Low</option>
                            <option value="medium" selected>⚡ Medium</option>
                            <option value="high">🔺 High</option>
                            <option value="urgent">🔥 Urgent</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Due Date</label>
                        <input type="date" name="due_date" value="{{ date('Y-m-d') }}" 
                               class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                    </div>
                </div>

                <!-- Optional Links to Tender, Bill, or Company -->
                <div class="pt-2 border-t border-stone-200 space-y-3">
                    <div class="text-[11px] font-black uppercase tracking-wider text-stone-400">Optional: Link to Existing Record</div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Tender</label>
                            <select name="tender_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($tenders as $tender)
                                    <option value="{{ $tender->id }}">{{ $tender->reference_no ?: $tender->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Bill / Invoice</label>
                            <select name="bill_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}">Bill #{{ $bill->bill_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Company</label>
                            <select name="company_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-stone-200">
                    <button type="button" @click="$dispatch('close-modal', 'create-task-modal')" class="px-4 py-2 text-xs font-bold text-stone-600 hover:text-stone-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Create Task
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- 6. Edit Task Modal -->
        <x-modal name="edit-task-modal" :show="false" maxWidth="2xl">
            <form :action="'/todos/' + (activeTodo ? activeTodo.id : '')" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <h3 class="font-extrabold text-lg text-stone-900">Edit Task</h3>
                    </div>
                    <button type="button" @click="$dispatch('close-modal', 'edit-task-modal')" class="text-stone-400 hover:text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Task Title *</label>
                    <input type="text" name="title" x-model="activeTodo.title" required 
                           class="w-full px-3.5 py-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-sm text-stone-900 focus:bg-white focus:border-orange-500">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Description / Notes</label>
                    <textarea name="description" x-model="activeTodo.description" rows="3" 
                              class="w-full px-3.5 py-2.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-sm text-stone-900 focus:bg-white focus:border-orange-500"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Category *</label>
                        <select name="category" x-model="activeTodo.category" required class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                            <option value="tender">📄 Tender Bid</option>
                            <option value="quotation">📑 Quotation Follow-up</option>
                            <option value="bill_invoice">🧾 Bill & Invoice</option>
                            <option value="cheque_agpr">🏦 Cheque & AGPR</option>
                            <option value="general">📋 General Task</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Priority *</label>
                        <select name="priority" x-model="activeTodo.priority" required class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                            <option value="low">🌱 Low</option>
                            <option value="medium">⚡ Medium</option>
                            <option value="high">🔺 High</option>
                            <option value="urgent">🔥 Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Due Date</label>
                        <input type="date" name="due_date" x-model="activeTodo.due_date" 
                               class="w-full px-3 py-2 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs font-semibold text-stone-800">
                    </div>
                </div>

                <!-- Links -->
                <div class="pt-2 border-t border-stone-200 space-y-3">
                    <div class="text-[11px] font-black uppercase tracking-wider text-stone-400">Linked Record</div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Tender</label>
                            <select name="tender_id" x-model="activeTodo.tender_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($tenders as $tender)
                                    <option value="{{ $tender->id }}">{{ $tender->reference_no ?: $tender->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Bill / Invoice</label>
                            <select name="bill_id" x-model="activeTodo.bill_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}">Bill #{{ $bill->bill_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-stone-600 mb-1">Company</label>
                            <select name="company_id" x-model="activeTodo.company_id" class="w-full px-2.5 py-1.5 bg-[#fcfaf7] border border-[#e5ded4] rounded-xl text-xs text-stone-700">
                                <option value="">-- None --</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-stone-200">
                    <button type="button" @click="$dispatch('close-modal', 'edit-task-modal')" class="px-4 py-2 text-xs font-bold text-stone-600 hover:text-stone-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <!-- Alpine Script for Live Checkbox Toggle -->
    <script>
        function todoApp() {
            return {
                activeTodo: {},
                openEditModal(todo) {
                    this.activeTodo = Object.assign({}, todo);
                    // Format due_date for HTML date input (YYYY-MM-DD)
                    if (this.activeTodo.due_date) {
                        this.activeTodo.due_date = this.activeTodo.due_date.substring(0, 10);
                    }
                    this.$dispatch('open-modal', 'edit-task-modal');
                },
                toggleTodo(todoId) {
                    const row = document.getElementById('todo-row-' + todoId);
                    const title = document.getElementById('todo-title-' + todoId);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    fetch(`/todos/${todoId}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page smoothly to reflect tabs and re-sorting or update classes
                            window.location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Error toggling task:', err);
                        window.location.reload();
                    });
                }
            };
        }
    </script>
</x-app-layout>
