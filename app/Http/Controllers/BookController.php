<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //  Status 200 OK (default)
        return response()->json(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|unique:books|max:20',
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'published_year' => 'required|integer|max:' . date('Y'),
            'stock' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);
        // RESTful status code 201 Created
        return response()->json(['message' => 'Book added successfully', 'data' => $book], 201);
    }

    /**
     * Display the specified resource.
     */
    // Route Model Binding memastikan 404 jika resource tidak ditemukan
    public function show(Book $book)
    {
        // Status 200 OK
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            // Ignore ID buku saat ini agar tetap bisa menggunakan ISBN yang sama
            'isbn' => ['sometimes', 'max:20', Rule::unique('books')->ignore($book->id)],
            'title' => 'sometimes|max:255',
            'author' => 'sometimes|max:255',
            'published_year' => 'sometimes|integer|max:' . date('Y'),
            'stock' => 'sometimes|integer|min:0', 
        ]);

        $book->update($validated);
        // Status 200 OK (default)
        return response()->json(['message' => 'Book updated successfully', 'data' => $book]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        // RESTful status code 204 No Content
        return response()->json(null, 204);
    }
}
