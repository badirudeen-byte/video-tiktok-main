<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-semibold mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center g-4">
        @auth
            <!-- Upload Video Card -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-3">Upload Video</h5>
                        <p class="card-text mb-4">Share your moments with others by uploading a new video.</p>
                        <a href="{{ route('videos.store') }}" class="btn btn-primary mt-auto">
                            <i class="bi bi-upload me-2"></i> Upload Video
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stream Video Card -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-3">Stream Video</h5>
                        <p class="card-text mb-4">Watch and enjoy videos from the community.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-auto">
                            <i class="bi bi-play-circle me-2"></i> Stream Video
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</x-app-layout>
