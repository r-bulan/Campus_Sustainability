<x-layouts.app :title="__('Categories')">
	@php
		$cats = $categories ?? \App\Models\Category::orderBy('name')->get();
	@endphp

	<div class="min-h-screen bg-gray-50">
		<div class="bg-white border-b border-gray-200">
			<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
				<h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Categories</h1>
				<p class="text-sm sm:text-base text-gray-500">Organize your sustainability initiatives</p>
			</div>
		</div>

		{{-- flash message --}}
		@if (session('success'))
			<div id="flash-success-cats" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
				<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-md p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center gap-3">
					<div class="flex items-start sm:items-center gap-3">
						<svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
						<div class="text-sm leading-tight break-words">{{ session('success') }}</div>
					</div>
					<div class="sm:ml-auto flex-shrink-0">
						<button type="button" onclick="document.getElementById('flash-success-cats').style.display='none'" aria-label="Dismiss success message" class="inline-flex items-center justify-center px-3 py-1.5 rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 text-sm">
							Dismiss
						</button>
					</div>
				</div>
			</div>
		@endif

		<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 text-black">
			<div class="mb-6 lg:mb-8 bg-white border border-gray-100 rounded-lg shadow-sm">
				<div class="p-4 border-b border-gray-100">
					<div class="text-lg font-semibold">Add New Category</div>
				</div>
				<div class="p-4">
					<form method="POST" action="{{ url('/categories') }}" class="flex flex-col sm:flex-row gap-3" onsubmit="window.dispatchEvent(new Event('closeMobileSidebar'))">
						@csrf
						<input name="name" required placeholder="Enter category name" class="border-gray-200 rounded-md px-3 py-2 flex-1 shadow-sm focus:ring-emerald-200 focus:border-emerald-500" />
						<button type="submit" class="w-full sm:w-auto inline-flex items-center gap-2 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white" onclick="window.dispatchEvent(new Event('closeMobileSidebar'))">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
							Add
						</button>
					</form>
				</div>
			</div>

			@if ($cats->isEmpty())
				<div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
					<div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
						<svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
					</div>
					<h3 class="text-lg font-semibold text-gray-900 mb-2">No categories yet</h3>
					<p class="text-gray-500">Create your first category to get started</p>
				</div>
			@else
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
					@foreach ($cats as $cat)
						<div class="bg-white rounded-lg border border-gray-100 p-4 flex flex-col sm:flex-row items-center justify-between shadow-sm gap-3">
							<div class="flex items-center gap-3 w-full sm:w-auto">
								<div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
								</div>
								<div class="font-medium text-gray-900">{{ $cat->name }}</div>
							</div>

							<form method="POST" action="{{ url('/categories/'.$cat->id) }}" onsubmit="window.dispatchEvent(new Event('closeMobileSidebar')) || confirm('Delete category &quot;{{ addslashes($cat->name) }}&quot;?');" class="w-full sm:w-auto">
								@csrf
								@method('DELETE')
								<button type="submit" class="w-full sm:w-auto ml-0 sm:ml-4 inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-red-50 text-red-600 text-sm">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7L5 21M5 7l14 14" /></svg>
									Delete
								</button>
							</form>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>
</x-layouts.app>
