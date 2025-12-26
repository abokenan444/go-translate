"""
CulturalTranslate Python SDK

Official Python SDK for CulturalTranslate API

Example:
    from culturaltranslate import CulturalTranslate

    client = CulturalTranslate(api_key='your-api-key')
    
    result = client.translate(
        text='Hello World',
        source_language='en',
        target_language='ar'
    )
"""

import requests
from typing import Dict, List, Optional, Any
import json


class CulturalTranslateError(Exception):
    """Base exception for CulturalTranslate SDK"""
    pass


class CulturalTranslate:
    """
    CulturalTranslate API Client
    
    Args:
        api_key (str): Your API key
        base_url (str): API base URL (default: https://api.culturaltranslate.com)
        timeout (int): Request timeout in seconds (default: 30)
    """
    
    def __init__(
        self, 
        api_key: str,
        base_url: str = "https://api.culturaltranslate.com",
        timeout: int = 30
    ):
        self.api_key = api_key
        self.base_url = base_url.rstrip('/')
        self.timeout = timeout
        self.session = requests.Session()
        self.session.headers.update({
            'Authorization': f'Bearer {api_key}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        })

    def _request(
        self, 
        method: str, 
        endpoint: str, 
        data: Optional[Dict] = None,
        files: Optional[Dict] = None,
        params: Optional[Dict] = None
    ) -> Dict:
        """Make HTTP request to API"""
        url = f"{self.base_url}{endpoint}"
        
        try:
            if files:
                # Remove Content-Type header for multipart/form-data
                headers = {k: v for k, v in self.session.headers.items() if k != 'Content-Type'}
                response = self.session.request(
                    method=method,
                    url=url,
                    data=data,
                    files=files,
                    params=params,
                    timeout=self.timeout,
                    headers=headers
                )
            else:
                response = self.session.request(
                    method=method,
                    url=url,
                    json=data,
                    params=params,
                    timeout=self.timeout
                )
            
            response.raise_for_status()
            return response.json()
            
        except requests.exceptions.HTTPError as e:
            error_message = f"API request failed: {e}"
            if e.response is not None:
                try:
                    error_data = e.response.json()
                    error_message = error_data.get('message', error_message)
                except:
                    pass
            raise CulturalTranslateError(error_message)
        except requests.exceptions.RequestException as e:
            raise CulturalTranslateError(f"Network error: {e}")

    def translate(
        self,
        text: str,
        source_language: str,
        target_language: str,
        context: Optional[str] = None,
        domain: Optional[str] = None,
        brand_voice_id: Optional[int] = None,
        apply_glossary: bool = True
    ) -> Dict:
        """
        Translate text with cultural context
        
        Args:
            text: Text to translate
            source_language: Source language code (e.g., 'en')
            target_language: Target language code (e.g., 'ar')
            context: Additional context for translation
            domain: Domain/industry (e.g., 'legal', 'medical')
            brand_voice_id: Brand voice ID to apply
            apply_glossary: Apply glossary terms
            
        Returns:
            Translation result dict
        """
        data = {
            'text': text,
            'source_language': source_language,
            'target_language': target_language,
            'apply_glossary': apply_glossary
        }
        
        if context:
            data['context'] = context
        if domain:
            data['domain'] = domain
        if brand_voice_id:
            data['brand_voice_id'] = brand_voice_id
            
        return self._request('POST', '/v1/translate', data=data)

    def upload_document(
        self,
        file_path: str,
        source_language: str,
        target_languages: List[str],
        document_type: str,
        priority: str = 'normal'
    ) -> Dict:
        """
        Upload document for translation
        
        Args:
            file_path: Path to document file
            source_language: Source language code
            target_languages: List of target language codes
            document_type: Type of document
            priority: Priority level (normal, high, urgent)
            
        Returns:
            Upload result dict
        """
        with open(file_path, 'rb') as f:
            files = {'file': f}
            data = {
                'source_language': source_language,
                'target_languages': json.dumps(target_languages),
                'document_type': document_type,
                'priority': priority
            }
            
            return self._request('POST', '/v1/documents', data=data, files=files)

    def get_document_status(self, document_id: int) -> Dict:
        """
        Get document translation status
        
        Args:
            document_id: Document ID
            
        Returns:
            Document status dict
        """
        return self._request('GET', f'/v1/documents/{document_id}')

    def verify_certificate(self, certificate_id: str) -> Dict:
        """
        Verify translation certificate
        
        Args:
            certificate_id: Certificate ID (e.g., 'CT-2025-ABC123')
            
        Returns:
            Verification result dict
        """
        return self._request('GET', f'/v1/verify/{certificate_id}')

    def get_brand_voices(self) -> List[Dict]:
        """
        Get all brand voices
        
        Returns:
            List of brand voice dicts
        """
        result = self._request('GET', '/v1/brand-voices')
        return result.get('brand_voices', [])

    def create_brand_voice(
        self,
        name: str,
        tone: str,
        formality_level: str,
        preferred_vocabulary: Optional[Dict] = None,
        cultural_preferences: Optional[Dict] = None
    ) -> Dict:
        """
        Create new brand voice
        
        Args:
            name: Brand voice name
            tone: Tone (professional, casual, friendly, etc.)
            formality_level: Formality level (formal, informal, neutral)
            preferred_vocabulary: Dict with 'use' and 'avoid' lists
            cultural_preferences: Cultural preferences dict
            
        Returns:
            Created brand voice dict
        """
        data = {
            'name': name,
            'tone': tone,
            'formality_level': formality_level
        }
        
        if preferred_vocabulary:
            data['preferred_vocabulary'] = preferred_vocabulary
        if cultural_preferences:
            data['cultural_preferences'] = cultural_preferences
            
        return self._request('POST', '/v1/brand-voices', data=data)

    def get_glossary(self, brand_voice_id: int) -> List[Dict]:
        """
        Get glossary terms for brand voice
        
        Args:
            brand_voice_id: Brand voice ID
            
        Returns:
            List of glossary term dicts
        """
        result = self._request('GET', f'/v1/brand-voices/{brand_voice_id}/glossary')
        return result.get('glossary_terms', [])

    def add_glossary_term(
        self,
        brand_voice_id: int,
        source_term: str,
        target_term: str,
        source_language: str,
        target_language: str,
        context: Optional[str] = None
    ) -> Dict:
        """
        Add glossary term to brand voice
        
        Args:
            brand_voice_id: Brand voice ID
            source_term: Term in source language
            target_term: Term in target language
            source_language: Source language code
            target_language: Target language code
            context: Usage context
            
        Returns:
            Created glossary term dict
        """
        data = {
            'source_term': source_term,
            'target_term': target_term,
            'source_language': source_language,
            'target_language': target_language
        }
        
        if context:
            data['context'] = context
            
        return self._request('POST', f'/v1/brand-voices/{brand_voice_id}/glossary', data=data)

    def analyze_context(
        self,
        text: str,
        source_language: str,
        target_language: str
    ) -> Dict:
        """
        Analyze text context (7-layer analysis)
        
        Args:
            text: Text to analyze
            source_language: Source language code
            target_language: Target language code
            
        Returns:
            Context analysis dict with 7 layers
        """
        data = {
            'text': text,
            'source_language': source_language,
            'target_language': target_language
        }
        
        return self._request('POST', '/v1/analyze-context', data=data)

    def get_usage_stats(
        self,
        start_date: Optional[str] = None,
        end_date: Optional[str] = None
    ) -> Dict:
        """
        Get API usage statistics
        
        Args:
            start_date: Start date (YYYY-MM-DD)
            end_date: End date (YYYY-MM-DD)
            
        Returns:
            Usage statistics dict
        """
        params = {}
        if start_date:
            params['start_date'] = start_date
        if end_date:
            params['end_date'] = end_date
            
        return self._request('GET', '/v1/usage-stats', params=params)

    def configure_webhook(
        self,
        url: str,
        events: List[str],
        secret: str
    ) -> Dict:
        """
        Configure webhook endpoint
        
        Args:
            url: Webhook URL
            events: List of event names to subscribe to
            secret: Webhook secret for signature verification
            
        Returns:
            Webhook configuration dict
        """
        data = {
            'url': url,
            'events': events,
            'secret': secret
        }
        
        return self._request('POST', '/v1/webhooks', data=data)

    def batch_translate(
        self,
        translations: List[Dict[str, Any]]
    ) -> List[Dict]:
        """
        Translate multiple texts in batch
        
        Args:
            translations: List of translation request dicts
            
        Returns:
            List of translation result dicts
        """
        data = {'translations': translations}
        result = self._request('POST', '/v1/translate/batch', data=data)
        return result.get('translations', [])


# Convenience function for quick translation
def translate(
    text: str,
    source_language: str,
    target_language: str,
    api_key: str
) -> str:
    """
    Quick translation function
    
    Args:
        text: Text to translate
        source_language: Source language code
        target_language: Target language code
        api_key: API key
        
    Returns:
        Translated text
    """
    client = CulturalTranslate(api_key=api_key)
    result = client.translate(text, source_language, target_language)
    return result.get('translation', '')
