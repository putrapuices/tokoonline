<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriController extends Controller
{
    /**
     * Action untuk menampilkan semua kategori
     */
    public function index()
    {

        /* tarik data dr table categories*/
        // $daftar_kategori = \App\Models\Category::all();
        /*tarik data dengan tampilan pagination*/
        $daftar_kategori = \App\Models\Categori::paginate(5);
        /*menampilkan data di folder view*/
        return view("kategori.index", ["daftar_kategori" => $daftar_kategori]);
    }

    public function create()
    {
        return view('categories.create');
    }

    /**
     * Action untuk mencari kategori berdasarkan nama
     */
    public function search(Request $request)
    {
        /* Dapatkan keyword dr querystring ? name=keyword */
        $keyword = $request->get("name");

        /* cari kategori where name == keyword dari querystring*/

        return \App\Models\Category::where("name", "LIKE", "%$keyword%")->get();
    }

    /**
     * Action untuk delete kategori
     */
    public function delete($id)
    {
        $category = \App\Models\Category::findOrFail($id);

        if (!$category->trashed()) {
            $category->delete();
            return "Kategori $category->name berhasil dihapus";
        }
    }

    /**
     * Action untuk merestore kategori yang telah didelete
     */
    public function restore($id)
    {
        $category = \App\models\Category::withTrashed()->findOrFail($id);

        if (!$category->trashed()) {
            return "Kategori tidak perlu direstore";
        }
        /**
         * Penambahan perintah untuk merubah colomn delete_at menjadi null
         */
        \App\models\Category::withTrashed()->findOrFail($id)->restore();
        return "Kategori $category->name berhasil direstore";
    }

    /**
     * Action untuk permanent delete dari database (tidak bisa direstore)
     */
    public function permanentDelete($id)
    {
        $category = \App\Models\Category::withTrashed()->findOrFail($id);
        $category->forceDelete();

        return "Kategori $category->name berhasil dihapus permanent. Tidak bisa direstore";
    }
}
