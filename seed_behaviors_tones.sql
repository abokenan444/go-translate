-- Additional Industry Behaviors and Emotional Tones
INSERT OR IGNORE INTO industry_behaviors (industry_key, behavior_key, name, description, guidelines, created_at, updated_at) VALUES 
('technology', 'technical_precision', 'Technical Precision', 'Maintain accuracy in technical terminology', 'Preserve API endpoints, code syntax, version numbers exactly. Use consistent technical terms.', datetime('now'), datetime('now')),
('healthcare', 'medical_accuracy', 'Medical Accuracy', 'Ensure medical information is precise and safe', 'Maintain drug names, dosages, medical procedures exactly. Include safety warnings.', datetime('now'), datetime('now')),
('finance', 'regulatory_compliance', 'Regulatory Compliance', 'Follow financial regulations', 'Include mandatory disclosures, preserve financial terms, maintain legal accuracy.', datetime('now'), datetime('now')),
('ecommerce', 'persuasive_selling', 'Persuasive Marketing', 'Encourage purchase decisions', 'Use benefit-focused language, create urgency, highlight value propositions.', datetime('now'), datetime('now')),
('education', 'clear_instruction', 'Clear Instruction', 'Make learning content accessible', 'Use simple explanations, provide examples, break down complex concepts.', datetime('now'), datetime('now'));

-- Emotional Tones for Different Contexts
INSERT OR IGNORE INTO emotional_tones (key, name, description, tone_markers, use_cases, created_at, updated_at) VALUES 
('formal_professional', 'Formal Professional', 'Business and corporate communications', '{"politeness":"high","respect":"essential","clarity":"priority"}', 'Business emails, corporate websites, professional services', datetime('now'), datetime('now')),
('casual_friendly', 'Casual Friendly', 'Informal and approachable', '{"warmth":"high","humor":"acceptable","informality":"moderate"}', 'Social media, lifestyle brands, consumer apps', datetime('now'), datetime('now')),
('empathetic_caring', 'Empathetic Caring', 'Supportive and understanding', '{"empathy":"high","reassurance":"valued","warmth":"essential"}', 'Healthcare, counseling, customer support', datetime('now'), datetime('now')),
('urgent_action', 'Urgent Action-Oriented', 'Motivating immediate response', '{"urgency":"high","directness":"strong","motivation":"key"}', 'Marketing campaigns, limited offers, emergency alerts', datetime('now'), datetime('now')),
('educational_patient', 'Educational Patient', 'Teaching and explaining', '{"clarity":"essential","patience":"valued","encouragement":"important"}', 'E-learning, tutorials, documentation', datetime('now'), datetime('now')),
('luxurious_aspirational', 'Luxurious Aspirational', 'Premium and exclusive', '{"sophistication":"high","exclusivity":"valued","quality":"emphasized"}', 'Luxury brands, premium services, high-end products', datetime('now'), datetime('now')),
('trustworthy_authoritative', 'Trustworthy Authoritative', 'Expert and credible', '{"expertise":"demonstrated","trust":"built","authority":"established"}', 'Professional services, financial services, medical advice', datetime('now'), datetime('now'));
