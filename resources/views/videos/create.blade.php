<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - TikTok Clone</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #000, #111);
            color: white;
            padding-top: 80px; /* Navbar offset */
        }
        .upload-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #fe2c55;
            box-shadow: 0 0 0 0.25rem rgba(254, 44, 85, 0.25);
        }
        .upload-button {
            background: #fe2c55;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 12px 24px;
            transition: background 0.3s ease;
            border: none;
        }
        .upload-button:hover {
            background: #ff4d7d;
        }
        .video-preview {
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 16px;
        }
        .error-message {
            color: #ff4d7d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('layouts.navigation')

    <!-- Upload Form Container -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="upload-card w-100" style="max-width: 600px;">
            <h1 class="text-center fw-bold mb-2 mt-3">📤 Upload a New Video</h1>
            <p class="text-center text-light mb-4">Share your creativity with the world!</p>

            <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- Video File Input -->
                <div class="mb-3">
                    <label for="video" class="form-label fw-semibold">Video File <span class="text-danger">*</span></label>
                    <input type="file" name="video" id="video" accept="video/*" required class="form-control">
                    @error('video')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <video id="videoPreview" class="video-preview d-none w-100 mt-3" controls></video>
                </div>

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="form-control">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control" placeholder="Say something about your video...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Producer -->
                <div class="mb-3">
                    <label for="producer" class="form-label fw-semibold">Producer</label>
                    <input type="text" name="producer" id="producer" value="{{ old('producer') }}" class="form-control">
                    @error('producer')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Genre -->
                <div class="mb-3">
                    <label for="genre" class="form-label fw-semibold">Genre</label>
                    <input type="text" name="genre" id="genre" value="{{ old('genre') }}" class="form-control">
                    @error('genre')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Age Range -->
                <div class="mb-3">
                    <label for="age_range" class="form-label fw-semibold">Age Range</label>
                    <input type="text" name="age_range" id="age_range" value="{{ old('age_range') }}" class="form-control">
                    @error('age_range')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="text-center mt-4">
                    <button type="submit" class="upload-button">🚀 Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Video preview functionality
        document.getElementById('video').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('videoPreview');
            if (file) {
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
                preview.src = '';
            }
        });
    </script>
</body>
</html>
