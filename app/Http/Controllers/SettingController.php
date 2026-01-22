<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\IKeywordLabelRepository;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\AppSetting;
use App\Models\KeywordLabel;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $data = [];
        $data['promptImage'] = AppSetting::getValue('prompt', 'image');
        $data['promptValidate'] = AppSetting::getValue('prompt', 'validate');
//        $data['labels'] = KeywordLabel::with('themes')->get();

        return view('setting', $data);
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Lưu image & validate
        AppSetting::setValue('prompt', 'image', $validated['image']);
        AppSetting::setValue('prompt', 'validate', $validated['validate']);

        // Chuẩn bị cho bulk update keyword_labels
        $caseLabel = '';
        $caseLabelEn = '';
        $ids = [];

        // Chuẩn bị cho bulk update themes
        $caseTheme = '';
        $themeIds = [];

        foreach ($validated['labels'] as $id => $value) {
            // keyword_labels
            $ids[] = $id;
            $caseLabel   .= "WHEN {$id} THEN '" . addslashes($value['label']) . "' ";
            $caseLabelEn .= "WHEN {$id} THEN '" . addslashes($value['label_en']) . "' ";

            // themes
            if (!empty($value['themes'])) {
                foreach ($value['themes'] as $themeId => $themeContent) {
                    $themeIds[] = $themeId;
                    $caseTheme .= "WHEN {$themeId} THEN '" . addslashes($themeContent) . "' ";
                }
            }
        }

        $sqlLabels = null;
        if ($ids) {
            $idList = implode(',', $ids);
            $sqlLabels = "
            UPDATE keyword_labels
            SET label = CASE id {$caseLabel} END,
                label_en = CASE id {$caseLabelEn} END
            WHERE id IN ($idList)
        ";
        }

        $sqlThemes = null;
        if ($themeIds) {
            $themeIdList = implode(',', $themeIds);
            $sqlThemes = "
            UPDATE themes
            SET content = CASE id {$caseTheme} END
            WHERE id IN ($themeIdList)
        ";
        }

        DB::transaction(function () use ($sqlLabels, $sqlThemes) {
            if ($sqlLabels) {
                DB::statement($sqlLabels);
            }
            if ($sqlThemes) {
                DB::statement($sqlThemes);
            }
        });

        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }
}
