<?php

namespace App\Http\Controllers;

use App\Models\LlmKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeminiKeyController extends Controller
{
    public function index(): View
    {
        $keys = LlmKey::query()->orderBy('id', 'desc')->paginate(10);
        return view('gemini_keys.index', compact('keys'));
    }

    public function create(): View
    {
        return view('gemini_keys.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'api_key' => 'required|string|unique:gemini_keys,api_key',
            'quota_limit' => 'nullable|integer|min:0',
        ]);

        LlmKey::query()->create($data);

        return redirect()->route('gemini-keys.index')->with('success', 'Key created successfully.');
    }

    public function edit(LlmKey $geminiKey): View
    {
        return view('gemini_keys.edit', compact('geminiKey'));
    }

    public function update(Request $request, LlmKey $geminiKey): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'api_key' => 'required|string|unique:gemini_keys,api_key,' . $geminiKey->id,
            'is_active' => 'boolean',
            'quota_limit' => 'nullable|integer|min:0',
        ]);

        $geminiKey->update($data);

        return redirect()->route('gemini-keys.index')->with('success', 'Key updated successfully.');
    }

//    public function destroy(GeminiKey $geminiKey): RedirectResponse
//    {
//        $geminiKey->delete();
//
//        return redirect()->route('gemini-keys.index')->with('success', 'Key deleted successfully.');
//    }
}
