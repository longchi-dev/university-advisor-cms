<?php

namespace App\AI\Services;

use App\AI\Dto\ConversationDto;
use App\Contracts\Repositories\IAIPromptTemplateRepository;
use App\Enums\AIPromptTemplateEnum;
use App\Enums\RoleEnum;
use App\Helpers\TextHelper;
use App\Models\Score;

class AIService
{
    public function __construct(
        protected IAIPromptTemplateRepository $aiPromptTemplateRepository,
    ) {

    }

    public function classifyIntentConversation(string $message): ConversationDto
    {
        $conversation = new ConversationDto();

        $systemPrompt = $this->buildAiPromptClassifyIntent();
        $conversation->setSystemPrompt($systemPrompt);
        $conversation->pushUserMessage($message);

        return $conversation;
    }

    public function advisorConversation(
        string $message,
        array $history = [],
        ?array $profileContext = null,
        ?string $feedback = null
    ): ConversationDto {
        $conversation = new ConversationDto();

        $systemPrompt = $this->buildAiPromptAdvisor();

        $this->pushHistoryToConversation($conversation, $history);

        if ($feedback) {
            $conversation->pushUserMessage("
                Previous answer was incorrect:
                {$feedback}

                Please answer again. Follow the rules strictly.
            ");
        }

        [$profilePrompt, $shouldSuggestProfileUpdate] = $this->buildProfileContextAndStatus($profileContext);

        $profileInstruction = $shouldSuggestProfileUpdate
            ? "PROFILE STATUS: SPARSE/EMPTY. If the user asks for general advice, add a gentle 1-sentence tip encouraging them to update their profile."
            : "PROFILE STATUS: SUFFICIENT. Do NOT suggest or ask the user to update their profile.";

        $conversation->setSystemPrompt($systemPrompt);

        $conversation->pushUserMessage("
            {$profilePrompt}

            User Question:
            {$message}

            FINAL EXECUTION RULES:
            1. STRICT 100% VIETNAMESE & DIRECT ANSWER: Answer ONLY what is explicitly asked in 'User Question' using general knowledge. Answer directly and concisely.
            2. NO RAW SCORE COMPARISON: Present scores accurately with their respective scales (e.g. 30-point high school exam scale vs 1200-point competency test scale). NEVER compare raw numbers across different evaluation scales directly!
            3. STRICT PROFILE TIP RULE:
               - {$profileInstruction}
               - EXCEPTION: If the user query is a strictly FACTUAL query (e.g. checking benchmark scores like 'điểm chuẩn', 'học phí') OR if they mention they just updated, NEVER suggest updating profile regardless of profile status.
            4. ABSOLUTELY NO EXTRA TEXT: No meta-commentary, no notes in brackets like '(Các bạn hãy...)', no duplicate greetings. Stop immediately when done.
        ");

        return $conversation;
    }

    public function advisorRagConversation(
        string $message,
        array $documents,
        array $history = [],
        ?array $profileContext = null,
        ?string $feedback = null
    ): ConversationDto {
        $conversation = new ConversationDto();

        $systemPrompt = $this->buildAiPromptAdvisor();

        $this->pushHistoryToConversation($conversation, $history);

        if ($feedback) {
            $conversation->pushUserMessage("
                Previous answer was incorrect:
                {$feedback}

                Please answer again using ONLY the provided context.
            ");
        }

        [$profilePrompt, $shouldSuggestProfileUpdate] = $this->buildProfileContextAndStatus($profileContext);

        $context = collect($documents)->map(function ($doc, $i) {
            $docText = is_array($doc) ? ($doc['content'] ?? '') : ($doc->content ?? '');

            if (empty($docText)) {
                $docText = is_array($doc) ? ($doc['text'] ?? '') : ($doc->text ?? '');
            }

            return "Document " . ($i + 1) . ":\n" . $docText;
        })->implode("\n\n");

        $profileInstruction = $shouldSuggestProfileUpdate
            ? "PROFILE STATUS: SPARSE/EMPTY. If the user asks for general advice, add a gentle 1-sentence tip encouraging them to update their profile."
            : "PROFILE STATUS: SUFFICIENT. Do NOT suggest or ask the user to update their profile.";

        $userPrompt = trim("
            {$profilePrompt}

            Here is some related information:
            --- START OF CONTEXT ---
            {$context}
            --- END OF CONTEXT ---

            User question:
            {$message}

            FINAL EXECUTION RULES:
            1. STRICT 100% VIETNAMESE & DIRECT ANSWER: Answer ONLY what is explicitly asked in 'User question'. Do NOT list unrelated schools/documents from context if they are not requested (e.g. if asked about Bách Khoa, do NOT list Khoa học Tự nhiên).
            2. NO RAW SCORE COMPARISON: Present scores accurately with their respective scales as stated in the context (e.g. 83.74/100 vs 29.19/30). NEVER compare them directly.
            3. STRICT PROFILE TIP RULE:
               - {$profileInstruction}
               - EXCEPTION: If the user query is a strictly FACTUAL query (e.g. checking benchmark scores like 'điểm chuẩn', 'học phí') OR if they mention they just updated, NEVER suggest updating profile regardless of profile status.
            4. ABSOLUTELY NO EXTRA TEXT: No meta-commentary, no notes in brackets like '(Các bạn hãy...)', no duplicate greetings. Stop immediately when done.
        ");

        $conversation->setSystemPrompt($systemPrompt);
        $conversation->pushUserMessage($userPrompt);

        return $conversation;
    }

    public function mbtiConversation(
        string $message,
        array $history = [],
        ?array $profileContext = null,
        ?string $feedback = null
    ): ConversationDto
    {
        $conversation = new ConversationDto();
        $this->pushHistoryToConversation($conversation, $history);

        $systemPrompt = "
            You are an expert MBTI and Career Guidance Advisor AI for Vietnamese students.

            Your core mission is to analyze the relationship between the student's MBTI personality, their academic performance (GPA and Subjects), and their career goals to provide highly customized advice.

            Crucial Analysis Framework:
            1. Cross-reference 'MBTI Type' with 'Work Style' to understand their core behavior.
            2. Compare 'Favorite/Weak Subjects' and 'GPA/Score' with their 'Target Major' or 'Career Goal'.
            3. Based on their strong subjects, logically infer their potential exam blocks (Khối xét tuyển như A00, A01, D01...) and suggest 2-3 specific, realistic Universities in Vietnam that match their academic performance tier.

            Strict Output Rules:
            - OUTPUT LANGUAGE: You MUST write the entire response in Vietnamese (Tiếng Việt). Never reply in English.
            - Do not give generic advice. Always weave specific details from the Student Profile into your response (e.g., 'Vì bạn có thế mạnh môn Toán nhưng điểm số hiện tại là...').
            - Provide specific names of Vietnamese universities based on their score range.
            - Be practical, empathetic, and supportive.
            - Keep answers well-structured, using clear bullet points, and concise.
        ";

        $profilePrompt = '';

        if ($profileContext) {
            $favoriteSubjects = !empty($profileContext['favorite_subjects']) ? implode(', ', $profileContext['favorite_subjects']) : 'Not updated';
            $weakSubjects = !empty($profileContext['weak_subjects']) ? implode(', ', $profileContext['weak_subjects']) : 'Not updated';

            $profilePrompt = "
                Student Profile:
                - MBTI Type: " . ($profileContext['mbti_type'] ?? 'Unknown') . "
                - GPA/Score: " . ($profileContext['score'] ?? 'Not updated') . " / 10
                - Favorite Subjects: {$favoriteSubjects}
                - Weak Subjects: {$weakSubjects}
                - Career Goal: " . ($profileContext['career_goal'] ?? 'Unknown') . "
                - Target Major: " . ($profileContext['target_major'] ?? 'Unknown') . "
                - Work Style: " . ($profileContext['work_style'] ?? 'Unknown') . "

                Use this profile to personalize recommendations.
            ";
        }

        if ($feedback) {
            $conversation->pushUserMessage("
            Previous answer was rejected:
            {$feedback}

            Please answer again more accurately.
        ");
        }

        $conversation->setSystemPrompt($systemPrompt);

        $conversation->pushUserMessage("
            {$profilePrompt}

            User question:
            {$message}
        ");

        return $conversation;
    }

    public function extractMetadataMessageConversation(string $message): ConversationDto
    {
        $conversation = new ConversationDto();

        $systemPrompt = $this->buildPromptExtractMetadata($message);
        $conversation->setSystemPrompt($systemPrompt);
        $conversation->pushUserMessage($message);

        return $conversation;
    }

    public function rewriteMessageConversation(string $message, array $metadata): ConversationDto
    {
        $conversation = new ConversationDto();

        $systemPrompt = $this->buildPromptRewrite($message, $metadata);
        $conversation->setSystemPrompt($systemPrompt);
        $conversation->pushUserMessage($message);

        return $conversation;
    }

    public function buildScoreEmbeddingText(Score $score): string
    {
        $contactInfo = [];
        if ($score->school->address) {
            $contactInfo[] = "Địa chỉ: {$score->school->address}.";
        }
        if ($score->school->phone) {
            $contactInfo[] = "Điện thoại: {$score->school->phone}.";
        }
        if ($score->school->website) {
            $contactInfo[] = "Website: {$score->school->website}.";
        }

        $contactText = !empty($contactInfo)
            ? " Thông tin liên hệ của trường: " . implode(' ', $contactInfo)
            : "";

        return trim(sprintf(
            "Trường %s tuyển sinh ngành %s năm %s có điểm chuẩn là %s. "
            . "Khối xét tuyển: %s. Hệ đào tạo: %s. %s%s",
            $score->school->name,
            $score->major->name,
            $score->year,
            $score->score,
            $score->block ?? 'không rõ',
            $score->level ?? 'chính quy',
            $score->note ? "Ghi chú: {$score->note}." : "",
            $contactText
        ));
    }

    public function verifyAnswerConversation(string $question, string $answer, array $documents, string $mode): ConversationDto
    {
        $conversation = new ConversationDto();
        $systemPrompt = $this->buildPromptVerifyAnswer();
        $conversation->setSystemPrompt($systemPrompt);

        // Chỉ lọc lấy nội dung text thuần túy của các documents để gửi cho AI verify
        $cleanedDocs = collect($documents)->map(function ($doc) {
            return is_array($doc) ? ($doc['content'] ?? $doc['text'] ?? '') : ($doc->content ?? $doc->text ?? '');
        })->filter()->values()->toArray();

        $conversation->pushUserMessage(json_encode([
            'question' => $question,
            'answer' => $answer,
            'documents' => $cleanedDocs,
            'mode' => $mode,
        ], JSON_UNESCAPED_UNICODE));

        return $conversation;
    }

    private function buildProfileContextAndStatus(?array $profileContext): array
    {
        if (!$profileContext) {
            return ['', true];
        }

        $hasTargetMajor = !empty($profileContext['target_major']) && !in_array($profileContext['target_major'], ['Unknown', 'Not updated']);
        $hasCareerGoal  = !empty($profileContext['career_goal']) && !in_array($profileContext['career_goal'], ['Unknown', 'Not updated']);
        $hasScore = !empty($profileContext['score']) && !in_array($profileContext['score'], ['Not updated', 'Unknown']);
        $hasSubjects = !empty($profileContext['favorite_subjects']);

        $shouldSuggestUpdate = (!$hasTargetMajor && !$hasCareerGoal && !$hasScore && !$hasSubjects);

        $favoriteSubjects = $hasSubjects ? implode(', ', $profileContext['favorite_subjects']) : 'Not updated';
        $weakSubjects     = !empty($profileContext['weak_subjects']) ? implode(', ', $profileContext['weak_subjects']) : 'Not updated';

        $profilePrompt = "
            Student Profile:
            - MBTI Type: " . ($profileContext['mbti_type'] ?? 'Not updated') . "
            - GPA/Score: " . ($profileContext['score'] ?? 'Not updated') . " / 10
            - Favorite Subjects: {$favoriteSubjects}
            - Weak Subjects: {$weakSubjects}
            - Career Goal: " . ($profileContext['career_goal'] ?? 'Not updated') . "
            - Target Major: " . ($profileContext['target_major'] ?? 'Not updated') . "
            - Work Style: " . ($profileContext['work_style'] ?? 'Not updated') . "

            Use this profile to personalize recommendations.
        ";

        return [$profilePrompt, $shouldSuggestUpdate];
    }

    private function buildAiPromptClassifyIntent(): string
    {
        $template = $this->aiPromptTemplateRepository->findByType(AIPromptTemplateEnum::CLASSIFY_INTENT);

        if (!$template) {
            throw new \RuntimeException('Classify Intent template not found');
        }

        return $template->prompt;
    }

    private function buildAiPromptAdvisor(): string
    {
        $template = $this->aiPromptTemplateRepository->findByType(AIPromptTemplateEnum::UNIVERSITY_ADVISOR);

        if (!$template) {
            throw new \RuntimeException('Classify Intent template not found');
        }

        return $template->prompt;
    }

    private function buildPromptExtractMetadata(string $query): string
    {
        $template = $this->aiPromptTemplateRepository->findByType(AIPromptTemplateEnum::EXTRACT_METADATA_QUERY);

        if (!$template) {
            throw new \RuntimeException('Extract Metadata Query template not found');
        }

        return str_replace('{user_message}', $query, $template->prompt);
    }

    private function buildPromptRewrite(string $query, array $metadata): string
    {
        $template = $this->aiPromptTemplateRepository->findByType(AIPromptTemplateEnum::REWRITE_QUERY);

        if (!$template) {
            throw new \RuntimeException('Rewrite Query template not found');
        }

        $prompt = $template->prompt;

        $search = ['{user_message}', '{school}', '{major}', '{year}'];
        $replace = [$query, $metadata['school'] ?? 'None', $metadata['major'] ?? 'None', $metadata['year'] ?? 'None'];

        return str_replace($search, $replace, $prompt);
    }

    private function buildPromptVerifyAnswer(): string
    {
        $template = $this->aiPromptTemplateRepository->findByType(AIPromptTemplateEnum::VERIFY_ANSWER);

        if (!$template) {
            throw new \RuntimeException('Verify Answer template not found');
        }

        return $template->prompt;
    }

    private function pushHistoryToConversation(ConversationDto $conversation, array $history): void
    {
        foreach ($history as $msg) {
            $roleValue = $msg->role instanceof \BackedEnum ? $msg->role->value : $msg->role;
            $userRoleValue = RoleEnum::USER instanceof \BackedEnum ? RoleEnum::USER->value : RoleEnum::USER;

            if ($roleValue === $userRoleValue) {
                $conversation->pushUserMessage($msg->message);
            } else {
                if (!empty($msg->message)) {
                    $conversation->pushAssistantMessage($msg->message);
                }
            }
        }
    }
}
