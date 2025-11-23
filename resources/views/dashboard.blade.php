<x-layouts.app :title="__('Dashboard')">
	@php
		$initiativesCount = 0;
		$categoriesCount = 0;
		$totalImpact = 0;

		if (isset($initiatives)) {
			$initiativesCount = is_countable($initiatives) ? count($initiatives) : (method_exists($initiatives, 'count') ? $initiatives->count() : 0);
			foreach ($initiatives as $it) {
				$totalImpact += $it->impact_score ?? $it['impact_score'] ?? 0;
			}
		}

		if (isset($categories)) {
			$categoriesCount = is_countable($categories) ? count($categories) : (method_exists($categories, 'count') ? $categories->count() : 0);
		}
	@endphp

	<div class="min-h-screen bg-gray-50">
		<div class="bg-white border-b border-gray-200">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
				<h1 class="text-2xl font-semibold text-gray-900">Initiatives Dashboard</h1>
				<p class="text-sm text-gray-500 mt-1">Track and manage campus sustainability initiatives.</p>
			</div>
		</div>

		<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
			<!-- Success Message -->
			@if (session('success'))
				<div id="flash-success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center gap-3">
						<div class="flex items-start sm:items-center gap-3">
							<svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
							<div class="text-sm leading-tight break-words">{{ session('success') }}</div>
						</div>

						<!-- Dismiss button: on small screens it appears under the message, on larger screens aligned right -->
						<div class="sm:ml-auto flex-shrink-0">
							<button type="button" onclick="document.getElementById('flash-success').style.display='none'" aria-label="Dismiss success message" class="inline-flex items-center justify-center px-3 py-1.5 rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 text-sm">
								Dismiss
							</button>
						</div>
					</div>
				</div>
			@endif

			<!-- Stats -->
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
				<div class="bg-white rounded-lg border p-4">
					<div class="text-xs text-gray-500">Total Initiatives</div>
					<div class="mt-2 text-2xl font-bold text-gray-900">{{ $initiativesCount }}</div>
				</div>

				<div class="bg-white rounded-lg border p-4">
					<div class="text-xs text-gray-500">Total Categories</div>
					<div class="mt-2 text-2xl font-bold text-gray-900">{{ $categoriesCount }}</div>
				</div>

				<div class="bg-white rounded-lg border p-4">
					<div class="text-xs text-gray-500">Total Impact Score</div>
					<div class="mt-2 text-2xl font-bold text-gray-900">{{ $totalImpact }}</div>
				</div>
			</div>

			<!-- Controls -->
			<div class="flex items-center justify-between">
				<div class="text-sm text-gray-600">
					{{ $initiativesCount }} {{ $initiativesCount === 1 ? 'initiative' : 'initiatives' }} tracked
				</div>
				<div>
					<button id="open-modal-btn" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-emerald-600 text-white">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
						Add Initiative
					</button>
				</div>
			</div>
 
			<!-- Responsive: Mobile cards (sm), Desktop/tablet table (md+) -->
			<!-- Table for md+ screens -->
			<div class="hidden md:block bg-white rounded-lg border overflow-x-auto">
				<table class="min-w-full text-sm divide-y divide-gray-100">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-4 py-3 text-left font-medium text-gray-600">Title</th>
							<th class="px-4 py-3 text-left font-medium text-gray-600">Category</th>
							<th class="px-4 py-3 text-left font-medium text-gray-600">Start</th>
							<th class="px-4 py-3 text-left font-medium text-gray-600">End</th>
							<th class="px-4 py-3 text-left font-medium text-gray-600">Impact</th>
							<th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-100">
						@forelse ($initiatives ?? [] as $initiative)
							<tr>
								<td class="px-4 py-3">
									<div class="font-medium text-gray-900">{{ $initiative->title ?? $initiative['title'] ?? '-' }}</div>
									<div class="text-xs text-gray-500">{{ Str::limit($initiative->description ?? $initiative['description'] ?? '', 80) }}</div>
								</td>
								<td class="px-4 py-3 text-gray-700">{{ optional($initiative->category)->name ?? $initiative['category_name'] ?? '-' }}</td>
								<td class="px-4 py-3 text-gray-700">{{ $initiative->start_date ?? $initiative['start_date'] ?? '-' }}</td>
								<td class="px-4 py-3 text-gray-700">{{ $initiative->end_date ?? $initiative['end_date'] ?? '-' }}</td>
								<td class="px-4 py-3 text-gray-900 font-semibold">{{ $initiative->impact_score ?? $initiative['impact_score'] ?? 0 }}</td>
								<td class="px-4 py-3">
									<div class="flex items-center gap-2">
										<button class="edit-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-emerald-50 text-emerald-700 text-sm" data-id="{{ $initiative->id ?? $initiative['id'] ?? '' }}" data-title="{{ e($initiative->title ?? $initiative['title'] ?? '') }}" data-description="{{ e($initiative->description ?? $initiative['description'] ?? '') }}" data-category="{{ $initiative->category_id ?? $initiative['category_id'] ?? '' }}" data-start="{{ $initiative->start_date ?? $initiative['start_date'] ?? '' }}" data-end="{{ $initiative->end_date ?? $initiative['end_date'] ?? '' }}" data-impact="{{ $initiative->impact_score ?? $initiative['impact_score'] ?? 0 }}">Edit</button>
										<form method="POST" action="{{ url('/initiatives/'.$initiative->id ?? $initiative['id'] ?? '') }}" onsubmit="return confirm('Delete this initiative?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-red-50 text-red-600 text-sm">Delete</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-8 text-center text-gray-500">No initiatives yet. Click "Add Initiative" to create one.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<!-- Mobile stacked cards -->
			<div class="md:hidden space-y-3">
				@forelse ($initiatives ?? [] as $initiative)
					<div class="bg-white rounded-lg border p-3">
						<div class="flex items-start justify-between gap-3">
							<div class="min-w-0">
								<div class="font-semibold text-gray-900 truncate">{{ $initiative->title ?? $initiative['title'] ?? '-' }}</div>
								<div class="text-xs text-gray-500 mt-1 truncate">{{ Str::limit($initiative->description ?? $initiative['description'] ?? '', 120) }}</div>
								<div class="mt-3 flex flex-wrap gap-2 text-xs text-gray-500">
									<span class="inline-flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-full">{{ optional($initiative->category)->name ?? $initiative['category_name'] ?? 'Uncategorized' }}</span>
									<span class="inline-flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-full">{{ $initiative->start_date ?? $initiative['start_date'] ?? '-' }} • {{ $initiative->end_date ?? $initiative['end_date'] ?? '—' }}</span>
								</div>
							</div>
							<div class="flex-shrink-0 text-right">
								<div class="text-lg font-bold text-gray-900">{{ $initiative->impact_score ?? $initiative['impact_score'] ?? 0 }}</div>
								<div class="text-xs text-gray-400">Impact</div>
							</div>
						</div>

						<div class="mt-3 flex flex-col gap-2">
							<button class="edit-btn w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-md bg-emerald-50 text-emerald-700 text-sm" data-id="{{ $initiative->id ?? $initiative['id'] ?? '' }}" data-title="{{ e($initiative->title ?? $initiative['title'] ?? '') }}" data-description="{{ e($initiative->description ?? $initiative['description'] ?? '') }}" data-category="{{ $initiative->category_id ?? $initiative['category_id'] ?? '' }}" data-start="{{ $initiative->start_date ?? $initiative['start_date'] ?? '' }}" data-end="{{ $initiative->end_date ?? $initiative['end_date'] ?? '' }}" data-impact="{{ $initiative->impact_score ?? $initiative['impact_score'] ?? 0 }}">Edit</button>
							<form method="POST" action="{{ url('/initiatives/'.$initiative->id ?? $initiative['id'] ?? '') }}" onsubmit="window.dispatchEvent(new Event('closeMobileSidebar'));">
								@csrf
								@method('DELETE')
								<button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-md bg-red-50 text-red-600 text-sm">Delete</button>
							</form>
						</div>
					</div>
				@empty
					<div class="text-center text-gray-500">No initiatives yet. Click "Add Initiative" to create one.</div>
				@endforelse
			</div>
			<!-- end responsive list -->
 
 			<!-- Responsive Modal (z-60 to sit above sidebar/overlay) -->
			<div id="initiative-modal" class="fixed inset-0 z-60 hidden items-center justify-center text-black" role="dialog" aria-modal="true">
 				<div class="absolute inset-0 bg-black/40" data-close></div>
 				<div class="relative w-full max-w-lg mx-4 sm:mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
 					<form id="initiative-form" method="POST" action="{{ url('/initiatives') }}" class="p-6 max-h-[85vh] overflow-y-auto">
 						@csrf
 						<input type="hidden" name="id" id="initiative-id" value="">
 						<div class="flex items-center justify-between mb-4">
 							<h3 class="text-lg font-medium text-gray-900" id="modal-title">Add Initiative</h3>
 							<button type="button" data-close class="text-gray-400 hover:text-gray-600">Close</button>
 						</div>

 						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-black">
 							<div class="sm:col-span-2">
 								<label class="text-sm text-gray-700">Title</label>
 								<input id="initiative-title" name="title" required class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm" />
 							</div>

 							<div class="sm:col-span-2">
 								<label class="text-sm text-gray-700">Description</label>
 								<textarea id="initiative-description" name="description" rows="3" class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm"></textarea>
 							</div>

 							<div>
 								<label class="text-sm text-gray-700">Category</label>
 								<select id="initiative-category" name="category_id" class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm">
 									<option value="">Select category</option>
 									@foreach ($categories ?? [] as $cat)
 										<option value="{{ $cat->id ?? $cat['id'] }}">{{ $cat->name ?? $cat['name'] }}</option>
 									@endforeach
 								</select>
 							</div>

 							<div>
 								<label class="text-sm text-gray-700">Impact Score</label>
 								<input id="initiative-impact" name="impact_score" type="number" min="0" class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm" />
 							</div>

 							<div>
 								<label class="text-sm text-gray-700">Start Date</label>
 								<input id="initiative-start" name="start_date" type="date" class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm text-black" />
 							</div>

 							<div>
 								<label class="text-sm text-gray-700">End Date</label>
 								<input id="initiative-end" name="end_date" type="date" class="mt-2 w-full rounded-md border-gray-200 px-3 py-2 shadow-sm" />
 							</div>
 						</div>

 						<div class="mt-6 flex flex-col sm:flex-row items-center justify-end gap-3">
 							<button type="button" data-close class="w-full sm:w-auto px-4 py-2 rounded-md bg-gray-100">Cancel</button>
 							<button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-md bg-emerald-600 text-white">Save</button>
 						</div>
 					</form>
 				</div>
 			</div>
		</main>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const openBtn = document.getElementById('open-modal-btn');
			const modal = document.getElementById('initiative-modal');
			const form = document.getElementById('initiative-form');

			function openModal() {
				// request mobile sidebar to close first to avoid layout/overlay conflicts
				window.dispatchEvent(new Event('closeMobileSidebar'));
				if (!modal) return;
				modal.classList.remove('hidden');
				modal.classList.add('flex');
				// prevent background scroll while modal is open
				document.documentElement.classList.add('overflow-hidden');
				document.body.classList.add('overflow-hidden');
			}

			function closeModal() {
				if (!modal) return;
				modal.classList.add('hidden');
				modal.classList.remove('flex');
				document.documentElement.classList.remove('overflow-hidden');
				document.body.classList.remove('overflow-hidden');
			}

			function resetForm() {
				if (!form) return;
				form.reset();
				const idEl = document.getElementById('initiative-id');
				if (idEl) idEl.value = '';
				const titleEl = document.getElementById('modal-title');
				if (titleEl) titleEl.textContent = 'Add Initiative';
				form.action = "{{ url('/initiatives') }}";
				const methodEl = form.querySelector('input[name=\"_method\"]');
				if (methodEl) methodEl.remove();
			}

			if (openBtn) {
				openBtn.addEventListener('click', function (e) {
					e.preventDefault();
					resetForm();
					// ensure sidebar closed on mobile before opening modal
					window.dispatchEvent(new Event('closeMobileSidebar'));
					openModal();
				});
			}

			if (modal) {
				modal.addEventListener('click', function (e) {
					if (e.target && (e.target.closest('[data-close]') || e.target === modal)) {
						closeModal();
					}
				});
			}

			document.body.addEventListener('click', function (e) {
				const btn = e.target.closest('.edit-btn');
				if (!btn) return;

				e.preventDefault();
				if (!form) return;

				const id = btn.dataset.id || '';
				const title = btn.dataset.title || '';
				const description = btn.dataset.description || '';
				const category = btn.dataset.category || '';
				const start = btn.dataset.start || '';
				const end = btn.dataset.end || '';
				const impact = btn.dataset.impact || '';

				const idEl = document.getElementById('initiative-id');
				const titleEl = document.getElementById('initiative-title');
				const descEl = document.getElementById('initiative-description');
				const catEl = document.getElementById('initiative-category');
				const startEl = document.getElementById('initiative-start');
				const endEl = document.getElementById('initiative-end');
				const impactEl = document.getElementById('initiative-impact');
				const modalTitleEl = document.getElementById('modal-title');

				if (idEl) idEl.value = id;
				if (titleEl) titleEl.value = title;
				if (descEl) descEl.value = description;
				if (catEl) catEl.value = category;
				if (startEl) startEl.value = start;
				if (endEl) endEl.value = end;
				if (impactEl) impactEl.value = impact;
				if (modalTitleEl) modalTitleEl.textContent = 'Edit Initiative';

				form.action = "{{ url('/initiatives') }}/" + id;
				let methodEl = form.querySelector('input[name=\"_method\"]');
				if (!methodEl) {
					methodEl = document.createElement('input');
					methodEl.type = 'hidden';
					methodEl.name = '_method';
					form.appendChild(methodEl);
				}
				methodEl.value = 'PUT';

				openModal();
			});
		});
	</script>
</x-layouts.app>
