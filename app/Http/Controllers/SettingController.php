<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
    ) {
    }

    public function index(): View
    {
        return view('setting', [
            'ollamaModel' => $this->settingService->getBackendEnv('OLLAMA_MODEL'),
            'ollamaEmbedding' => $this->settingService->getBackendEnv('OLLAMA_MODEL_EMBEDDING'),
            'ollamaBaseUrl' => $this->settingService->getBackendEnv('OLLAMA_BASE_URL'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'ollama_model' => ['required', 'string'],
            'ollama_model_embedding' => ['required', 'string'],
            'ollama_base_url' => ['required', 'url'],
        ]);

        $this->settingService->updateBackendEnv([
            'OLLAMA_MODEL' => $request->ollama_model,
            'OLLAMA_MODEL_EMBEDDING' => $request->ollama_model_embedding,
            'OLLAMA_BASE_URL' => $request->ollama_base_url,
        ]);

        return back()->with('success', 'Cập nhật thành công!');
    }
}
