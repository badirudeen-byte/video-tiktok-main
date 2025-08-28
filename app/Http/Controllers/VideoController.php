<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;


class VideoController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->age) {
            $userAge = auth()->user()->age;
            $videos = Video::whereNotNull('age_range')
            ->where(function ($query) use ($userAge) {
                $query->where('age_range', '<=', $userAge);
            })
            ->with('user', 'likes', 'comments')
            ->latest()
            ->paginate(10);
        } else {
            $videos = Video::whereNotNull('age_range')
            ->with('user', 'likes', 'comments')
            ->latest()
            ->paginate(10);
        }
        return view('videos.index', compact('videos'));
    }

    public function create_index()
    {
        $videos = Video::with('user', 'likes', 'comments')->latest()->paginate(10);
        return view('videos.create', compact('videos'));
    }

    public function like(Video $video)
    {
        $like = Like::where('user_id', auth()->id())->where('video_id', $video->id)->first();
        if ($like) {
            $like->delete();
        } else {
            Like::create(['user_id' => auth()->id(), 'video_id' => $video->id]);
        }

        return back();
    }

    public function comment(Request $request, Video $video)
    {
        $request->validate(['content' => 'required|string']);
        Comment::create([
            'user_id' => auth()->id(),
            'video_id' => $video->id,
            'content' => $request->content,
        ]);

        return back();
    }

    // public function store(Request $request)
    // {
    //     // Enhanced validation with better rules
    //     $validated = $request->validate([
    //         'video' => [
    //             'required',
    //             'file',
    //             'mimes:mp4,mov,avi,webm,mkv',
    //             'max:2048', // 2MB max size for initial validation
    //             'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska'
    //         ],
    //         'title' => 'required|string|max:255|min:3',
    //         'description' => 'nullable|string|max:1000',
    //     ], [
    //         'video.required' => 'Please select a video file to upload.',
    //         'video.mimes' => 'Only MP4, MOV, AVI, WebM, and MKV video formats are allowed.',
    //         'video.max' => 'Video file size cannot exceed 200MB.',
    //         'video.mimetypes' => 'Invalid video file type detected.',
    //         'title.min' => 'Title must be at least 3 characters long.',
    //         'title.max' => 'Title cannot exceed 255 characters.',
    //         'description.max' => 'Description cannot exceed 1000 characters.',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         Log::info('Upload process started');

    //         $videoFile = $request->file('video');

    //         // Additional security checks
    //         if (!$videoFile || !$videoFile->isValid()) {
    //             Log::error('Invalid video file', [
    //                 'exists' => $request->hasFile('video'),
    //                 'valid' => $videoFile ? $videoFile->isValid() : false,
    //                 'error' => $videoFile ? $videoFile->getErrorMessage() : 'File not uploaded',
    //             ]);
    //             throw new \Exception('Invalid video file uploaded.');
    //         }

    //         // Check file size again
    //         if ($videoFile->getSize() > 209715200) {
    //             Log::error('Video exceeds max size', [
    //                 'size_in_bytes' => $videoFile->getSize(),
    //             ]);
    //             throw new \Exception('Video file exceeds maximum allowed size of 200MB.');
    //         }

    //         // Generate unique filename
    //         $originalName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
    //         $extension = $videoFile->getClientOriginalExtension();
    //         $sanitizedName = Str::slug($originalName);
    //         $uniqueFilename = $sanitizedName . '_' . time() . '_' . Str::random(6) . '.' . $extension;

    //         $subfolder = 'videos/' . date('Y/m');
    //         $videoPath = $subfolder . '/' . $uniqueFilename;

    //         Log::info('Generated video path', ['videoPath' => $videoPath]);

    //         // Store video
    //         $stored = $videoFile->storeAs($subfolder, $uniqueFilename, 'public');

    //         if (!$stored) {
    //             Log::error('storeAs returned false');
    //             throw new \Exception('Failed to store video file on server.');
    //         }

    //         // Double-check if the file was saved
    //         if (!Storage::disk('public')->exists($videoPath)) {
    //             Log::error('File not found after storing', [
    //                 'expected_path' => $videoPath,
    //                 'full_path' => storage_path('app/public/' . $videoPath),
    //                 'exists' => Storage::disk('public')->exists($videoPath),
    //             ]);
    //             throw new \Exception('Video file was not properly saved.');
    //         }

    //         // Get file info
    //         $fileSize = $videoFile->getSize();
    //         $mimeType = $videoFile->getMimeType();

    //         Log::info('Video upload successful', [
    //             'user_id' => auth()->id(),
    //             'original_name' => $videoFile->getClientOriginalName(),
    //             'stored_path' => $videoPath,
    //             'file_size' => $fileSize,
    //             'mime_type' => $mimeType,
    //         ]);

    //         // Save video to DB
    //         $video = Video::create([
    //             'user_id' => auth()->id(),
    //             'title' => $validated['title'],
    //             'description' => $validated['description'],
    //             'video_path' => $videoPath,
    //             'thumbnail_path' => null,
    //         ]);

    //         DB::commit();

    //         Log::info('Video record created successfully', ['video_id' => $video->id]);

    //         return redirect()->route('home')->with('success', 'Video uploaded successfully!');
    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         Log::warning('Validation failed during video upload', [
    //             'errors' => $e->errors(),
    //             'user_id' => auth()->id()
    //         ]);
    //         throw $e;
    //     } catch (PostTooLargeException $e) {
    //         DB::rollBack();
    //         Log::error('Video too large error', [
    //             'max_size' => ini_get('upload_max_filesize'),
    //             'error' => $e->getMessage(),
    //             'user_id' => auth()->id()
    //         ]);
    //         return back()->withErrors(['video' => 'The uploaded video is too large. Maximum size allowed is 200MB.']);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         if (isset($videoPath) && Storage::disk('public')->exists($videoPath)) {
    //             Storage::disk('public')->delete($videoPath);
    //             Log::info('Partially uploaded file deleted', ['path' => $videoPath]);
    //         }

    //         Log::error('Unhandled exception during video upload', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //             'user_id' => auth()->id(),
    //         ]);

    //         $errorMessage = 'Failed to upload video. Please try again.';

    //         if (str_contains($e->getMessage(), 'disk space')) {
    //             $errorMessage = 'Server storage is full. Please try again later.';
    //         } elseif (str_contains($e->getMessage(), 'permission')) {
    //             $errorMessage = 'Server permission error. Contact support.';
    //         }

    //         return back()->withErrors(['video' => $errorMessage]);
    //     }
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'producer' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'age_range' => 'nullable|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:2046', // 2MB max
        ]);

        // Get uploaded file
        $file = $request->file('video');
        $filename = uniqid() . '_' . $file->getClientOriginalName();

        // Move file to public/videos
        $file->move(public_path('videos'), $filename);

        // Save record
        Video::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'producer' => $validated['producer'] ?? null,
            'genre' => $validated['genre'] ?? null,
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'age_range' => $validated['age_range'] ?? null,
            'video_path' => 'videos/' . $filename,
            'mime_type' => $file->getClientMimeType(),
            'thumbnail_path' => null,
        ]);

        return redirect()->route('home')->with('success', 'Video uploaded successfully!');
    }
}
