<?php

namespace App\Http\Controllers;

use App\Models\FeesInformation;
use App\Models\FeesType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountsController extends Controller
{
    /** account/fees/collections/page */
    public function index()
    {
        // Eloquent relationship instead of join
        $feesInformation = FeesInformation::with('student')->get();

        return view('accounts.feescollections', compact('feesInformation'));
    }

    /** add/fees/collection/page */
    public function create()
    {
        $users = User::whereIn('type', [User::STUDENT, User::PARENT])->get();
        $feesType = FeesType::all();

        return view('accounts.add-fees-collection', compact('users', 'feesType'));
    }

    /** saveRecord */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string'],
            'student_name' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'fees_type' => ['required', 'string'],
            'fees_amount' => ['required', 'string'],
            'paid_date' => ['required', 'string'],
        ]);

        $feeData = $validated;
        $feeData['user_id'] = $feeData['student_id'];
        unset($feeData['student_id'], $feeData['student_name']);

        try {
            FeesInformation::create($feeData);

            return response()->json(['message' => 'Fees Collection added successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to add Fees Collection', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to add fees collection.'], 500);
        }
    }
}
