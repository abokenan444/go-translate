-- Cultural Profiles (Major World Cultures)
INSERT OR IGNORE INTO cultural_profiles (code, name, locale, region, description, values_json, created_at, updated_at) VALUES 
-- Middle East
('ar-SA', 'Saudi Arabian Arabic', 'ar', 'Gulf', 'Formal, respectful Arabic with Gulf dialects', '{"formality":"high","honorifics":"required","directness":"indirect"}', datetime('now'), datetime('now')),
('ar-EG', 'Egyptian Arabic', 'ar', 'North Africa', 'Informal, widely understood dialect', '{"formality":"medium","humor":"appreciated","directness":"moderate"}', datetime('now'), datetime('now')),
('ar-AE', 'Emirati Arabic', 'ar', 'Gulf', 'Gulf dialect with modern business tone', '{"formality":"high","business":"formal","directness":"indirect"}', datetime('now'), datetime('now')),
('ar-MA', 'Moroccan Arabic', 'ar', 'Maghreb', 'Darija dialect with French influence', '{"formality":"medium","french_influence":"high","directness":"moderate"}', datetime('now'), datetime('now')),
('ar-LB', 'Lebanese Arabic', 'ar', 'Levant', 'Levantine dialect, cosmopolitan', '{"formality":"medium","multilingual":"common","directness":"moderate"}', datetime('now'), datetime('now')),

-- European Languages
('en-US', 'American English', 'en', 'North America', 'Direct, informal, action-oriented', '{"formality":"low","directness":"high","innovation":"valued"}', datetime('now'), datetime('now')),
('en-GB', 'British English', 'en', 'Europe', 'Formal, polite, understated', '{"formality":"high","politeness":"valued","understatement":"common"}', datetime('now'), datetime('now')),
('fr-FR', 'French (France)', 'fr', 'Europe', 'Formal, sophisticated, detail-oriented', '{"formality":"very_high","politeness":"essential","precision":"valued"}', datetime('now'), datetime('now')),
('de-DE', 'German', 'de', 'Europe', 'Formal, precise, direct', '{"formality":"high","precision":"critical","directness":"high"}', datetime('now'), datetime('now')),
('es-ES', 'Spanish (Spain)', 'es', 'Europe', 'Formal with regional pride', '{"formality":"high","regional_identity":"strong","directness":"moderate"}', datetime('now'), datetime('now')),
('it-IT', 'Italian', 'it', 'Europe', 'Expressive, relationship-focused', '{"formality":"medium","emotion":"valued","relationships":"priority"}', datetime('now'), datetime('now')),

-- Asian Languages
('zh-CN', 'Simplified Chinese', 'zh', 'East Asia', 'Formal, hierarchical, indirect', '{"formality":"very_high","hierarchy":"critical","indirectness":"high"}', datetime('now'), datetime('now')),
('zh-TW', 'Traditional Chinese', 'zh', 'East Asia', 'Traditional, courteous, relationship-based', '{"formality":"very_high","tradition":"valued","relationships":"critical"}', datetime('now'), datetime('now')),
('ja-JP', 'Japanese', 'ja', 'East Asia', 'Extremely formal, indirect, context-dependent', '{"formality":"extreme","indirectness":"very_high","context":"essential"}', datetime('now'), datetime('now')),
('ko-KR', 'Korean', 'ko', 'East Asia', 'Hierarchical, formal, respectful', '{"formality":"very_high","hierarchy":"critical","respect":"essential"}', datetime('now'), datetime('now')),
('hi-IN', 'Hindi', 'hi', 'South Asia', 'Respectful, relationship-oriented', '{"formality":"high","respect":"valued","relationships":"important"}', datetime('now'), datetime('now')),

-- Latin American
('es-MX', 'Mexican Spanish', 'es', 'Latin America', 'Warm, respectful, relationship-focused', '{"formality":"medium","warmth":"valued","relationships":"important"}', datetime('now'), datetime('now')),
('es-AR', 'Argentine Spanish', 'es', 'Latin America', 'Informal, expressive, European-influenced', '{"formality":"low","expressiveness":"high","italian_influence":"notable"}', datetime('now'), datetime('now')),
('pt-BR', 'Brazilian Portuguese', 'pt', 'Latin America', 'Warm, informal, relationship-based', '{"formality":"low","warmth":"high","relationships":"valued"}', datetime('now'), datetime('now')),

-- Others
('ru-RU', 'Russian', 'ru', 'Eastern Europe', 'Formal in business, direct communication', '{"formality":"high","directness":"high","business":"formal"}', datetime('now'), datetime('now')),
('tr-TR', 'Turkish', 'tr', 'Middle East', 'Formal with Ottoman influences', '{"formality":"high","respect":"valued","tradition":"important"}', datetime('now'), datetime('now'));
