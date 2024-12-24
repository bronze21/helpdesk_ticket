<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\Subcategory;
use DB;
use Illuminate\Http\Request;
use Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|min:3|unique:subcategories,name',
            'category_id'=>'required|exists:categories,id',
        ];
        if($request->ajax()){
            $validator = Validator::make($request->input(),$rules);
            
            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }
        }else{
            $request->validate($rules);
        }
        try {
            DB::beginTransaction();
            $sub_category = new Subcategory();
            $sub_category->name = $request->name;
            $sub_category->slug = \Str::slug($request->name);
            $sub_category->category_id = $request->category_id;
            $sub_category->save();
            DB::commit();
            if($request->ajax()){
                return $this->json_success($sub_category,"Sub Category Created Successfully");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            if($request->ajax()){
                return $this->json_error(['inputs'=>$request->input()],$th->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Subcategory $subcategory)
    {
        if(!$request->ajax()){
            return $this->json_error(null,'Method not allowed','405');
        }
        $subcategory->update_url = route('subcategories.update',[$subcategory->id]);
        return $this->json_success($subcategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $rules = [
            'name'=>'required|min:3|unique:subcategories,name,'.$subcategory->id,
            'category_id'=>'required|exists:categories,id',
        ];
        if($request->ajax()){
            $validator = Validator::make($request->input(),$rules);
            
            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }
        }else{
            $request->validate($rules);
        }
        try {
            DB::beginTransaction();
            $subcategory->name = $request->name;
            $subcategory->slug = \Str::slug($request->name);
            $subcategory->category_id = $request->category_id;
            $subcategory->save();
            DB::commit();
            if($request->ajax()){
                return $this->json_success($subcategory,"Sub Category Created Successfully");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            if($request->ajax()){
                return $this->json_error(['inputs'=>$request->input()],$th->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Subcategory $subcategory)
    {
        try {
            DB::beginTransaction();
            $subcategory->delete();
            DB::commit();
            if($request->ajax()) return $this->json_success(null,'Sub Category Deleted Successfully');
            return redirect()->route('subcategories.index')->with('success','Sub Category has been deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            if($request->ajax()) return $this->json_error(null,$th->getMessage());
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }
}
