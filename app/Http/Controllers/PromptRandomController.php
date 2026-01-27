<?php

namespace App\Http\Controllers;

use App\Models\PromptRandom;
use Illuminate\Http\Request;

class PromptRandomController extends Controller
{
    /**
     * List randoms by group
     * GET /prompt-randoms?group=background_scene
     */
    public function index(Request $request)
    {
        $group = $request->query('group');

        $query = PromptRandom::query();

        if ($group) {
            $query->where('group', $group);
        }

        $randoms = $query
            ->orderBy('group')
            ->orderByDesc('weight')
            ->paginate(20);

        return view('prompt_randoms.index', compact('randoms', 'group'));
        // hoặc return response()->json($randoms);
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $group = $request->query('group');

        return view('prompt_randoms.create', compact('group'));
    }

    /**
     * Store new random
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|string|max:100',
            'value' => 'required|string|max:255',
            'weight' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        PromptRandom::create($data);

        return redirect()
            ->route('prompt-randoms.index', ['group' => $data['group']])
            ->with('success', 'Created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(PromptRandom $promptRandom)
    {
        return view('prompt_randoms.edit', compact('promptRandom'));
    }

    /**
     * Update random
     */
    public function update(Request $request, PromptRandom $promptRandom)
    {
        $data = $request->validate([
            'group' => 'required|string|max:100',
            'value' => 'required|string|max:255',
            'weight' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $promptRandom->update($data);

        return redirect()
            ->route('prompt-randoms.index', ['group' => $data['group']])
            ->with('success', 'Updated successfully');
    }

    /**
     * Delete random
     */
    public function destroy(PromptRandom $promptRandom)
    {
        $group = $promptRandom->group;
        $promptRandom->delete();

        return redirect()
            ->route('prompt-randoms.index', ['group' => $group])
            ->with('success', 'Deleted successfully');
    }
}
