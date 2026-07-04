<?php

namespace App\Http\Controllers;

use App\Models\GradeSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GradeSettingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(GradeSetting::class);
    }

    public function index()
    {
        $gradeSettings = GradeSetting::orderByDesc('min_score')->get();

        return view('results.grade-settings', compact('gradeSettings'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'min_score' => ['required', 'integer', 'min:0', 'max:100'],
            'max_score' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_score'],
            'grade' => ['required', 'string', 'max:5'],
            'remark' => ['required', 'string', 'max:100'],
        ]);

        try {
            GradeSetting::create($validated);

            return response()->json([
                'message' => 'Grade setting added successfully!',
                'redirect' => route('grade-settings.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add grade setting', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to add grade setting.'], 500);
        }
    }

    public function update(Request $request, GradeSetting $gradeSetting): JsonResponse
    {
        $validated = $request->validate([
            'min_score' => ['required', 'integer', 'min:0', 'max:100'],
            'max_score' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_score'],
            'grade' => ['required', 'string', 'max:5'],
            'remark' => ['required', 'string', 'max:100'],
        ]);

        try {
            $gradeSetting->update($validated);

            return response()->json([
                'message' => 'Grade setting updated successfully!',
                'redirect' => route('grade-settings.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update grade setting', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update grade setting.'], 500);
        }
    }

    public function destroy(GradeSetting $gradeSetting): JsonResponse
    {
        try {
            $gradeSetting->delete();

            return response()->json(['message' => 'Grade setting deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete grade setting', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete grade setting.'], 500);
        }
    }
}
