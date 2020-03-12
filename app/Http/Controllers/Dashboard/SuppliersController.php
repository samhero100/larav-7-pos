<?php

namespace App\Http\Controllers\Dashboard;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = supplier::when($request->search, function($q) use ($request){

            return $q->whereTranslationLike('name', 'like', '%' . $request->search . '%')
              //  ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhereTranslationLike('address', 'like', '%' . $request->search . '%')
                ->andWhereTranslationEqual('type',   'عميل' );

        })->latest()->paginate(25);

        return view('dashboard.suppliers.index', compact('suppliers'));

    }//end of index

    public function create()
    {
        return view('dashboard.suppliers.create');

    }//end of create

    public function store(Request $request)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('supplier_translations', 'name')]];
          //  $rules += [$locale . '.address' => ['required', Rule::unique('supplier_translations', 'address')]];

        }//end of for each
        // $rules +=['phone' => 'required|array|min:1',
        // 'phone.0' => 'required',];
        $request->validate($rules);

        // $request->validate([
        //     'name' => 'required',
        //     'phone' => 'required|array|min:1',
        //     'phone.0' => 'required',
        //     'address' => 'required',
        // ]);

        $request_data = $request->all();
       // $request_data['phone'] = array_filter($request->phone);

        Supplier::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.suppliers.index');

    }//end of store

    public function edit(Supplier $supplier)
    {
        return view('dashboard.suppliers.edit', compact('supplier'));

    }//end of edit

    public function update(Request $request, Supplier $supplier)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('supplier_translations', 'name')]];
            //$rules += [$locale . '.address' => ['required', Rule::unique('supplier_translations', 'address')]];

        }//end of for each
        // $rules +=['phone' => 'required|array|min:1',
        // 'phone.0' => 'required',];
        $request->validate($rules);

        // $request->validate([
        //     'name' => 'required',
        //     'phone' => 'required|array|min:1',
        //     'phone.0' => 'required',
        //     'address' => 'required',
        // ]);

        $request_data = $request->all();
       // $request_data['phone'] = array_filter($request->phone);
        
        $supplier->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.suppliers.index');

    }//end of update

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.suppliers.index');

    }//end of destroy

}//end of controller -->
