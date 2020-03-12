<?php

namespace App\Http\Controllers\Dashboard;

use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(Request $request)
    {
       //abort_unless(\Gate::allows('read_stores'), 403);

        $stores = Store::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->latest()->paginate(25);

        return view('dashboard.stores.index', compact('stores'));

    }//end of index

    public function create()
    {
      // abort_unless(\Gate::allows('create_stores'), 403);

        return view('dashboard.stores.create');

    }//end of create

    public function store(Request $request)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('store_translations', 'name')]];

        }//end of for each

        $request->validate($rules);

        Store::create($request->all());
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.stores.index');

    }//end of store

    public function edit(Store $store)
    {
        return view('dashboard.stores.edit', compact('store'));

    }//end of edit

    public function update(Request $request, Store $store)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('store_translations', 'name')->ignore($store->id, 'Store_id')]];

        }//end of for each

        $request->validate($rules);

        $store->update($request->all());
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.stores.index');

    }//end of update

    public function destroy(Store $store)
    {
        abort_unless(\Gate::allows('store_delete'), 403);

        $store->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.stores.index');

    }//end of destroy

}//end of controller
