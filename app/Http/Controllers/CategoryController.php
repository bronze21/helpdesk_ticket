<?php

namespace App\Http\Controllers;

use App\Models\Category;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;

class CategoryController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->data['crumbs'][1] = [
            'title' => 'Categories',
            'url' => route('categories.index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['datas'] = Category::paginate(10);
        $this->data['title'] = "List Category";
        return view('pages.categories.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = 'Create New Category';
        $this->data['crumbs'][] = [
            'title' => 'Create New Category',
            'url' => route('categories.create'),
        ];

        return view('pages.categories.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|unique:categories,name',
        ]);

        try {
            DB::beginTransaction();
            $newCategory = new Category();
            $newCategory->name = $request->name;
            $newCategory->slug = \Str::slug($request->name);
            $newCategory->save();
            DB::commit();
            return redirect()->route('categories.index')->with('success','Category has been created');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function subcategories(Request $request, Category $category)
    {
        $subcategory = $category->subCategories();
        
        return DataTables::of($subcategory)
        ->addIndexColumn()
        ->addColumn('status',function($data){
            if($data->isActive){
                return "<span class='badge bg-success'>Active</span>";
            }else{
                return "<span class='badge bg-danger'>Inactive</span>";
            }
        })
        ->addColumn('action',function($data) use ($category){
            return '<button type="button" data-url="'.route('subcategories.show',[$data->id]).'" x-on:click="getSubcategory($el,'.$data->id.')" class="btn btn-sm btn-primary">Edit</button>
            <button type="button" data-url="'.route('subcategories.destroy',[$data->id]).'" x-on:click="deleteSubcategory($el,'.$data->id.')" class="btn btn-sm btn-outline-danger">Delete</button>';
        })
        ->rawColumns(['status','action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->data['title'] = "Edit Category";
        $this->data['crumbs'][] = [
            'title' => 'Edit Category',
            'url' => route('categories.edit',$category->id),
        ];
        $this->data['data'] = $category;
        return view('pages.categories.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=>'required|min:3|unique:categories,name,'.$category->id,
        ]);

        try {
            DB::beginTransaction();
            $category->name = $request->name;
            $category->slug = \Str::slug($request->name);
            $category->save();
            DB::commit();
            return redirect()->route('categories.edit',$category->id)->with('success','Category has been updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            if($request->ajax()) return $this->json_success(null,'Category Deleted Successfully');
            return redirect()->route('categories.index')->with('success','Category has been deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            if($request->ajax()) return $this->json_error(null,$th->getMessage());
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }
}
