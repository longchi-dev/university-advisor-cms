<?php

namespace App\Http\Controllers;

use App\Models\LlmKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LLMKeyController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];
        $keyName = $request->get('key_name');
        $lastUsedDate = $request->get('last_used_at');

        $keys = LlmKey::query()
            ->when($keyName, function ($q) use ($keyName) {
                $q->where('name', 'like', "%{$keyName}%");
            })
            ->when($lastUsedDate, function ($q) use ($lastUsedDate) {
                $q->whereDate('last_used_at', $lastUsedDate);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data['keys'] = $keys;
        $data['keyName'] = $keyName;
        $data['lastUsedDate'] = $lastUsedDate;

        return view('llm_keys.index', $data);
    }

    public function create(): View
    {
        return view('llm_keys.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
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
            'model' => 'nullable|string|max:255',
            'api_key' => 'required|string|unique:llm_keys,api_key,' . $llmKey->id,
            'is_active' => 'boolean',
            'quota_limit' => 'nullable|integer|min:0',
        ]);

        if ($data['name'] &&($data['name'] || $llmKey->name)) {
            $llmKey->name = $data['name'];
        }
        if ($data['model'] &&($data['model'] || $llmKey->model)) {
            $llmKey->model = $data['model'];
        }
        if ($data['api_key'] &&($data['api_key'] || $llmKey->api_key)) {
            $llmKey->api_key = $data['api_key'];
        }
        if ($data['is_active'] &&($data['is_active'] || $llmKey->is_active)) {
            $llmKey->is_active = $data['is_active'];
        }
        if ($data['quota_limit'] &&($data['quota_limit'] || $llmKey->quota_limit)) {
            $llmKey->quota_limit = $data['quota_limit'];
        }
        if ($llmKey->isDirty()){
            $llmKey->save();
        }

        return redirect()->route('llm-keys.index')->with('success', 'Key updated successfully.');
    }

//    public function destroy(GeminiKey $geminiKey): RedirectResponse
//    {
//        $geminiKey->delete();
//
//        return redirect()->route('gemini-keys.index')->with('success', 'Key deleted successfully.');
//    }
}
