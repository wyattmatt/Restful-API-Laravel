<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //  Status 200 OK (default)
        return response()->json(Member::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:members|max:20',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:members|max:255',
        ]);

        $member = Member::create($validated);
        // RESTful status code 201 Created
        return response()->json(['message' => 'Member added successfully', 'data' => $member], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        // Status 200 OK
        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            // Ignore ID member saat ini agar tetap bisa menggunakan student_id dan email yang sama
            'student_id' => ['sometimes', 'max:20', Rule::unique('members')->ignore($member->id)],
            'name' => 'sometimes|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('members')->ignore($member->id)],
        ]);

        $member->update($validated);
        // Status 200 OK (default)
        return response()->json(['message' => 'Member updated successfully', 'data' => $member]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        // RESTful status code 204 No Content
        return response()->json(null, 204);
    }
}
