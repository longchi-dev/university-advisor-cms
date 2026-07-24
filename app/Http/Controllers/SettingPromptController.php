<?php

namespace App\Http\Controllers;

use App\Models\AI\AIPromptTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingPromptController extends Controller
{
    public function index(): View
    {
        return view('settings.prompt.index', [
            'prompts' => AIPromptTemplate::query()
                ->orderBy('type')
                ->get(),
        ]);
    }

    public function update(
        Request $request,
        AIPromptTemplate $prompt
    ): RedirectResponse {

        $request->validate([
            'prompt' => ['required', 'string'],
        ]);

        $prompt->update([
            'prompt' => $request->prompt,
        ]);

        return back()->with(
            'success',
            'Cập nhật Prompt thành công.'
        );
    }
}
