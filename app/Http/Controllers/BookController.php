<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $books = \App\Models\Book::with('categories')->paginate(10);

        $status = $request->get('status');
        // dd($request);
        $keyword = $request->get('keyword') ? $request->get('keyword') : '';

        if ($status) {
            $books = \App\Models\Book::with('categories')->where('title', "LIKE", "%$keyword%")->where('status', strtoupper($status))->paginate(10);
        } else {
            $books = \App\Models\Book::with('categories')->where("title", "LIKE", "%$keyword%")->paginate(10);
        }

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_book = new \App\Models\Book;
        $new_book->title = $request->get('title');
        $new_book->description = $request->get('description');
        $new_book->author = $request->get('author');
        $new_book->publisher = $request->get('publisher');
        $new_book->price = $request->get('price');
        $new_book->stock = $request->get('stock');

        $new_book->status = $request->get('save_action');

        $cover = $request->file('cover');

        if ($cover) {
            $cover_path = $cover->store('book-covers', 'public');

            $new_book->cover = $cover_path;
        }

        $new_book->slug = \Str::slug($request->get('title'));

        $new_book->created_by = \Auth::user()->id;

        $new_book->save();

        $new_book->categories()->attach($request->get('categories'));

        if ($request->get('save_action') == 'PUBLISH') {
            return redirect()
                ->route('books.create')
                ->with('status', 'Book successfully saved and published');
        } else {
            return redirect()
                ->route('books.create')
                ->with('status', 'Book saved as draft');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = \App\Models\Book::findOrFail($id);

        return view('books.edit', ['book' => $book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = \App\Models\Book::findOrFail($id);

        $book->title = $request->get('title');
        $book->slug = $request->get('slug');
        $book->description = $request->get('description');
        $book->author = $request->get('author');
        $book->publisher = $request->get('publisher');
        $book->stock = $request->get('stock');
        $book->price = $request->get('price');

        $new_cover = $request->file('cover');

        if ($new_cover) {
            if ($book->cover && file_exists(storage_path('app/public/' . $book->cover))) {
                \Storage::delete('public/' . $book->cover);
            }

            $new_cover_path = $new_cover->store('book-covers', 'public');

            $book->cover = $new_cover_path;
        }

        $book->updated_by = \Auth::user()->id;

        $book->status = $request->get('status');

        $book->save();

        $book->categories()->sync($request->get('categories'));

        return redirect()->route('books.edit', [$book->id])->with('status', 'Book successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('status', 'Book moved to trash');
    }

    public function trash()
    {
        $books = \App\Models\Book::onlyTrashed()->paginate(10);

        return view('books.trash', ['books' => $books]);
    }

    public function restore($id)
    {
        $book = \App\Models\Book::withTrashed()->findOrFail($id);

        if ($book->trashed()) {
            $book->restore();
            return redirect()->route('books.trash')->with('status', 'Book successfully restored');
        } else {
            return redirect()->route('books.trash')->with('status', 'Book is not in trash');
        }
    }

    public function deletePermanent($id)
    {
        $book = \App\Models\Book::withTrashed()->findOrFail($id);

        if (!$book->trashed()) {
            return redirect()->route('books.trash')->with('status', 'Book is not in trash!')->with('status_type', 'alert');
        } else {
            $book->categories()->detach();
            $book->forceDelete();

            return redirect()->route('books.trash')->with('status', 'Book permanently deleted!');
        }
    }
}
