Your Certified Translation is Ready!

Hello {{ $userName }},

Great news! Your official document translation has been completed and certified.

Document Details:
- Document Type: {{ ucwords(str_replace('_', ' ', $documentType)) }}
- Original File: {{ $documentName }}
- From Language: {{ strtoupper($sourceLang) }}
- To Language: {{ strtoupper($targetLang) }}

Certificate ID: {{ $certificateId }}
(Use this ID to verify your document's authenticity)

Download your certified translation:
{{ $downloadUrl }}

Your certified translation includes:
- Official certification seal
- QR code for verification
- Unique certificate ID
- Legal statement of accuracy

You can verify your document anytime at:
{{ url('/verify/' . $certificateId) }}

View all your documents:
{{ url('/official-documents/my-documents') }}

If you have any questions, please contact us at support@culturaltranslate.com

© {{ date('Y') }} CulturalTranslate. All rights reserved.
