<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobPostingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $jobs = [
            [
                'title' => 'Senior Full Stack Developer',
                'slug' => 'senior-full-stack-developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'description' => 'We are looking for an experienced Full Stack Developer to join our engineering team. You will be responsible for developing and maintaining our translation platform using Laravel, Vue.js, and modern web technologies.',
                'requirements' => json_encode([
                    '5+ years of experience with PHP and Laravel',
                    'Strong knowledge of Vue.js or React',
                    'Experience with RESTful APIs and microservices',
                    'Understanding of database design and optimization',
                    'Experience with cloud platforms (AWS, GCP)',
                    'Excellent problem-solving skills',
                ]),
                'responsibilities' => json_encode([
                    'Develop and maintain web applications',
                    'Write clean, maintainable code',
                    'Collaborate with cross-functional teams',
                    'Participate in code reviews',
                    'Optimize application performance',
                ]),
                'benefits' => json_encode([
                    'Competitive salary',
                    'Remote work flexibility',
                    'Health insurance',
                    'Professional development budget',
                    '20 days paid vacation',
                ]),
                'salary_range' => '€80,000 - €120,000',
                'positions_available' => 2,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'AI/ML Engineer',
                'slug' => 'ai-ml-engineer',
                'department' => 'Engineering',
                'location' => 'Remote / Hybrid',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'description' => 'Join our AI team to develop cutting-edge translation models and improve our cultural adaptation algorithms. You will work with state-of-the-art NLP technologies.',
                'requirements' => json_encode([
                    'Masters or PhD in Computer Science, AI, or related field',
                    '3+ years experience with machine learning',
                    'Strong Python programming skills',
                    'Experience with NLP and transformer models',
                    'Familiarity with TensorFlow or PyTorch',
                    'Understanding of translation and localization',
                ]),
                'responsibilities' => json_encode([
                    'Develop and train translation models',
                    'Improve cultural adaptation algorithms',
                    'Research latest NLP techniques',
                    'Optimize model performance',
                    'Collaborate with product team',
                ]),
                'benefits' => json_encode([
                    'Competitive salary €70k - €110k',
                    'Flexible working hours',
                    'Research and conference budget',
                    'Latest hardware and tools',
                    'Learning opportunities',
                ]),
                'salary_range' => '€70,000 - €110,000',
                'positions_available' => 1,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Product Manager',
                'slug' => 'product-manager',
                'department' => 'Product',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'description' => 'We are seeking a Product Manager to lead our translation platform development. You will define product strategy, prioritize features, and work closely with engineering and design teams.',
                'requirements' => json_encode([
                    '3+ years of product management experience',
                    'Experience in SaaS or translation industry',
                    'Strong analytical and communication skills',
                    'Ability to work with technical teams',
                    'Data-driven decision making',
                    'Fluency in English',
                ]),
                'responsibilities' => json_encode([
                    'Define product roadmap and strategy',
                    'Gather and prioritize requirements',
                    'Work with engineering on implementation',
                    'Analyze user feedback and metrics',
                    'Launch and iterate on features',
                ]),
                'benefits' => json_encode([
                    'Competitive salary €60k - €90k',
                    'Remote-first culture',
                    'Equity options',
                    'Health and wellness benefits',
                    'Team retreats',
                ]),
                'salary_range' => '€60,000 - €90,000',
                'positions_available' => 1,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Customer Success Manager',
                'slug' => 'customer-success-manager',
                'department' => 'Customer Success',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'description' => 'Help our enterprise customers succeed with CulturalTranslate. You will onboard new clients, provide training, and ensure they get maximum value from our platform.',
                'requirements' => json_encode([
                    '2+ years in customer success or account management',
                    'Experience with SaaS products',
                    'Excellent communication skills',
                    'Multilingual abilities (preferred)',
                    'Problem-solving mindset',
                    'Technical aptitude',
                ]),
                'responsibilities' => json_encode([
                    'Onboard new enterprise customers',
                    'Conduct training sessions',
                    'Monitor customer health metrics',
                    'Identify upsell opportunities',
                    'Advocate for customer needs',
                ]),
                'benefits' => json_encode([
                    'Base salary €45k - €65k',
                    'Performance bonuses',
                    'Remote work setup allowance',
                    'Career growth opportunities',
                    'Flexible schedule',
                ]),
                'salary_range' => '€45,000 - €65,000',
                'positions_available' => 1,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'UX/UI Designer',
                'slug' => 'ux-ui-designer',
                'department' => 'Design',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'description' => 'Design beautiful, intuitive interfaces for our translation platform. You will create user flows, wireframes, and high-fidelity mockups that delight our users.',
                'requirements' => json_encode([
                    '3+ years of UX/UI design experience',
                    'Strong portfolio of web applications',
                    'Proficiency in Figma or Sketch',
                    'Understanding of web technologies',
                    'User research and testing experience',
                    'Attention to detail',
                ]),
                'responsibilities' => json_encode([
                    'Design user interfaces and experiences',
                    'Create wireframes and prototypes',
                    'Conduct user research',
                    'Collaborate with developers',
                    'Maintain design system',
                ]),
                'benefits' => json_encode([
                    'Salary €50k - €75k',
                    'Remote work flexibility',
                    'Design tools and resources',
                    'Conference attendance',
                    'Creative freedom',
                ]),
                'salary_range' => '€50,000 - €75,000',
                'positions_available' => 1,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('job_postings')->insert($jobs);
        
        $this->command->info('Created 5 job postings successfully!');
    }
}
