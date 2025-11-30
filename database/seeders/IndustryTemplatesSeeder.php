<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustryTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            // 1. Tourism & Hospitality
            [
                'industry_code' => 'tourism',
                'industry_name' => 'السياحة والضيافة',
                'industry_name_en' => 'Tourism & Hospitality',
                'description' => 'قطاع السياحة والفنادق والضيافة',
                'common_terms' => json_encode([
                    'booking' => 'حجز',
                    'reservation' => 'حجز مسبق',
                    'check-in' => 'تسجيل الدخول',
                    'check-out' => 'تسجيل الخروج',
                    'amenities' => 'مرافق',
                    'hospitality' => 'ضيافة',
                ]),
                'glossary' => json_encode([
                    'suite' => 'جناح',
                    'concierge' => 'موظف الاستقبال',
                    'complimentary' => 'مجاني',
                    'all-inclusive' => 'شامل كل شيء',
                ]),
                'preferred_tones' => json_encode(['friendly', 'professional', 'warm']),
                'cultural_considerations' => json_encode([
                    'emphasize_hospitality',
                    'highlight_comfort',
                    'use_welcoming_language',
                    'respect_cultural_sensitivities',
                ]),
                'content_types' => json_encode(['hotel_descriptions', 'booking_confirmations', 'travel_guides', 'promotional_materials']),
                'system_prompt' => 'You are a tourism and hospitality expert. Use warm, welcoming language that makes guests feel valued. Highlight unique experiences and comfort. Be descriptive and inviting.',
                'translation_rules' => json_encode([
                    'maintain_welcoming_tone',
                    'emphasize_unique_features',
                    'use_sensory_descriptions',
                    'highlight_local_culture',
                ]),
                'seo_keywords' => json_encode(['luxury hotel', 'best destination', 'travel guide', 'vacation', 'resort']),
                'marketing_phrases' => json_encode([
                    'Experience unforgettable moments',
                    'Your perfect getaway awaits',
                    'Discover paradise',
                    'Where memories are made',
                ]),
                'icon' => '✈️',
                'color' => '#0ea5e9',
                'is_active' => true,
                'priority' => 1,
            ],
            
            // 2. E-commerce & Retail
            [
                'industry_code' => 'ecommerce',
                'industry_name' => 'التجارة الإلكترونية',
                'industry_name_en' => 'E-commerce & Retail',
                'description' => 'قطاع التجارة الإلكترونية والبيع بالتجزئة',
                'common_terms' => json_encode([
                    'cart' => 'سلة التسوق',
                    'checkout' => 'إتمام الشراء',
                    'shipping' => 'الشحن',
                    'delivery' => 'التوصيل',
                    'return policy' => 'سياسة الإرجاع',
                    'product' => 'منتج',
                ]),
                'glossary' => json_encode([
                    'add to cart' => 'أضف إلى السلة',
                    'wishlist' => 'قائمة الرغبات',
                    'out of stock' => 'غير متوفر',
                    'free shipping' => 'شحن مجاني',
                ]),
                'preferred_tones' => json_encode(['marketing', 'friendly', 'professional']),
                'cultural_considerations' => json_encode([
                    'highlight_value',
                    'build_trust',
                    'emphasize_quality',
                    'clear_pricing',
                ]),
                'content_types' => json_encode(['product_descriptions', 'category_pages', 'checkout_process', 'promotional_emails']),
                'system_prompt' => 'You are an e-commerce expert. Write compelling product descriptions that sell. Highlight benefits, features, and value. Use persuasive language while being honest and clear.',
                'translation_rules' => json_encode([
                    'focus_on_benefits',
                    'use_action_verbs',
                    'create_urgency',
                    'highlight_unique_selling_points',
                ]),
                'seo_keywords' => json_encode(['buy online', 'best price', 'free shipping', 'discount', 'sale']),
                'marketing_phrases' => json_encode([
                    'Shop now and save',
                    'Limited time offer',
                    'Get yours today',
                    'Free shipping on all orders',
                ]),
                'icon' => '🛒',
                'color' => '#f59e0b',
                'is_active' => true,
                'priority' => 2,
            ],
            
            // 3. Technology & Software
            [
                'industry_code' => 'technology',
                'industry_name' => 'التكنولوجيا والبرمجيات',
                'industry_name_en' => 'Technology & Software',
                'description' => 'قطاع التكنولوجيا وتطوير البرمجيات',
                'common_terms' => json_encode([
                    'software' => 'برمجيات',
                    'application' => 'تطبيق',
                    'platform' => 'منصة',
                    'integration' => 'تكامل',
                    'API' => 'واجهة برمجية',
                    'cloud' => 'سحابة',
                ]),
                'glossary' => json_encode([
                    'deployment' => 'نشر',
                    'scalability' => 'قابلية التوسع',
                    'user interface' => 'واجهة المستخدم',
                    'dashboard' => 'لوحة التحكم',
                ]),
                'preferred_tones' => json_encode(['technical', 'professional', 'authoritative']),
                'cultural_considerations' => json_encode([
                    'be_precise',
                    'use_technical_terms',
                    'provide_clear_instructions',
                    'focus_on_functionality',
                ]),
                'content_types' => json_encode(['documentation', 'user_guides', 'api_docs', 'release_notes', 'technical_specs']),
                'system_prompt' => 'You are a technology expert. Use precise technical language. Be clear and accurate. Focus on functionality and specifications. Maintain professional tone.',
                'translation_rules' => json_encode([
                    'preserve_technical_terms',
                    'maintain_accuracy',
                    'use_consistent_terminology',
                    'be_concise_and_clear',
                ]),
                'seo_keywords' => json_encode(['software solution', 'cloud platform', 'API integration', 'tech stack']),
                'marketing_phrases' => json_encode([
                    'Powerful technology, simple to use',
                    'Built for developers',
                    'Enterprise-grade solution',
                    'Seamless integration',
                ]),
                'icon' => '💻',
                'color' => '#6366f1',
                'is_active' => true,
                'priority' => 3,
            ],
            
            // 4. Healthcare & Medical
            [
                'industry_code' => 'healthcare',
                'industry_name' => 'الرعاية الصحية',
                'industry_name_en' => 'Healthcare & Medical',
                'description' => 'قطاع الرعاية الصحية والخدمات الطبية',
                'common_terms' => json_encode([
                    'patient' => 'مريض',
                    'treatment' => 'علاج',
                    'diagnosis' => 'تشخيص',
                    'prescription' => 'وصفة طبية',
                    'appointment' => 'موعد',
                    'symptoms' => 'أعراض',
                ]),
                'glossary' => json_encode([
                    'consultation' => 'استشارة',
                    'medical history' => 'التاريخ الطبي',
                    'side effects' => 'آثار جانبية',
                    'follow-up' => 'متابعة',
                ]),
                'preferred_tones' => json_encode(['empathetic', 'professional', 'authoritative']),
                'cultural_considerations' => json_encode([
                    'be_compassionate',
                    'maintain_privacy',
                    'use_clear_language',
                    'avoid_medical_jargon',
                ]),
                'content_types' => json_encode(['patient_information', 'medical_reports', 'health_tips', 'appointment_reminders']),
                'system_prompt' => 'You are a healthcare communication expert. Use clear, compassionate language. Be accurate with medical information. Show empathy and care. Maintain patient privacy.',
                'translation_rules' => json_encode([
                    'use_plain_language',
                    'be_accurate_with_medical_terms',
                    'show_empathy',
                    'maintain_professional_tone',
                ]),
                'seo_keywords' => json_encode(['healthcare services', 'medical care', 'health tips', 'doctor consultation']),
                'marketing_phrases' => json_encode([
                    'Your health is our priority',
                    'Expert care you can trust',
                    'Compassionate healthcare',
                    'Here for your wellbeing',
                ]),
                'icon' => '🏥',
                'color' => '#10b981',
                'is_active' => true,
                'priority' => 4,
            ],
            
            // 5. Education & Training
            [
                'industry_code' => 'education',
                'industry_name' => 'التعليم والتدريب',
                'industry_name_en' => 'Education & Training',
                'description' => 'قطاع التعليم والتدريب والدورات',
                'common_terms' => json_encode([
                    'course' => 'دورة',
                    'curriculum' => 'منهج',
                    'student' => 'طالب',
                    'instructor' => 'مدرب',
                    'certificate' => 'شهادة',
                    'enrollment' => 'تسجيل',
                ]),
                'glossary' => json_encode([
                    'learning outcomes' => 'نتائج التعلم',
                    'assessment' => 'تقييم',
                    'module' => 'وحدة تعليمية',
                    'syllabus' => 'المنهج الدراسي',
                ]),
                'preferred_tones' => json_encode(['professional', 'friendly', 'authoritative']),
                'cultural_considerations' => json_encode([
                    'be_encouraging',
                    'use_clear_explanations',
                    'support_learning',
                    'be_inclusive',
                ]),
                'content_types' => json_encode(['course_descriptions', 'learning_materials', 'announcements', 'certificates']),
                'system_prompt' => 'You are an education expert. Use clear, encouraging language. Make complex topics accessible. Be supportive and motivating. Focus on learning outcomes.',
                'translation_rules' => json_encode([
                    'simplify_complex_concepts',
                    'use_examples',
                    'be_encouraging',
                    'maintain_academic_tone',
                ]),
                'seo_keywords' => json_encode(['online course', 'learn', 'training program', 'certification', 'education']),
                'marketing_phrases' => json_encode([
                    'Learn from experts',
                    'Advance your career',
                    'Master new skills',
                    'Transform your future',
                ]),
                'icon' => '📚',
                'color' => '#8b5cf6',
                'is_active' => true,
                'priority' => 5,
            ],
        ];

        foreach ($industries as $industry) {
            DB::table('industry_templates')->updateOrInsert(
                ['industry_code' => $industry['industry_code']],
                array_merge($industry, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
