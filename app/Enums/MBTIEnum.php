<?php

namespace App\Enums;

enum MBTIEnum: string
{
    /**
     * =========================
     * SJ - Guardian
     * =========================
     */
    case ISTJ = 'ISTJ';
    case ISFJ = 'ISFJ';
    case ESTJ = 'ESTJ';
    case ESFJ = 'ESFJ';

    /**
     * =========================
     * SP - Explorer
     * =========================
     */
    case ISTP = 'ISTP';
    case ISFP = 'ISFP';
    case ESTP = 'ESTP';
    case ESFP = 'ESFP';

    /**
     * =========================
     * NF - Idealist
     * =========================
     */
    case INFJ = 'INFJ';
    case INFP = 'INFP';
    case ENFJ = 'ENFJ';
    case ENFP = 'ENFP';

    /**
     * =========================
     * NT - Analyst
     * =========================
     */
    case INTJ = 'INTJ';
    case INTP = 'INTP';
    case ENTJ = 'ENTJ';
    case ENTP = 'ENTP';

    public function vietnameseLabel(): string
    {
        return match ($this) {
            self::ISTJ => 'Người trách nhiệm',
            self::ISFJ => 'Người nuôi dưỡng',
            self::ESTJ => 'Người giám hộ',
            self::ESFJ => 'Người quan tâm',

            self::ISTP => 'Nhà kỹ thuật',
            self::ISFP => 'Người nghệ sĩ',
            self::ESTP => 'Người thực thi',
            self::ESFP => 'Người trình diễn',

            self::INFJ => 'Người che chở',
            self::INFP => 'Người lý tưởng hóa',
            self::ENFJ => 'Người cho đi',
            self::ENFP => 'Người truyền cảm hứng',

            self::INTJ => 'Nhà khoa học',
            self::INTP => 'Nhà tư duy',
            self::ENTJ => 'Nhà điều hành',
            self::ENTP => 'Người nhìn xa',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ISTJ => 'Người thực tế, có trách nhiệm và làm việc theo nguyên tắc.',
            self::ISFJ => 'Người tận tâm, quan tâm người khác và làm việc ổn định.',
            self::ESTJ => 'Người tổ chức tốt, quyết đoán và có tố chất quản lý.',
            self::ESFJ => 'Người hòa đồng, thích hỗ trợ và chăm sóc mọi người.',

            self::ISTP => 'Người thích khám phá kỹ thuật, xử lý vấn đề linh hoạt.',
            self::ISFP => 'Người sáng tạo, yêu cái đẹp và thích tự do.',
            self::ESTP => 'Người năng động, thích hành động và trải nghiệm thực tế.',
            self::ESFP => 'Người hướng ngoại, vui vẻ và thích môi trường sôi động.',

            self::INFJ => 'Người sâu sắc, trực giác tốt và thích giúp đỡ người khác.',
            self::INFP => 'Người giàu lý tưởng, sáng tạo và đồng cảm.',
            self::ENFJ => 'Người truyền cảm hứng và có khả năng kết nối tập thể.',
            self::ENFP => 'Người nhiệt huyết, sáng tạo và thích giao tiếp.',

            self::INTJ => 'Người thiên về tư duy logic, chiến lược và phân tích.',
            self::INTP => 'Người thích suy luận, nghiên cứu và khám phá ý tưởng mới.',
            self::ENTJ => 'Người có khả năng lãnh đạo và định hướng mục tiêu rõ ràng.',
            self::ENTP => 'Người thích tranh luận, đổi mới và nhìn nhận đa góc độ.',
        };
    }

    public function traits(): array
    {
        return match ($this) {
            self::ISTJ => [
                'responsible',
                'organized',
                'practical',
            ],

            self::ISFJ => [
                'supportive',
                'patient',
                'careful',
            ],

            self::ESTJ => [
                'leadership',
                'disciplined',
                'decisive',
            ],

            self::ESFJ => [
                'friendly',
                'caring',
                'team oriented',
            ],

            self::ISTP => [
                'technical',
                'flexible',
                'problem solving',
            ],

            self::ISFP => [
                'creative',
                'artistic',
                'independent',
            ],

            self::ESTP => [
                'energetic',
                'action oriented',
                'adventurous',
            ],

            self::ESFP => [
                'social',
                'enthusiastic',
                'expressive',
            ],

            self::INFJ => [
                'insightful',
                'visionary',
                'helpful',
            ],

            self::INFP => [
                'idealistic',
                'creative',
                'empathetic',
            ],

            self::ENFJ => [
                'empathetic',
                'motivational',
                'communicative',
            ],

            self::ENFP => [
                'creative',
                'energetic',
                'communicative',
            ],

            self::INTJ => [
                'logical',
                'strategic',
                'analytical',
            ],

            self::INTP => [
                'curious',
                'theoretical',
                'innovative',
            ],

            self::ENTJ => [
                'leadership',
                'strategic',
                'goal oriented',
            ],

            self::ENTP => [
                'innovative',
                'debating',
                'adaptable',
            ],
        };
    }

    public function recommendedMajors(): array
    {
        return match ($this) {
            self::ISTJ => [
                'Kế toán',
                'Luật',
                'Quản trị kinh doanh',
            ],

            self::ISFJ => [
                'Điều dưỡng',
                'Sư phạm',
                'Tâm lý học',
            ],

            self::ESTJ => [
                'Quản trị kinh doanh',
                'Tài chính',
                'Quản lý dự án',
            ],

            self::ESFJ => [
                'Quản trị nhân sự',
                'Giáo dục',
                'Quan hệ công chúng',
            ],

            self::ISTP => [
                'Công nghệ thông tin',
                'Kỹ thuật cơ khí',
                'An ninh mạng',
            ],

            self::ISFP => [
                'Thiết kế đồ họa',
                'Kiến trúc',
                'Truyền thông đa phương tiện',
            ],

            self::ESTP => [
                'Marketing',
                'Kinh doanh',
                'Du lịch',
            ],

            self::ESFP => [
                'Truyền thông',
                'Giải trí',
                'Marketing',
            ],

            self::INFJ => [
                'Tâm lý học',
                'Y khoa',
                'Giáo dục',
            ],

            self::INFP => [
                'Văn học',
                'Thiết kế',
                'Tâm lý học',
            ],

            self::ENFJ => [
                'Tâm lý học',
                'Giáo dục',
                'Quản trị nhân sự',
            ],

            self::ENFP => [
                'Marketing',
                'Truyền thông',
                'Quan hệ công chúng',
            ],

            self::INTJ => [
                'Khoa học máy tính',
                'Trí tuệ nhân tạo',
                'Khoa học dữ liệu',
            ],

            self::INTP => [
                'Khoa học máy tính',
                'Toán học',
                'Kỹ thuật phần mềm',
            ],

            self::ENTJ => [
                'Quản trị kinh doanh',
                'Kinh tế',
                'Công nghệ thông tin',
            ],

            self::ENTP => [
                'Khởi nghiệp',
                'Marketing',
                'Công nghệ thông tin',
            ],
        };
    }

    public function toPromptContext(): array
    {
        return [
            'type' => $this->value,
            'label' => $this->vietnameseLabel(),
            'description' => $this->description(),
            'traits' => $this->traits(),
            'recommended_majors' => $this->recommendedMajors(),
        ];
    }
}
