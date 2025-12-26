<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PlatformDemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting platform demo data seeding...');

        // 1. Create Demo Users
        $this->seedUsers();
        
        // 2. Create Demo Companies
        $this->seedCompanies();
        
        // 3. Create Demo Projects
        $this->seedProjects();
        
        // 4. Create Demo Translations
        $this->seedTranslations();
        
        // 5. Create Pricing Plans
        $this->seedPricingPlans();
        
        // 6. Create Contact Messages
        $this->seedContactMessages();
        
        // 7. Create Job Postings
        $this->seedJobPostings();
        
        $this->command->info('✅ Platform demo data seeded successfully!');
    }

    private function seedUsers(): void
    {
        $this->command->info('Creating demo users...');
        
        $users = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'language' => 'ar',
                'email_verified_at' => now(),
                'created_at' => now()->subDays(30),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'language' => 'en',
                'email_verified_at' => now(),
                'created_at' => now()->subDays(25),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mohammed Ali',
                'email' => 'mohammed@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'language' => 'ar',
                'email_verified_at' => now(),
                'created_at' => now()->subDays(20),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emily Chen',
                'email' => 'emily@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'language' => 'en',
                'email_verified_at' => now(),
                'created_at' => now()->subDays(15),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            if (!DB::table('users')->where('email', $user['email'])->exists()) {
                DB::table('users')->insert($user);
            }
        }
        
        $this->command->info('✓ Created ' . count($users) . ' demo users');
    }

    private function seedCompanies(): void
    {
        $this->command->info('Creating demo companies...');
        
        $companies = [
            [
                'name' => 'TechGlobal Solutions',
                'domain' => 'techglobal.example.com',
                'status' => 'active',
                'member_count' => 25,
                'created_at' => now()->subDays(60),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arab Digital Media',
                'domain' => 'arabdigital.example.com',
                'status' => 'active',
                'member_count' => 15,
                'created_at' => now()->subDays(50),
                'updated_at' => now(),
            ],
            [
                'name' => 'EuroTrans International',
                'domain' => 'eurotrans.example.com',
                'status' => 'active',
                'member_count' => 30,
                'created_at' => now()->subDays(40),
                'updated_at' => now(),
            ],
        ];

        foreach ($companies as $company) {
            if (!DB::table('companies')->where('domain', $company['domain'])->exists()) {
                DB::table('companies')->insert($company);
            }
        }
        
        $this->command->info('✓ Created ' . count($companies) . ' demo companies');
    }

    private function seedProjects(): void
    {
        $this->command->info('Creating demo projects...');
        
        $users = DB::table('users')->where('role', 'user')->pluck('id')->toArray();
        
        if (empty($users)) {
            $this->command->warn('⚠ No users found to assign projects');
            return;
        }

        $projects = [
            [
                'owner_id' => $users[array_rand($users)],
                'name' => 'Website Localization Project',
                'description' => 'Translate entire website from English to Arabic',
                'created_at' => now()->subDays(15),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $users[array_rand($users)],
                'name' => 'Mobile App Translation',
                'description' => 'Translate mobile app UI strings',
                'created_at' => now()->subDays(10),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $users[array_rand($users)],
                'name' => 'Marketing Materials Translation',
                'description' => 'Translate marketing content for Middle East campaign',
                'created_at' => now()->subDays(7),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $users[array_rand($users)],
                'name' => 'Technical Documentation',
                'description' => 'Translate product documentation to German',
                'created_at' => now()->subDays(30),
                'updated_at' => now(),
            ],
        ];

        foreach ($projects as $project) {
            DB::table('projects')->insert($project);
        }
        
        $this->command->info('✓ Created ' . count($projects) . ' demo projects');
    }

    private function seedTranslations(): void
    {
        $this->command->info('Creating demo translations...');
        
        $users = DB::table('users')->where('role', 'user')->pluck('id')->toArray();
        
        if (empty($users)) {
            $this->command->warn('⚠ No users found to add translations');
            return;
        }

        $sampleTexts = [
            ['en' => 'Welcome to our platform', 'ar' => 'مرحباً بك في منصتنا'],
            ['en' => 'Get started today', 'ar' => 'ابدأ اليوم'],
            ['en' => 'Contact us for more information', 'ar' => 'اتصل بنا لمزيد من المعلومات'],
            ['en' => 'Our services are available worldwide', 'ar' => 'خدماتنا متاحة في جميع أنحاء العالم'],
            ['en' => 'Professional translation services', 'ar' => 'خدمات ترجمة احترافية'],
            ['en' => 'Fast and accurate translations', 'ar' => 'ترجمات سريعة ودقيقة'],
            ['en' => 'Trusted by thousands of customers', 'ar' => 'موثوق به من قبل آلاف العملاء'],
            ['en' => 'Quality guaranteed', 'ar' => 'جودة مضمونة'],
            ['en' => 'Available 24/7', 'ar' => 'متاح على مدار الساعة'],
            ['en' => 'Competitive pricing', 'ar' => 'أسعار تنافسية'],
        ];

        $translationsInserted = 0;
        foreach ($users as $userId) {
            foreach ($sampleTexts as $text) {
                DB::table('translations')->insert([
                    'user_id' => $userId,
                    'source_text' => $text['en'],
                    'translated_text' => $text['ar'],
                    'source_language' => 'en',
                    'target_language' => 'ar',
                    'status' => 'success',
                    'type' => 'text',
                    'tokens_in' => strlen($text['en']),
                    'tokens_out' => mb_strlen($text['ar']),
                    'total_tokens' => strlen($text['en']) + mb_strlen($text['ar']),
                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now(),
                ]);
                $translationsInserted++;
            }
        }
        
        $this->command->info('✓ Created ' . $translationsInserted . ' demo translations');
    }

    private function seedPricingPlans(): void
    {
        $this->command->info('Creating pricing plans...');
        
        if (DB::table('pricing_plans')->count() > 0) {
            $this->command->info('⚠ Pricing plans already exist, skipping...');
            return;
        }

        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for trying out the platform',
                'price' => 0.00,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '1,000 characters/month',
                    'Basic translations',
                    'Community support',
                    '3 languages',
                ]),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'max_projects' => 1,
                'max_pages' => 10,
                'max_translations' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Great for small businesses',
                'price' => 29.99,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '50,000 characters/month',
                    'AI-powered translations',
                    'Email support',
                    '10 languages',
                    'Brand voice',
                ]),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'max_projects' => 5,
                'max_pages' => 50,
                'max_translations' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing teams',
                'price' => 99.99,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'features' => json_encode([
                    '200,000 characters/month',
                    'Cultural AI engine',
                    'Priority support',
                    '20 languages',
                    'Custom brand voices',
                    'API access',
                    'Webhooks',
                ]),
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'max_projects' => 20,
                'max_pages' => 200,
                'max_translations' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations',
                'price' => 299.99,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'features' => json_encode([
                    'Unlimited characters',
                    'Dedicated cultural AI',
                    '24/7 premium support',
                    'All languages',
                    'Custom integrations',
                    'Advanced API',
                    'SLA guarantee',
                    'Dedicated account manager',
                ]),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'max_projects' => null,
                'max_pages' => null,
                'max_translations' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('pricing_plans')->insert($plan);
        }
        
        $this->command->info('✓ Created ' . count($plans) . ' pricing plans');
    }

    private function seedContactMessages(): void
    {
        $this->command->info('Creating contact messages...');
        
        $messages = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'subject' => 'Question about Enterprise plan',
                'message' => 'I would like to know more about your Enterprise plan features and pricing.',
                'phone' => '+1-555-0101',
                'company' => 'TechCorp Inc.',
                'status' => 'new',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'name' => 'فاطمة أحمد',
                'email' => 'fatima@example.com',
                'subject' => 'استفسار عن الترجمة للعربية',
                'message' => 'هل تدعم المنصة الترجمة من الإنجليزية إلى العربية مع الحفاظ على السياق الثقافي؟',
                'phone' => '+971-50-123-4567',
                'company' => 'شركة الابتكار الرقمي',
                'status' => 'read',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'subject' => 'API Integration Support',
                'message' => 'We need help integrating your translation API with our e-commerce platform.',
                'phone' => '+34-91-234-5678',
                'company' => 'Tienda Online SL',
                'status' => 'replied',
                'admin_notes' => 'Sent API documentation and integration guide.',
                'replied_at' => now()->subDays(1),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($messages as $message) {
            DB::table('contact_messages')->insert($message);
        }
        
        $this->command->info('✓ Created ' . count($messages) . ' contact messages');
    }

    private function seedJobPostings(): void
    {
        $this->command->info('Creating job postings...');
        
        $jobs = [
            [
                'title' => 'Senior Full Stack Developer',
                'slug' => 'senior-full-stack-developer',
                'description' => 'We are looking for an experienced Full Stack Developer to join our team.',
                'requirements' => json_encode([
                    '5+ years of experience in web development',
                    'Strong knowledge of Laravel and Vue.js',
                    'Experience with RESTful APIs',
                    'Knowledge of database design',
                    'Experience with cloud platforms',
                ]),
                'responsibilities' => json_encode([
                    'Develop and maintain platform features',
                    'Write clean, maintainable code',
                    'Collaborate with product team',
                    'Participate in code reviews',
                ]),
                'type' => 'full-time',
                'location' => 'Remote',
                'salary_min' => 80000.00,
                'salary_max' => 120000.00,
                'currency' => 'USD',
                'benefits' => json_encode([
                    'Competitive salary',
                    'Health insurance',
                    'Flexible working hours',
                    'Professional development budget',
                ]),
                'status' => 'published',
                'application_deadline' => now()->addDays(30),
                'contact_email' => 'careers@culturaltranslate.com',
                'created_at' => now()->subDays(10),
                'updated_at' => now(),
            ],
            [
                'title' => 'Arabic Content Specialist',
                'slug' => 'arabic-content-specialist',
                'description' => 'Join our team as an Arabic Content Specialist.',
                'requirements' => json_encode([
                    'Native Arabic speaker',
                    'Excellent English proficiency',
                    'Experience in translation',
                    'Understanding of cultural nuances',
                ]),
                'responsibilities' => json_encode([
                    'Review and improve Arabic translations',
                    'Provide cultural context feedback',
                    'Test translation features',
                    'Create content guidelines',
                ]),
                'type' => 'full-time',
                'location' => 'Dubai, UAE / Remote',
                'salary_min' => 50000.00,
                'salary_max' => 70000.00,
                'currency' => 'USD',
                'benefits' => json_encode([
                    'Competitive salary',
                    'Work visa sponsorship',
                    'Health insurance',
                    'Annual flight tickets',
                ]),
                'status' => 'published',
                'application_deadline' => now()->addDays(45),
                'contact_email' => 'careers@culturaltranslate.com',
                'created_at' => now()->subDays(5),
                'updated_at' => now(),
            ],
        ];

        foreach ($jobs as $job) {
            if (!DB::table('job_postings')->where('slug', $job['slug'])->exists()) {
                DB::table('job_postings')->insert($job);
            }
        }
        
        $this->command->info('✓ Created ' . count($jobs) . ' job postings');
    }
}
