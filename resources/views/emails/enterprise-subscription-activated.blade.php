<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الاشتراك</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; direction: rtl;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 28px;">🎉 تم تفعيل اشتراككم!</h1>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; color: #333; line-height: 1.8;">
                مرحباً <strong>{{ $subscription->company_name }}</strong>،
            </p>

            <p style="font-size: 16px; color: #333; line-height: 1.8;">
                يسعدنا إبلاغكم بأنه تم تفعيل اشتراك المؤسسة الخاص بكم في منصة CulturalTranslate!
            </p>

            <!-- Subscription Details -->
            <div style="background-color: #f8f9fa; border-radius: 8px; padding: 25px; margin: 25px 0;">
                <h2 style="color: #667eea; margin-top: 0; font-size: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                    تفاصيل الاشتراك
                </h2>
                
                <table style="width: 100%; font-size: 15px;">
                    <tr>
                        <td style="padding: 10px 0; color: #666;"><strong>كود الاشتراك:</strong></td>
                        <td style="padding: 10px 0; color: #333; font-weight: bold;">{{ $subscription->subscription_code }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #666;"><strong>نوع الخطة:</strong></td>
                        <td style="padding: 10px 0; color: #333;">
                            @if($subscription->plan_type === 'pay_as_you_go')
                                الدفع حسب الاستخدام
                            @elseif($subscription->plan_type === 'committed')
                                حجم محجوز شهرياً
                            @else
                                هجين (محجوز + إضافي)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #666;"><strong>دورة الفوترة:</strong></td>
                        <td style="padding: 10px 0; color: #333;">
                            @if($subscription->billing_cycle === 'monthly')
                                شهرياً
                            @elseif($subscription->billing_cycle === 'quarterly')
                                كل 3 أشهر
                            @else
                                سنوياً
                            @endif
                        </td>
                    </tr>
                    @if($subscription->committed_words_monthly > 0)
                    <tr>
                        <td style="padding: 10px 0; color: #666;"><strong>الكلمات المحجوزة:</strong></td>
                        <td style="padding: 10px 0; color: #333;">{{ number_format($subscription->committed_words_monthly) }} كلمة/شهرياً</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Pricing -->
            <div style="background-color: #e8f5e9; border-radius: 8px; padding: 25px; margin: 25px 0;">
                <h3 style="color: #2e7d32; margin-top: 0; font-size: 18px;">💰 التسعير</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; color: #333;">✓ سعر الكلمة: <strong>${{ $subscription->price_per_word }}</strong></li>
                    <li style="padding: 8px 0; color: #333;">✓ سعر استدعاء API: <strong>${{ $subscription->price_per_api_call }}</strong></li>
                    <li style="padding: 8px 0; color: #333;">✓ سعر ثانية الصوت: <strong>${{ $subscription->price_per_voice_second }}</strong></li>
                    @if($subscription->discount_percentage > 0)
                    <li style="padding: 8px 0; color: #2e7d32; font-weight: bold;">🎁 خصم: {{ $subscription->discount_percentage }}%</li>
                    @endif
                </ul>
            </div>

            <!-- Features -->
            <div style="background-color: #fff3e0; border-radius: 8px; padding: 25px; margin: 25px 0;">
                <h3 style="color: #e65100; margin-top: 0; font-size: 18px;">🚀 الميزات المتاحة</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; color: #333;">✅ دعم فني مخصص على مدار الساعة</li>
                    <li style="padding: 8px 0; color: #333;">✅ مدير حساب مخصص</li>
                    <li style="padding: 8px 0; color: #333;">✅ وصول كامل لـ API</li>
                    <li style="padding: 8px 0; color: #333;">✅ نماذج ذكاء اصطناعي مخصصة</li>
                    <li style="padding: 8px 0; color: #333;">✅ تكامل SSO</li>
                    <li style="padding: 8px 0; color: #333;">✅ تقارير تفصيلية</li>
                    <li style="padding: 8px 0; color: #333;">✅ SLA مضمون</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ config('app.url') }}/enterprise/dashboard" 
                   style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                    الانتقال إلى لوحة التحكم
                </a>
            </div>

            <!-- Support -->
            <div style="background-color: #e3f2fd; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <p style="margin: 0; color: #1565c0; font-size: 15px;">
                    <strong>هل تحتاج إلى مساعدة؟</strong><br>
                    فريق الدعم المخصص لكم متاح على:<br>
                    📧 <a href="mailto:enterprise@culturaltranslate.com" style="color: #1565c0;">enterprise@culturaltranslate.com</a><br>
                    📞 يمكنكم التواصل مع مدير حسابكم المخصص
                </p>
            </div>

            <p style="font-size: 16px; color: #333; line-height: 1.8;">
                نحن متحمسون للعمل معكم!
            </p>

            <p style="font-size: 16px; color: #333; margin-bottom: 0;">
                مع أطيب التحيات،<br>
                <strong>فريق CulturalTranslate</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 13px; color: #666;">
            <p style="margin: 5px 0;">© {{ date('Y') }} CulturalTranslate. جميع الحقوق محفوظة.</p>
            <p style="margin: 5px 0;">
                <a href="{{ config('app.url') }}/terms" style="color: #667eea; text-decoration: none;">الشروط والأحكام</a> | 
                <a href="{{ config('app.url') }}/privacy" style="color: #667eea; text-decoration: none;">سياسة الخصوصية</a>
            </p>
        </div>
    </div>
</body>
</html>
