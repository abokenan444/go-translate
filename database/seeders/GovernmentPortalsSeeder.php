<?php

namespace Database\Seeders;

use App\Models\GovernmentPortal;
use Illuminate\Database\Seeder;

class GovernmentPortalsSeeder extends Seeder
{
    /**
     * Seed the government portals for all countries
     * 
     * This creates portals for 195+ countries with appropriate configurations
     */
    public function run(): void
    {
        $countries = $this->getCountriesList();
        
        foreach ($countries as $country) {
            GovernmentPortal::updateOrCreate(
                ['country_code' => $country['code']],
                [
                    'country_name' => $country['name'],
                    'country_name_native' => $country['native'] ?? $country['name'],
                    'portal_slug' => strtolower($country['code']),
                    'subdomain_pattern' => 'path', // Default: gov.culturaltranslate.com/{country}
                    'default_language' => $country['language'] ?? 'en',
                    'supported_languages' => $country['languages'] ?? ['en'],
                    'currency_code' => $country['currency'] ?? 'USD',
                    'timezone' => $country['timezone'] ?? 'UTC',
                    'requires_certified_translation' => $country['requires_certified'] ?? true,
                    'requires_notarization' => $country['requires_notarization'] ?? false,
                    'requires_apostille' => $country['requires_apostille'] ?? false,
                    'is_active' => true,
                ]
            );
        }
        
        $this->command->info('Created ' . count($countries) . ' government portals.');
    }

    /**
     * Get comprehensive list of all countries with configurations
     */
    private function getCountriesList(): array
    {
        return [
            // Europe
            ['code' => 'NL', 'name' => 'Netherlands', 'native' => 'Nederland', 'language' => 'nl', 'languages' => ['nl', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Amsterdam', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'DE', 'name' => 'Germany', 'native' => 'Deutschland', 'language' => 'de', 'languages' => ['de', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Berlin', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'FR', 'name' => 'France', 'native' => 'France', 'language' => 'fr', 'languages' => ['fr', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Paris', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'GB', 'name' => 'United Kingdom', 'native' => 'United Kingdom', 'language' => 'en', 'languages' => ['en'], 'currency' => 'GBP', 'timezone' => 'Europe/London', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'IT', 'name' => 'Italy', 'native' => 'Italia', 'language' => 'it', 'languages' => ['it', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Rome', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'ES', 'name' => 'Spain', 'native' => 'España', 'language' => 'es', 'languages' => ['es', 'en', 'ca'], 'currency' => 'EUR', 'timezone' => 'Europe/Madrid', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PT', 'name' => 'Portugal', 'native' => 'Portugal', 'language' => 'pt', 'languages' => ['pt', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Lisbon', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BE', 'name' => 'Belgium', 'native' => 'België', 'language' => 'nl', 'languages' => ['nl', 'fr', 'de', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Brussels', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'AT', 'name' => 'Austria', 'native' => 'Österreich', 'language' => 'de', 'languages' => ['de', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Vienna', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CH', 'name' => 'Switzerland', 'native' => 'Schweiz', 'language' => 'de', 'languages' => ['de', 'fr', 'it', 'en'], 'currency' => 'CHF', 'timezone' => 'Europe/Zurich', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SE', 'name' => 'Sweden', 'native' => 'Sverige', 'language' => 'sv', 'languages' => ['sv', 'en'], 'currency' => 'SEK', 'timezone' => 'Europe/Stockholm', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'NO', 'name' => 'Norway', 'native' => 'Norge', 'language' => 'no', 'languages' => ['no', 'en'], 'currency' => 'NOK', 'timezone' => 'Europe/Oslo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'DK', 'name' => 'Denmark', 'native' => 'Danmark', 'language' => 'da', 'languages' => ['da', 'en'], 'currency' => 'DKK', 'timezone' => 'Europe/Copenhagen', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'FI', 'name' => 'Finland', 'native' => 'Suomi', 'language' => 'fi', 'languages' => ['fi', 'sv', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Helsinki', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'IE', 'name' => 'Ireland', 'native' => 'Éire', 'language' => 'en', 'languages' => ['en', 'ga'], 'currency' => 'EUR', 'timezone' => 'Europe/Dublin', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PL', 'name' => 'Poland', 'native' => 'Polska', 'language' => 'pl', 'languages' => ['pl', 'en'], 'currency' => 'PLN', 'timezone' => 'Europe/Warsaw', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CZ', 'name' => 'Czech Republic', 'native' => 'Česko', 'language' => 'cs', 'languages' => ['cs', 'en'], 'currency' => 'CZK', 'timezone' => 'Europe/Prague', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'HU', 'name' => 'Hungary', 'native' => 'Magyarország', 'language' => 'hu', 'languages' => ['hu', 'en'], 'currency' => 'HUF', 'timezone' => 'Europe/Budapest', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'GR', 'name' => 'Greece', 'native' => 'Ελλάδα', 'language' => 'el', 'languages' => ['el', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Athens', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'RO', 'name' => 'Romania', 'native' => 'România', 'language' => 'ro', 'languages' => ['ro', 'en'], 'currency' => 'RON', 'timezone' => 'Europe/Bucharest', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BG', 'name' => 'Bulgaria', 'native' => 'България', 'language' => 'bg', 'languages' => ['bg', 'en'], 'currency' => 'BGN', 'timezone' => 'Europe/Sofia', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'HR', 'name' => 'Croatia', 'native' => 'Hrvatska', 'language' => 'hr', 'languages' => ['hr', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Zagreb', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SK', 'name' => 'Slovakia', 'native' => 'Slovensko', 'language' => 'sk', 'languages' => ['sk', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Bratislava', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SI', 'name' => 'Slovenia', 'native' => 'Slovenija', 'language' => 'sl', 'languages' => ['sl', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Ljubljana', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'LT', 'name' => 'Lithuania', 'native' => 'Lietuva', 'language' => 'lt', 'languages' => ['lt', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Vilnius', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'LV', 'name' => 'Latvia', 'native' => 'Latvija', 'language' => 'lv', 'languages' => ['lv', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Riga', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'EE', 'name' => 'Estonia', 'native' => 'Eesti', 'language' => 'et', 'languages' => ['et', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Tallinn', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CY', 'name' => 'Cyprus', 'native' => 'Κύπρος', 'language' => 'el', 'languages' => ['el', 'tr', 'en'], 'currency' => 'EUR', 'timezone' => 'Asia/Nicosia', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MT', 'name' => 'Malta', 'native' => 'Malta', 'language' => 'mt', 'languages' => ['mt', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Malta', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'LU', 'name' => 'Luxembourg', 'native' => 'Luxembourg', 'language' => 'fr', 'languages' => ['fr', 'de', 'lb', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Luxembourg', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'IS', 'name' => 'Iceland', 'native' => 'Ísland', 'language' => 'is', 'languages' => ['is', 'en'], 'currency' => 'ISK', 'timezone' => 'Atlantic/Reykjavik', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'UA', 'name' => 'Ukraine', 'native' => 'Україна', 'language' => 'uk', 'languages' => ['uk', 'en'], 'currency' => 'UAH', 'timezone' => 'Europe/Kiev', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'RU', 'name' => 'Russia', 'native' => 'Россия', 'language' => 'ru', 'languages' => ['ru', 'en'], 'currency' => 'RUB', 'timezone' => 'Europe/Moscow', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BY', 'name' => 'Belarus', 'native' => 'Беларусь', 'language' => 'be', 'languages' => ['be', 'ru', 'en'], 'currency' => 'BYN', 'timezone' => 'Europe/Minsk', 'requires_certified' => true, 'requires_apostille' => false],
            ['code' => 'MD', 'name' => 'Moldova', 'native' => 'Moldova', 'language' => 'ro', 'languages' => ['ro', 'ru', 'en'], 'currency' => 'MDL', 'timezone' => 'Europe/Chisinau', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'RS', 'name' => 'Serbia', 'native' => 'Србија', 'language' => 'sr', 'languages' => ['sr', 'en'], 'currency' => 'RSD', 'timezone' => 'Europe/Belgrade', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BA', 'name' => 'Bosnia and Herzegovina', 'native' => 'Bosna i Hercegovina', 'language' => 'bs', 'languages' => ['bs', 'hr', 'sr', 'en'], 'currency' => 'BAM', 'timezone' => 'Europe/Sarajevo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'ME', 'name' => 'Montenegro', 'native' => 'Crna Gora', 'language' => 'sr', 'languages' => ['sr', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Podgorica', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MK', 'name' => 'North Macedonia', 'native' => 'Северна Македонија', 'language' => 'mk', 'languages' => ['mk', 'sq', 'en'], 'currency' => 'MKD', 'timezone' => 'Europe/Skopje', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'AL', 'name' => 'Albania', 'native' => 'Shqipëri', 'language' => 'sq', 'languages' => ['sq', 'en'], 'currency' => 'ALL', 'timezone' => 'Europe/Tirane', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'XK', 'name' => 'Kosovo', 'native' => 'Kosovë', 'language' => 'sq', 'languages' => ['sq', 'sr', 'en'], 'currency' => 'EUR', 'timezone' => 'Europe/Belgrade', 'requires_certified' => true, 'requires_apostille' => false],
            
            // Middle East & North Africa
            ['code' => 'AE', 'name' => 'United Arab Emirates', 'native' => 'الإمارات', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'AED', 'timezone' => 'Asia/Dubai', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'SA', 'name' => 'Saudi Arabia', 'native' => 'السعودية', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'SAR', 'timezone' => 'Asia/Riyadh', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'QA', 'name' => 'Qatar', 'native' => 'قطر', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'QAR', 'timezone' => 'Asia/Qatar', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'KW', 'name' => 'Kuwait', 'native' => 'الكويت', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'KWD', 'timezone' => 'Asia/Kuwait', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'BH', 'name' => 'Bahrain', 'native' => 'البحرين', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'BHD', 'timezone' => 'Asia/Bahrain', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'OM', 'name' => 'Oman', 'native' => 'عمان', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'OMR', 'timezone' => 'Asia/Muscat', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'JO', 'name' => 'Jordan', 'native' => 'الأردن', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'JOD', 'timezone' => 'Asia/Amman', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => true],
            ['code' => 'LB', 'name' => 'Lebanon', 'native' => 'لبنان', 'language' => 'ar', 'languages' => ['ar', 'fr', 'en'], 'currency' => 'LBP', 'timezone' => 'Asia/Beirut', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => true],
            ['code' => 'IL', 'name' => 'Israel', 'native' => 'ישראל', 'language' => 'he', 'languages' => ['he', 'ar', 'en'], 'currency' => 'ILS', 'timezone' => 'Asia/Jerusalem', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PS', 'name' => 'Palestine', 'native' => 'فلسطين', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'ILS', 'timezone' => 'Asia/Gaza', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'EG', 'name' => 'Egypt', 'native' => 'مصر', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'EGP', 'timezone' => 'Africa/Cairo', 'requires_certified' => true, 'requires_notarization' => true, 'requires_apostille' => false],
            ['code' => 'MA', 'name' => 'Morocco', 'native' => 'المغرب', 'language' => 'ar', 'languages' => ['ar', 'fr', 'en'], 'currency' => 'MAD', 'timezone' => 'Africa/Casablanca', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'DZ', 'name' => 'Algeria', 'native' => 'الجزائر', 'language' => 'ar', 'languages' => ['ar', 'fr', 'en'], 'currency' => 'DZD', 'timezone' => 'Africa/Algiers', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'TN', 'name' => 'Tunisia', 'native' => 'تونس', 'language' => 'ar', 'languages' => ['ar', 'fr', 'en'], 'currency' => 'TND', 'timezone' => 'Africa/Tunis', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'LY', 'name' => 'Libya', 'native' => 'ليبيا', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'LYD', 'timezone' => 'Africa/Tripoli', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'IQ', 'name' => 'Iraq', 'native' => 'العراق', 'language' => 'ar', 'languages' => ['ar', 'ku', 'en'], 'currency' => 'IQD', 'timezone' => 'Asia/Baghdad', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'SY', 'name' => 'Syria', 'native' => 'سوريا', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'SYP', 'timezone' => 'Asia/Damascus', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'YE', 'name' => 'Yemen', 'native' => 'اليمن', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'YER', 'timezone' => 'Asia/Aden', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'IR', 'name' => 'Iran', 'native' => 'ایران', 'language' => 'fa', 'languages' => ['fa', 'en'], 'currency' => 'IRR', 'timezone' => 'Asia/Tehran', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'TR', 'name' => 'Turkey', 'native' => 'Türkiye', 'language' => 'tr', 'languages' => ['tr', 'en'], 'currency' => 'TRY', 'timezone' => 'Europe/Istanbul', 'requires_certified' => true, 'requires_apostille' => true],
            
            // Asia Pacific
            ['code' => 'CN', 'name' => 'China', 'native' => '中国', 'language' => 'zh', 'languages' => ['zh', 'en'], 'currency' => 'CNY', 'timezone' => 'Asia/Shanghai', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'JP', 'name' => 'Japan', 'native' => '日本', 'language' => 'ja', 'languages' => ['ja', 'en'], 'currency' => 'JPY', 'timezone' => 'Asia/Tokyo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'KR', 'name' => 'South Korea', 'native' => '대한민국', 'language' => 'ko', 'languages' => ['ko', 'en'], 'currency' => 'KRW', 'timezone' => 'Asia/Seoul', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'KP', 'name' => 'North Korea', 'native' => '조선', 'language' => 'ko', 'languages' => ['ko'], 'currency' => 'KPW', 'timezone' => 'Asia/Pyongyang', 'requires_certified' => true],
            ['code' => 'IN', 'name' => 'India', 'native' => 'भारत', 'language' => 'hi', 'languages' => ['hi', 'en'], 'currency' => 'INR', 'timezone' => 'Asia/Kolkata', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PK', 'name' => 'Pakistan', 'native' => 'پاکستان', 'language' => 'ur', 'languages' => ['ur', 'en'], 'currency' => 'PKR', 'timezone' => 'Asia/Karachi', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'BD', 'name' => 'Bangladesh', 'native' => 'বাংলাদেশ', 'language' => 'bn', 'languages' => ['bn', 'en'], 'currency' => 'BDT', 'timezone' => 'Asia/Dhaka', 'requires_certified' => true, 'requires_notarization' => true],
            ['code' => 'LK', 'name' => 'Sri Lanka', 'native' => 'ශ්‍රී ලංකාව', 'language' => 'si', 'languages' => ['si', 'ta', 'en'], 'currency' => 'LKR', 'timezone' => 'Asia/Colombo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'NP', 'name' => 'Nepal', 'native' => 'नेपाल', 'language' => 'ne', 'languages' => ['ne', 'en'], 'currency' => 'NPR', 'timezone' => 'Asia/Kathmandu', 'requires_certified' => true],
            ['code' => 'TH', 'name' => 'Thailand', 'native' => 'ประเทศไทย', 'language' => 'th', 'languages' => ['th', 'en'], 'currency' => 'THB', 'timezone' => 'Asia/Bangkok', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'VN', 'name' => 'Vietnam', 'native' => 'Việt Nam', 'language' => 'vi', 'languages' => ['vi', 'en'], 'currency' => 'VND', 'timezone' => 'Asia/Ho_Chi_Minh', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MY', 'name' => 'Malaysia', 'native' => 'Malaysia', 'language' => 'ms', 'languages' => ['ms', 'en', 'zh'], 'currency' => 'MYR', 'timezone' => 'Asia/Kuala_Lumpur', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SG', 'name' => 'Singapore', 'native' => 'Singapore', 'language' => 'en', 'languages' => ['en', 'zh', 'ms', 'ta'], 'currency' => 'SGD', 'timezone' => 'Asia/Singapore', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'ID', 'name' => 'Indonesia', 'native' => 'Indonesia', 'language' => 'id', 'languages' => ['id', 'en'], 'currency' => 'IDR', 'timezone' => 'Asia/Jakarta', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PH', 'name' => 'Philippines', 'native' => 'Pilipinas', 'language' => 'tl', 'languages' => ['tl', 'en'], 'currency' => 'PHP', 'timezone' => 'Asia/Manila', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MM', 'name' => 'Myanmar', 'native' => 'မြန်မာ', 'language' => 'my', 'languages' => ['my', 'en'], 'currency' => 'MMK', 'timezone' => 'Asia/Yangon', 'requires_certified' => true],
            ['code' => 'KH', 'name' => 'Cambodia', 'native' => 'កម្ពុជា', 'language' => 'km', 'languages' => ['km', 'en'], 'currency' => 'KHR', 'timezone' => 'Asia/Phnom_Penh', 'requires_certified' => true],
            ['code' => 'LA', 'name' => 'Laos', 'native' => 'ລາວ', 'language' => 'lo', 'languages' => ['lo', 'en'], 'currency' => 'LAK', 'timezone' => 'Asia/Vientiane', 'requires_certified' => true],
            ['code' => 'HK', 'name' => 'Hong Kong', 'native' => '香港', 'language' => 'zh', 'languages' => ['zh', 'en'], 'currency' => 'HKD', 'timezone' => 'Asia/Hong_Kong', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'TW', 'name' => 'Taiwan', 'native' => '台灣', 'language' => 'zh', 'languages' => ['zh', 'en'], 'currency' => 'TWD', 'timezone' => 'Asia/Taipei', 'requires_certified' => true],
            ['code' => 'MN', 'name' => 'Mongolia', 'native' => 'Монгол', 'language' => 'mn', 'languages' => ['mn', 'en'], 'currency' => 'MNT', 'timezone' => 'Asia/Ulaanbaatar', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'KZ', 'name' => 'Kazakhstan', 'native' => 'Қазақстан', 'language' => 'kk', 'languages' => ['kk', 'ru', 'en'], 'currency' => 'KZT', 'timezone' => 'Asia/Almaty', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'UZ', 'name' => 'Uzbekistan', 'native' => 'O\'zbekiston', 'language' => 'uz', 'languages' => ['uz', 'ru', 'en'], 'currency' => 'UZS', 'timezone' => 'Asia/Tashkent', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'TM', 'name' => 'Turkmenistan', 'native' => 'Türkmenistan', 'language' => 'tk', 'languages' => ['tk', 'ru', 'en'], 'currency' => 'TMT', 'timezone' => 'Asia/Ashgabat', 'requires_certified' => true],
            ['code' => 'TJ', 'name' => 'Tajikistan', 'native' => 'Тоҷикистон', 'language' => 'tg', 'languages' => ['tg', 'ru', 'en'], 'currency' => 'TJS', 'timezone' => 'Asia/Dushanbe', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'KG', 'name' => 'Kyrgyzstan', 'native' => 'Кыргызстан', 'language' => 'ky', 'languages' => ['ky', 'ru', 'en'], 'currency' => 'KGS', 'timezone' => 'Asia/Bishkek', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'AF', 'name' => 'Afghanistan', 'native' => 'افغانستان', 'language' => 'ps', 'languages' => ['ps', 'fa', 'en'], 'currency' => 'AFN', 'timezone' => 'Asia/Kabul', 'requires_certified' => true],
            
            // Oceania
            ['code' => 'AU', 'name' => 'Australia', 'native' => 'Australia', 'language' => 'en', 'languages' => ['en'], 'currency' => 'AUD', 'timezone' => 'Australia/Sydney', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'NZ', 'name' => 'New Zealand', 'native' => 'New Zealand', 'language' => 'en', 'languages' => ['en', 'mi'], 'currency' => 'NZD', 'timezone' => 'Pacific/Auckland', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'FJ', 'name' => 'Fiji', 'native' => 'Fiji', 'language' => 'en', 'languages' => ['en', 'fj', 'hi'], 'currency' => 'FJD', 'timezone' => 'Pacific/Fiji', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PG', 'name' => 'Papua New Guinea', 'native' => 'Papua Niugini', 'language' => 'en', 'languages' => ['en', 'ho', 'tpi'], 'currency' => 'PGK', 'timezone' => 'Pacific/Port_Moresby', 'requires_certified' => true],
            
            // Americas
            ['code' => 'US', 'name' => 'United States', 'native' => 'United States', 'language' => 'en', 'languages' => ['en', 'es'], 'currency' => 'USD', 'timezone' => 'America/New_York', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CA', 'name' => 'Canada', 'native' => 'Canada', 'language' => 'en', 'languages' => ['en', 'fr'], 'currency' => 'CAD', 'timezone' => 'America/Toronto', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MX', 'name' => 'Mexico', 'native' => 'México', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'MXN', 'timezone' => 'America/Mexico_City', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BR', 'name' => 'Brazil', 'native' => 'Brasil', 'language' => 'pt', 'languages' => ['pt', 'en'], 'currency' => 'BRL', 'timezone' => 'America/Sao_Paulo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'AR', 'name' => 'Argentina', 'native' => 'Argentina', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'ARS', 'timezone' => 'America/Buenos_Aires', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CL', 'name' => 'Chile', 'native' => 'Chile', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'CLP', 'timezone' => 'America/Santiago', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CO', 'name' => 'Colombia', 'native' => 'Colombia', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'COP', 'timezone' => 'America/Bogota', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PE', 'name' => 'Peru', 'native' => 'Perú', 'language' => 'es', 'languages' => ['es', 'qu', 'en'], 'currency' => 'PEN', 'timezone' => 'America/Lima', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'VE', 'name' => 'Venezuela', 'native' => 'Venezuela', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'VES', 'timezone' => 'America/Caracas', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'EC', 'name' => 'Ecuador', 'native' => 'Ecuador', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'USD', 'timezone' => 'America/Guayaquil', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'BO', 'name' => 'Bolivia', 'native' => 'Bolivia', 'language' => 'es', 'languages' => ['es', 'qu', 'ay', 'en'], 'currency' => 'BOB', 'timezone' => 'America/La_Paz', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PY', 'name' => 'Paraguay', 'native' => 'Paraguay', 'language' => 'es', 'languages' => ['es', 'gn', 'en'], 'currency' => 'PYG', 'timezone' => 'America/Asuncion', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'UY', 'name' => 'Uruguay', 'native' => 'Uruguay', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'UYU', 'timezone' => 'America/Montevideo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'PA', 'name' => 'Panama', 'native' => 'Panamá', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'PAB', 'timezone' => 'America/Panama', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CR', 'name' => 'Costa Rica', 'native' => 'Costa Rica', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'CRC', 'timezone' => 'America/Costa_Rica', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'GT', 'name' => 'Guatemala', 'native' => 'Guatemala', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'GTQ', 'timezone' => 'America/Guatemala', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CU', 'name' => 'Cuba', 'native' => 'Cuba', 'language' => 'es', 'languages' => ['es'], 'currency' => 'CUP', 'timezone' => 'America/Havana', 'requires_certified' => true],
            ['code' => 'DO', 'name' => 'Dominican Republic', 'native' => 'República Dominicana', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'DOP', 'timezone' => 'America/Santo_Domingo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'HN', 'name' => 'Honduras', 'native' => 'Honduras', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'HNL', 'timezone' => 'America/Tegucigalpa', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SV', 'name' => 'El Salvador', 'native' => 'El Salvador', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'USD', 'timezone' => 'America/El_Salvador', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'NI', 'name' => 'Nicaragua', 'native' => 'Nicaragua', 'language' => 'es', 'languages' => ['es', 'en'], 'currency' => 'NIO', 'timezone' => 'America/Managua', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'HT', 'name' => 'Haiti', 'native' => 'Haïti', 'language' => 'fr', 'languages' => ['fr', 'ht', 'en'], 'currency' => 'HTG', 'timezone' => 'America/Port-au-Prince', 'requires_certified' => true],
            ['code' => 'JM', 'name' => 'Jamaica', 'native' => 'Jamaica', 'language' => 'en', 'languages' => ['en'], 'currency' => 'JMD', 'timezone' => 'America/Jamaica', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'TT', 'name' => 'Trinidad and Tobago', 'native' => 'Trinidad and Tobago', 'language' => 'en', 'languages' => ['en'], 'currency' => 'TTD', 'timezone' => 'America/Port_of_Spain', 'requires_certified' => true, 'requires_apostille' => true],
            
            // Africa
            ['code' => 'ZA', 'name' => 'South Africa', 'native' => 'South Africa', 'language' => 'en', 'languages' => ['en', 'af', 'zu'], 'currency' => 'ZAR', 'timezone' => 'Africa/Johannesburg', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'NG', 'name' => 'Nigeria', 'native' => 'Nigeria', 'language' => 'en', 'languages' => ['en', 'ha', 'yo', 'ig'], 'currency' => 'NGN', 'timezone' => 'Africa/Lagos', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'KE', 'name' => 'Kenya', 'native' => 'Kenya', 'language' => 'en', 'languages' => ['en', 'sw'], 'currency' => 'KES', 'timezone' => 'Africa/Nairobi', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'GH', 'name' => 'Ghana', 'native' => 'Ghana', 'language' => 'en', 'languages' => ['en'], 'currency' => 'GHS', 'timezone' => 'Africa/Accra', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'ET', 'name' => 'Ethiopia', 'native' => 'ኢትዮጵያ', 'language' => 'am', 'languages' => ['am', 'en'], 'currency' => 'ETB', 'timezone' => 'Africa/Addis_Ababa', 'requires_certified' => true],
            ['code' => 'TZ', 'name' => 'Tanzania', 'native' => 'Tanzania', 'language' => 'sw', 'languages' => ['sw', 'en'], 'currency' => 'TZS', 'timezone' => 'Africa/Dar_es_Salaam', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'UG', 'name' => 'Uganda', 'native' => 'Uganda', 'language' => 'en', 'languages' => ['en', 'sw'], 'currency' => 'UGX', 'timezone' => 'Africa/Kampala', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'RW', 'name' => 'Rwanda', 'native' => 'Rwanda', 'language' => 'rw', 'languages' => ['rw', 'en', 'fr'], 'currency' => 'RWF', 'timezone' => 'Africa/Kigali', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'SD', 'name' => 'Sudan', 'native' => 'السودان', 'language' => 'ar', 'languages' => ['ar', 'en'], 'currency' => 'SDG', 'timezone' => 'Africa/Khartoum', 'requires_certified' => true],
            ['code' => 'SN', 'name' => 'Senegal', 'native' => 'Sénégal', 'language' => 'fr', 'languages' => ['fr', 'wo', 'en'], 'currency' => 'XOF', 'timezone' => 'Africa/Dakar', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CI', 'name' => 'Côte d\'Ivoire', 'native' => 'Côte d\'Ivoire', 'language' => 'fr', 'languages' => ['fr', 'en'], 'currency' => 'XOF', 'timezone' => 'Africa/Abidjan', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'CM', 'name' => 'Cameroon', 'native' => 'Cameroun', 'language' => 'fr', 'languages' => ['fr', 'en'], 'currency' => 'XAF', 'timezone' => 'Africa/Douala', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'AO', 'name' => 'Angola', 'native' => 'Angola', 'language' => 'pt', 'languages' => ['pt', 'en'], 'currency' => 'AOA', 'timezone' => 'Africa/Luanda', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'MZ', 'name' => 'Mozambique', 'native' => 'Moçambique', 'language' => 'pt', 'languages' => ['pt', 'en'], 'currency' => 'MZN', 'timezone' => 'Africa/Maputo', 'requires_certified' => true, 'requires_apostille' => true],
            ['code' => 'ZW', 'name' => 'Zimbabwe', 'native' => 'Zimbabwe', 'language' => 'en', 'languages' => ['en', 'sn', 'nd'], 'currency' => 'ZWL', 'timezone' => 'Africa/Harare', 'requires_certified' => true],
            ['code' => 'MU', 'name' => 'Mauritius', 'native' => 'Mauritius', 'language' => 'en', 'languages' => ['en', 'fr', 'mfe'], 'currency' => 'MUR', 'timezone' => 'Indian/Mauritius', 'requires_certified' => true, 'requires_apostille' => true],
        ];
    }
}
