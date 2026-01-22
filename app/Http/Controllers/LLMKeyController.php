<?php

namespace App\Http\Controllers;

use App\Models\LlmKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LLMKeyController extends Controller
{
    public function index(): View
    {
        $keys = LlmKey::query()->orderBy('id', 'desc')->paginate(10);
        return view('llm_keys.index', compact('keys'));
    }

    public function create(): View
    {
        return view('llm_keys.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'api_key' => 'required|string|unique:llm_keys,api_key',
            'quota_limit' => 'nullable|integer|min:0',
        ]);

        LlmKey::query()->create($data);

        return redirect()->route('llm-keys.index')->with('success', 'Key created successfully.');
    }

    public function edit(LlmKey $llmKey): View
    {
        return view('llm_keys.edit', compact('llmKey'));
    }

    public function update(Request $request, LlmKey $llmKey): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'api_key' => 'required|string|unique:llm_keys,api_key,' . $llmKey->id,
            'is_active' => 'boolean',
            'quota_limit' => 'nullable|integer|min:0',
        ]);

        $llmKey->update($data);

        return redirect()->route('llm-keys.index')->with('success', 'Key updated successfully.');
    }

//    public function destroy(GeminiKey $geminiKey): RedirectResponse
//    {
//        $geminiKey->delete();
//
//        return redirect()->route('gemini-keys.index')->with('success', 'Key deleted successfully.');
//    }
}
