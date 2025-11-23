<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	{{-- ...existing head/meta/styles/scripts... --}}
	@stack('head')
</head>
<body class="antialiased bg-gray-50">
	<div class="min-h-screen flex">
		{{-- include the centralized sidebar component --}}
		<x-layouts.app.sidebar />

		{{-- main content area --}}
		<div class="flex-1">
			{{-- optional topbar slot if your layout uses one --}}
			@if (isset($topbar))
				<div class="bg-white border-b border-gray-200">
					<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
						{{ $topbar }}
					</div>
				</div>
			@endif

			<main class="min-h-[calc(100vh-64px)]">
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
					{{ $slot }}
				</div>
			</main>
		</div>
	</div>

	{{-- ...existing scripts... --}}
	@stack('scripts')
</body>
</html>
