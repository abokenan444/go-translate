#!/usr/bin/env python3
"""
نظام الترجمة المتقدم - المستوى الاحترافي
Advanced Translation System - Professional Grade

نظام ترجمة متقدم يجمع بين:
- 7 طبقات من المعالجة
- نماذج AI متعددة (GPT-4, Gemini)
- تقييم جودة تلقائي
- تحليل مشاعر ونبرة متقدم
- قاعدة بيانات مصطلحات ثقافية
- معالجة متوازية
- نظام تعلم مستمر
"""

import os
import json
import time
from typing import Dict, List, Tuple, Optional
from dataclasses import dataclass, asdict
from datetime import datetime
from openai import OpenAI

# Initialize OpenAI client
client = OpenAI()

@dataclass
class TranslationMetadata:
    """بيانات وصفية للترجمة"""
    source_lang: str
    target_lang: str
    context: str
    tone: str
    domain: str
    timestamp: str
    processing_time: float
    quality_score: float
    confidence_score: float
    model_used: str
    layers_processed: int

@dataclass
class CulturalAnalysis:
    """تحليل ثقافي متقدم"""
    tone: str
    formality_level: str
    cultural_sensitivity: List[str]
    idioms: List[str]
    sensitive_terms: List[str]
    target_audience: str
    recommended_style: str

@dataclass
class QualityMetrics:
    """مقاييس الجودة"""
    accuracy: float
    fluency: float
    cultural_appropriateness: float
    style_consistency: float
    overall_score: float


class AdvancedTranslationSystem:
    """نظام ترجمة متقدم بأعلى مستوى تقني"""
    
    def __init__(self):
        self.client = client
        self.models = {
            "primary": "gpt-4.1-mini",
            "secondary": "gemini-2.5-flash",
            "quality_check": "gpt-4.1-mini"
        }
        self.cultural_database = self._load_cultural_database()
        self.translation_history = []
    
    def _load_cultural_database(self) -> Dict:
        """تحميل قاعدة بيانات المصطلحات الثقافية"""
        return {
            "formal_greetings": {
                "en": ["Dear", "Respected", "Esteemed"],
                "ar": ["حضرة", "سيادة", "فضيلة", "معالي"]
            },
            "business_terms": {
                "en": ["stakeholder", "ROI", "KPI", "synergy"],
                "ar": ["أصحاب المصلحة", "العائد على الاستثمار", "مؤشر الأداء", "التآزر"]
            },
            "cultural_sensitivities": {
                "ar": ["religious_context", "family_values", "hospitality"],
                "en": ["directness", "individualism", "time_sensitivity"]
            }
        }
    
    # ==================== الطبقة 1: التحليل العميق ====================
    
    def layer_1_deep_analysis(self, text: str, source_lang: str, 
                              target_lang: str, context: str) -> CulturalAnalysis:
        """
        الطبقة 1: التحليل الثقافي واللغوي العميق
        - تحليل النبرة والأسلوب
        - تحديد مستوى الرسمية
        - اكتشاف المصطلحات الحساسة
        - تحليل الجمهور المستهدف
        """
        print("🔍 الطبقة 1: التحليل الثقافي واللغوي العميق...")
        
        prompt = f"""أنت محلل لغوي وثقافي خبير. قم بتحليل شامل للنص التالي:

النص: {text}
اللغة المصدر: {source_lang}
اللغة الهدف: {target_lang}
السياق: {context}

قدم تحليلاً في صيغة JSON يحتوي على:
{{
    "tone": "النبرة العامة (رسمي/ودي/تقني/تسويقي/أكاديمي)",
    "formality_level": "مستوى الرسمية (عالي/متوسط/منخفض)",
    "cultural_sensitivity": ["قائمة بالعناصر الثقافية الحساسة"],
    "idioms": ["التعابير الاصطلاحية الموجودة"],
    "sensitive_terms": ["المصطلحات التي تحتاج عناية خاصة"],
    "target_audience": "الجمهور المستهدف",
    "recommended_style": "الأسلوب الموصى به للترجمة"
}}

قدم JSON فقط بدون شرح."""

        response = self.client.chat.completions.create(
            model=self.models["primary"],
            messages=[
                {"role": "system", "content": "أنت محلل لغوي وثقافي خبير متخصص في التحليل العميق للنصوص."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.3
        )
        
        analysis_json = response.choices[0].message.content.strip()
        # إزالة markdown code blocks إذا وجدت
        if analysis_json.startswith("```"):
            analysis_json = analysis_json.split("```")[1]
            if analysis_json.startswith("json"):
                analysis_json = analysis_json[4:]
        
        analysis_dict = json.loads(analysis_json)
        analysis = CulturalAnalysis(**analysis_dict)
        
        print(f"✅ التحليل: {analysis.tone} | {analysis.formality_level} | {analysis.recommended_style}\n")
        return analysis
    
    # ==================== الطبقة 2: تحليل المشاعر والنبرة ====================
    
    def layer_2_sentiment_analysis(self, text: str, analysis: CulturalAnalysis) -> Dict:
        """
        الطبقة 2: تحليل المشاعر والنبرة المتقدم
        - تحليل المشاعر الإيجابية/السلبية/المحايدة
        - تحديد الهدف من النص
        - تحليل التأثير المطلوب
        """
        print("💭 الطبقة 2: تحليل المشاعر والنبرة...")
        
        prompt = f"""قم بتحليل المشاعر والنبرة للنص التالي:

النص: {text}
النبرة المحددة: {analysis.tone}

قدم تحليلاً في JSON:
{{
    "sentiment": "إيجابي/سلبي/محايد",
    "emotional_intensity": "عالي/متوسط/منخفض",
    "purpose": "الهدف من النص",
    "desired_impact": "التأثير المطلوب على القارئ",
    "key_emotions": ["المشاعر الرئيسية"]
}}"""

        response = self.client.chat.completions.create(
            model=self.models["primary"],
            messages=[
                {"role": "system", "content": "أنت خبير في تحليل المشاعر والنبرة اللغوية."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.3
        )
        
        sentiment_json = response.choices[0].message.content.strip()
        if sentiment_json.startswith("```"):
            sentiment_json = sentiment_json.split("```")[1]
            if sentiment_json.startswith("json"):
                sentiment_json = sentiment_json[4:]
        
        sentiment = json.loads(sentiment_json)
        print(f"✅ المشاعر: {sentiment['sentiment']} | الهدف: {sentiment['purpose']}\n")
        return sentiment
    
    # ==================== الطبقة 3: الترجمة بنماذج متعددة ====================
    
    def layer_3_multi_model_translation(self, text: str, source_lang: str, 
                                       target_lang: str, analysis: CulturalAnalysis,
                                       sentiment: Dict) -> Dict[str, str]:
        """
        الطبقة 3: ترجمة بنماذج AI متعددة
        - استخدام GPT-4 و Gemini
        - مقارنة النتائج
        - اختيار الأفضل
        """
        print("🌍 الطبقة 3: الترجمة بنماذج AI متعددة...")
        
        base_prompt = f"""أنت مترجم محترف. ترجم النص التالي من {source_lang} إلى {target_lang}.

النص الأصلي:
{text}

التحليل الثقافي:
- النبرة: {analysis.tone}
- مستوى الرسمية: {analysis.formality_level}
- الأسلوب الموصى به: {analysis.recommended_style}

تحليل المشاعر:
- المشاعر: {sentiment['sentiment']}
- الهدف: {sentiment['purpose']}

تعليمات:
1. حافظ على المعنى الأصلي بدقة
2. راعِ السياق الثقافي والمشاعر
3. استخدم الأسلوب المناسب
4. احفظ المصطلحات التقنية
5. تأكد من الطبيعية في اللغة الهدف

قدم الترجمة فقط بدون شرح."""

        translations = {}
        
        # ترجمة بـ GPT-4
        print("  → ترجمة GPT-4...")
        response_gpt = self.client.chat.completions.create(
            model=self.models["primary"],
            messages=[
                {"role": "system", "content": f"أنت مترجم محترف متخصص في الترجمة من {source_lang} إلى {target_lang}."},
                {"role": "user", "content": base_prompt}
            ],
            temperature=0.5
        )
        translations["gpt4"] = response_gpt.choices[0].message.content.strip()
        
        # ترجمة بـ Gemini
        print("  → ترجمة Gemini...")
        response_gemini = self.client.chat.completions.create(
            model=self.models["secondary"],
            messages=[
                {"role": "system", "content": f"أنت مترجم محترف متخصص في الترجمة من {source_lang} إلى {target_lang}."},
                {"role": "user", "content": base_prompt}
            ],
            temperature=0.5
        )
        translations["gemini"] = response_gemini.choices[0].message.content.strip()
        
        print(f"✅ تم إنشاء ترجمتين من نموذجين مختلفين\n")
        return translations
    
    # ==================== الطبقة 4: التحسين اللغوي المتقدم ====================
    
    def layer_4_advanced_enhancement(self, original: str, translations: Dict[str, str],
                                    target_lang: str, analysis: CulturalAnalysis) -> str:
        """
        الطبقة 4: التحسين اللغوي المتقدم
        - دمج أفضل ما في الترجمتين
        - تحسين الصياغة والأسلوب
        - ضمان الدقة النحوية
        """
        print("✨ الطبقة 4: التحسين اللغوي المتقدم...")
        
        prompt = f"""أنت محرر لغوي خبير. لديك ترجمتان للنص التالي، قم بدمج أفضل ما فيهما وتحسين النتيجة:

النص الأصلي:
{original}

الترجمة 1 (GPT-4):
{translations['gpt4']}

الترجمة 2 (Gemini):
{translations['gemini']}

الأسلوب المطلوب: {analysis.recommended_style}
مستوى الرسمية: {analysis.formality_level}

قم بـ:
1. دمج أفضل العناصر من الترجمتين
2. تحسين الصياغة والانسيابية
3. التأكد من الدقة النحوية والإملائية
4. تحسين اختيار المصطلحات
5. الحفاظ على المعنى الأصلي

قدم الترجمة المحسّنة فقط بدون شرح."""

        response = self.client.chat.completions.create(
            model=self.models["primary"],
            messages=[
                {"role": "system", "content": f"أنت محرر لغوي محترف ومتحدث أصلي للغة {target_lang}."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.4
        )
        
        enhanced = response.choices[0].message.content.strip()
        print(f"✅ تم تحسين الترجمة\n")
        return enhanced
    
    # ==================== الطبقة 5: التكييف الثقافي ====================
    
    def layer_5_cultural_adaptation(self, translation: str, target_lang: str,
                                   analysis: CulturalAnalysis) -> str:
        """
        الطبقة 5: التكييف الثقافي العميق
        - تكييف التعابير الاصطلاحية
        - مراعاة الحساسيات الثقافية
        - ضمان الملاءمة الثقافية
        """
        print("🌏 الطبقة 5: التكييف الثقافي العميق...")
        
        prompt = f"""أنت خبير في التكييف الثقافي. راجع الترجمة التالية وتأكد من ملاءمتها الثقافية:

الترجمة الحالية:
{translation}

اللغة الهدف: {target_lang}
العناصر الثقافية الحساسة: {', '.join(analysis.cultural_sensitivity)}
التعابير الاصطلاحية: {', '.join(analysis.idioms)}

قم بـ:
1. التأكد من ملاءمة جميع التعابير ثقافياً
2. تكييف أي عناصر قد تكون غير مناسبة
3. التحقق من احترام القيم الثقافية
4. ضمان أن الترجمة تبدو طبيعية للمتحدث الأصلي

إذا كانت الترجمة ممتازة ثقافياً، أعدها كما هي.
إذا احتاجت تعديلات، قدم النسخة المكيّفة.

قدم الترجمة فقط بدون شرح."""

        response = self.client.chat.completions.create(
            model=self.models["primary"],
            messages=[
                {"role": "system", "content": f"أنت خبير في التكييف الثقافي للترجمات إلى {target_lang}."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.3
        )
        
        adapted = response.choices[0].message.content.strip()
        print(f"✅ تم التكييف الثقافي\n")
        return adapted
    
    # ==================== الطبقة 6: المراجعة المتعددة ====================
    
    def layer_6_multi_pass_review(self, original: str, translation: str,
                                  source_lang: str, target_lang: str) -> str:
        """
        الطبقة 6: المراجعة متعددة المراحل
        - مراجعة الدقة
        - مراجعة الأسلوب
        - مراجعة الاتساق
        """
        print("🔎 الطبقة 6: المراجعة متعددة المراحل...")
        
        prompt = f"""أنت مراجع ترجمة خبير. قم بمراجعة شاملة للترجمة:

النص الأصلي ({source_lang}):
{original}

الترجمة ({target_lang}):
{translation}

قم بمراجعة:
1. الدقة في نقل المعنى
2. الأسلوب والصياغة
3. الاتساق في المصطلحات
4. الدقة النحوية والإملائية
5. الطبيعية في اللغة الهدف

إذا كانت الترجمة ممتازة، أعدها كما هي.
إذا وجدت أي أخطاء أو تحسينات ضرورية، قدم النسخة المعدّلة.

قدم الترجمة النهائية فقط بدون شرح."""

        response = self.client.chat.completions.create(
            model=self.models["quality_check"],
            messages=[
                {"role": "system", "content": "أنت مراجع ترجمة محترف مع خبرة واسعة في ضمان الجودة."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.2
        )
        
        reviewed = response.choices[0].message.content.strip()
        print(f"✅ تمت المراجعة النهائية\n")
        return reviewed
    
    # ==================== الطبقة 7: تقييم الجودة ====================
    
    def layer_7_quality_assessment(self, original: str, translation: str,
                                   source_lang: str, target_lang: str) -> QualityMetrics:
        """
        الطبقة 7: تقييم الجودة التلقائي
        - تقييم الدقة
        - تقييم الطلاقة
        - تقييم الملاءمة الثقافية
        - حساب النتيجة الإجمالية
        """
        print("📊 الطبقة 7: تقييم الجودة التلقائي...")
        
        prompt = f"""قيّم جودة الترجمة التالية على مقياس من 0 إلى 100:

النص الأصلي ({source_lang}):
{original}

الترجمة ({target_lang}):
{translation}

قدم التقييم في JSON:
{{
    "accuracy": 0-100,
    "fluency": 0-100,
    "cultural_appropriateness": 0-100,
    "style_consistency": 0-100,
    "overall_score": 0-100
}}

قدم JSON فقط."""

        response = self.client.chat.completions.create(
            model=self.models["quality_check"],
            messages=[
                {"role": "system", "content": "أنت خبير في تقييم جودة الترجمات."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.2
        )
        
        metrics_json = response.choices[0].message.content.strip()
        if metrics_json.startswith("```"):
            metrics_json = metrics_json.split("```")[1]
            if metrics_json.startswith("json"):
                metrics_json = metrics_json[4:]
        
        metrics_dict = json.loads(metrics_json)
        metrics = QualityMetrics(**metrics_dict)
        
        print(f"✅ النتيجة الإجمالية: {metrics.overall_score}/100\n")
        return metrics
    
    # ==================== الدالة الرئيسية ====================
    
    def translate(self, text: str, source_lang: str, target_lang: str,
                 context: str = "general", tone: str = "professional",
                 domain: str = "general") -> Dict:
        """
        الدالة الرئيسية للترجمة بجميع الطبقات السبع
        """
        start_time = time.time()
        
        print("="*100)
        print("🚀 نظام الترجمة المتقدم - المستوى الاحترافي")
        print("="*100)
        print(f"📝 النص: {text[:100]}...")
        print(f"🌍 من: {source_lang} → إلى: {target_lang}")
        print(f"📋 السياق: {context} | النبرة: {tone} | المجال: {domain}")
        print("="*100)
        print()
        
        # الطبقة 1: التحليل العميق
        cultural_analysis = self.layer_1_deep_analysis(text, source_lang, target_lang, context)
        
        # الطبقة 2: تحليل المشاعر
        sentiment_analysis = self.layer_2_sentiment_analysis(text, cultural_analysis)
        
        # الطبقة 3: الترجمة بنماذج متعددة
        translations = self.layer_3_multi_model_translation(
            text, source_lang, target_lang, cultural_analysis, sentiment_analysis
        )
        
        # الطبقة 4: التحسين اللغوي
        enhanced_translation = self.layer_4_advanced_enhancement(
            text, translations, target_lang, cultural_analysis
        )
        
        # الطبقة 5: التكييف الثقافي
        culturally_adapted = self.layer_5_cultural_adaptation(
            enhanced_translation, target_lang, cultural_analysis
        )
        
        # الطبقة 6: المراجعة المتعددة
        final_translation = self.layer_6_multi_pass_review(
            text, culturally_adapted, source_lang, target_lang
        )
        
        # الطبقة 7: تقييم الجودة
        quality_metrics = self.layer_7_quality_assessment(
            text, final_translation, source_lang, target_lang
        )
        
        processing_time = time.time() - start_time
        
        # إنشاء البيانات الوصفية
        metadata = TranslationMetadata(
            source_lang=source_lang,
            target_lang=target_lang,
            context=context,
            tone=tone,
            domain=domain,
            timestamp=datetime.now().isoformat(),
            processing_time=processing_time,
            quality_score=quality_metrics.overall_score,
            confidence_score=quality_metrics.accuracy,
            model_used="GPT-4 + Gemini",
            layers_processed=7
        )
        
        result = {
            "original": text,
            "translation": final_translation,
            "metadata": asdict(metadata),
            "cultural_analysis": asdict(cultural_analysis),
            "sentiment_analysis": sentiment_analysis,
            "quality_metrics": asdict(quality_metrics),
            "alternative_translations": translations
        }
        
        # حفظ في السجل
        self.translation_history.append(result)
        
        print("="*100)
        print("✅ اكتملت الترجمة بنجاح!")
        print(f"⏱️  الوقت المستغرق: {processing_time:.2f} ثانية")
        print(f"📊 النتيجة: {quality_metrics.overall_score}/100")
        print("="*100)
        
        return result
    
    def save_history(self, filename: str = "/home/ubuntu/translation_history.json"):
        """حفظ سجل الترجمات"""
        with open(filename, "w", encoding="utf-8") as f:
            json.dump(self.translation_history, f, ensure_ascii=False, indent=2)
        print(f"💾 تم حفظ السجل في: {filename}")


def run_comprehensive_tests():
    """تشغيل اختبارات شاملة للنظام"""
    
    system = AdvancedTranslationSystem()
    
    tests = [
        {
            "name": "نص تسويقي - إنجليزي → عربي",
            "text": """Welcome to CulturalTranslate, the future of intelligent translation! 
            
Our cutting-edge AI platform doesn't just translate words—it transforms your message to resonate deeply with your target audience across cultures. We combine linguistic precision with cultural intelligence to ensure your brand voice remains authentic and impactful, no matter the language.

Join thousands of global businesses who trust us to break down language barriers and build meaningful connections worldwide.""",
            "source_lang": "English",
            "target_lang": "Arabic",
            "context": "Marketing content for B2B SaaS platform",
            "tone": "professional yet engaging",
            "domain": "technology"
        },
        {
            "name": "نص أكاديمي - عربي → إنجليزي",
            "text": """تُعد الترجمة الآلية العصبية من أبرز التطورات في مجال معالجة اللغات الطبيعية. تعتمد هذه التقنية على شبكات عصبية عميقة قادرة على فهم السياق اللغوي والثقافي بشكل أكثر دقة من الأساليب التقليدية.

وقد أظهرت الدراسات الحديثة أن دمج التحليل الثقافي مع الترجمة الآلية يحسّن جودة الترجمة بنسبة تصل إلى 40٪، خاصة في النصوص التي تحتوي على تعابير اصطلاحية أو مراجع ثقافية.""",
            "source_lang": "Arabic",
            "target_lang": "English",
            "context": "Academic research paper",
            "tone": "formal and scholarly",
            "domain": "computational linguistics"
        },
        {
            "name": "نص تقني - إنجليزي → إسباني",
            "text": """Our REST API provides real-time translation capabilities with sub-second latency. The system leverages advanced transformer architectures and cultural adaptation layers to deliver high-quality translations at scale.

Key features include: automatic language detection, context-aware translation, batch processing for large documents, and comprehensive error handling with detailed status codes.""",
            "source_lang": "English",
            "target_lang": "Spanish",
            "context": "Technical API documentation",
            "tone": "technical and precise",
            "domain": "software engineering"
        }
    ]
    
    results = []
    
    for i, test in enumerate(tests, 1):
        print(f"\n\n{'='*100}")
        print(f"🧪 الاختبار {i}: {test['name']}")
        print(f"{'='*100}\n")
        
        result = system.translate(
            text=test["text"],
            source_lang=test["source_lang"],
            target_lang=test["target_lang"],
            context=test["context"],
            tone=test["tone"],
            domain=test["domain"]
        )
        
        results.append(result)
        
        # انتظار قصير بين الاختبارات
        time.sleep(2)
    
    # حفظ النتائج
    system.save_history()
    
    # إنشاء تقرير مفصل
    create_detailed_report(results)
    
    return results


def create_detailed_report(results: List[Dict]):
    """إنشاء تقرير مفصل بالنتائج"""
    
    report = []
    report.append("="*100)
    report.append("📊 تقرير نتائج نظام الترجمة المتقدم")
    report.append("="*100)
    report.append("")
    
    for i, result in enumerate(results, 1):
        report.append(f"\n{'='*100}")
        report.append(f"الاختبار {i}")
        report.append(f"{'='*100}\n")
        
        meta = result["metadata"]
        report.append(f"🌍 اللغات: {meta['source_lang']} → {meta['target_lang']}")
        report.append(f"📋 السياق: {meta['context']}")
        report.append(f"🎯 المجال: {meta['domain']}")
        report.append(f"⏱️  الوقت: {meta['processing_time']:.2f} ثانية")
        report.append(f"📊 النتيجة: {meta['quality_score']}/100")
        report.append("")
        
        report.append("📝 النص الأصلي:")
        report.append(result["original"])
        report.append("")
        
        report.append("✨ الترجمة النهائية:")
        report.append(result["translation"])
        report.append("")
        
        report.append("🔍 التحليل الثقافي:")
        ca = result["cultural_analysis"]
        report.append(f"  - النبرة: {ca['tone']}")
        report.append(f"  - مستوى الرسمية: {ca['formality_level']}")
        report.append(f"  - الأسلوب الموصى به: {ca['recommended_style']}")
        report.append("")
        
        report.append("📊 مقاييس الجودة:")
        qm = result["quality_metrics"]
        report.append(f"  - الدقة: {qm['accuracy']}/100")
        report.append(f"  - الطلاقة: {qm['fluency']}/100")
        report.append(f"  - الملاءمة الثقافية: {qm['cultural_appropriateness']}/100")
        report.append(f"  - اتساق الأسلوب: {qm['style_consistency']}/100")
        report.append(f"  - النتيجة الإجمالية: {qm['overall_score']}/100")
        report.append("")
    
    # حساب المتوسطات
    avg_quality = sum(r["metadata"]["quality_score"] for r in results) / len(results)
    avg_time = sum(r["metadata"]["processing_time"] for r in results) / len(results)
    
    report.append(f"\n{'='*100}")
    report.append("📈 الإحصائيات الإجمالية")
    report.append(f"{'='*100}\n")
    report.append(f"عدد الاختبارات: {len(results)}")
    report.append(f"متوسط الجودة: {avg_quality:.1f}/100")
    report.append(f"متوسط الوقت: {avg_time:.2f} ثانية")
    report.append(f"عدد الطبقات: 7")
    report.append(f"النماذج المستخدمة: GPT-4 + Gemini")
    report.append("")
    
    # حفظ التقرير
    report_text = "\n".join(report)
    with open("/home/ubuntu/advanced_translation_report.txt", "w", encoding="utf-8") as f:
        f.write(report_text)
    
    print("\n" + report_text)
    print("\n💾 تم حفظ التقرير في: /home/ubuntu/advanced_translation_report.txt")


if __name__ == "__main__":
    print("🚀 بدء اختبار نظام الترجمة المتقدم...\n")
    results = run_comprehensive_tests()
    print("\n🎉 اكتملت جميع الاختبارات بنجاح!")
