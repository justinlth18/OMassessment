<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $token = session('api_token');

        if (!$token) {
            return redirect('/')->withErrors(['error' => 'You must be logged in to access this page.']);
        }

        $response = Http::withToken($token)->get('https://assessmentapi.orionmano.com/items');

        if ($response->ok()) {
            $items = $response->json(); 
            return view('items.index', ['items' => $items]);
        } else {
            return back()->withErrors(['error' => 'Failed to fetch items.']);
        }
    }

    public function create()
    {
        return view('items.create'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|integer|in:1,2,3,4',
        ]);

        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type, 
        ]);

        return redirect()->route('items.index');
    }

    
    public function showItems()
    {
        $items = Item::all();   
            return view('items.index', compact('items'));  
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    
        $item = Item::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return redirect()->route('items.index');  // Redirect to the item list
    }
    
    public function changeItemType(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:1,2,3,4',  
        ]);

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->type = $request->input('type');
        $item->save();

        return response()->json(['message' => 'Item type updated successfully', 'item' => $item], 200);
    }


    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
    
        return redirect()->route('items.index');  
    }
    
}
?>