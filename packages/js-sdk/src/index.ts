/**
 * CulturalTranslate JavaScript SDK
 * 
 * Official SDK for integrating CulturalTranslate API
 * 
 * @example
 * ```javascript
 * const CulturalTranslate = require('culturaltranslate-js-sdk');
 * 
 * const client = new CulturalTranslate({
 *   apiKey: 'your-api-key',
 *   baseURL: 'https://api.culturaltranslate.com'
 * });
 * 
 * // Translate text
 * const result = await client.translate({
 *   text: 'Hello World',
 *   source_language: 'en',
 *   target_language: 'ar'
 * });
 * ```
 */

import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';

export interface CulturalTranslateConfig {
  apiKey: string;
  baseURL?: string;
  timeout?: number;
}

export interface TranslationRequest {
  text: string;
  source_language: string;
  target_language: string;
  context?: string;
  domain?: string;
  brand_voice_id?: number;
  apply_glossary?: boolean;
}

export interface TranslationResponse {
  success: boolean;
  translation: string;
  original_text: string;
  source_language: string;
  target_language: string;
  context_analysis?: any;
  brand_voice_applied?: boolean;
  metadata?: any;
}

export interface DocumentUploadRequest {
  file: Buffer | Blob;
  filename: string;
  source_language: string;
  target_languages: string[];
  document_type: string;
  priority?: 'normal' | 'high' | 'urgent';
}

export interface CertificateVerificationRequest {
  certificate_id: string;
}

export interface CertificateVerificationResponse {
  valid: boolean;
  status: string;
  certificate_id: string;
  issue_date?: string;
  translator?: any;
  partner?: any;
  verification_count: number;
}

export class CulturalTranslate {
  private client: AxiosInstance;
  private apiKey: string;

  constructor(config: CulturalTranslateConfig) {
    this.apiKey = config.apiKey;

    this.client = axios.create({
      baseURL: config.baseURL || 'https://api.culturaltranslate.com',
      timeout: config.timeout || 30000,
      headers: {
        'Authorization': `Bearer ${this.apiKey}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });
  }

  /**
   * Translate text with cultural context
   */
  async translate(request: TranslationRequest): Promise<TranslationResponse> {
    try {
      const response = await this.client.post('/v1/translate', request);
      return response.data;
    } catch (error: any) {
      throw new Error(`Translation failed: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Upload document for translation
   */
  async uploadDocument(request: DocumentUploadRequest): Promise<any> {
    try {
      const formData = new FormData();
      formData.append('file', request.file as any, request.filename);
      formData.append('source_language', request.source_language);
      formData.append('target_languages', JSON.stringify(request.target_languages));
      formData.append('document_type', request.document_type);
      if (request.priority) {
        formData.append('priority', request.priority);
      }

      const response = await this.client.post('/v1/documents', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      return response.data;
    } catch (error: any) {
      throw new Error(`Document upload failed: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Get document status
   */
  async getDocumentStatus(documentId: number): Promise<any> {
    try {
      const response = await this.client.get(`/v1/documents/${documentId}`);
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to get document status: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Verify certificate
   */
  async verifyCertificate(request: CertificateVerificationRequest): Promise<CertificateVerificationResponse> {
    try {
      const response = await this.client.get(`/v1/verify/${request.certificate_id}`);
      return response.data;
    } catch (error: any) {
      throw new Error(`Certificate verification failed: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Get brand voices
   */
  async getBrandVoices(): Promise<any> {
    try {
      const response = await this.client.get('/v1/brand-voices');
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to get brand voices: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Create brand voice
   */
  async createBrandVoice(data: any): Promise<any> {
    try {
      const response = await this.client.post('/v1/brand-voices', data);
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to create brand voice: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Get glossary terms
   */
  async getGlossary(brandVoiceId: number): Promise<any> {
    try {
      const response = await this.client.get(`/v1/brand-voices/${brandVoiceId}/glossary`);
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to get glossary: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Add glossary term
   */
  async addGlossaryTerm(brandVoiceId: number, data: any): Promise<any> {
    try {
      const response = await this.client.post(`/v1/brand-voices/${brandVoiceId}/glossary`, data);
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to add glossary term: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Analyze context
   */
  async analyzeContext(text: string, sourceLanguage: string, targetLanguage: string): Promise<any> {
    try {
      const response = await this.client.post('/v1/analyze-context', {
        text,
        source_language: sourceLanguage,
        target_language: targetLanguage
      });
      return response.data;
    } catch (error: any) {
      throw new Error(`Context analysis failed: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Get usage statistics
   */
  async getUsageStats(startDate?: string, endDate?: string): Promise<any> {
    try {
      const params: any = {};
      if (startDate) params.start_date = startDate;
      if (endDate) params.end_date = endDate;

      const response = await this.client.get('/v1/usage-stats', { params });
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to get usage stats: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Configure webhook
   */
  async configureWebhook(url: string, events: string[], secret: string): Promise<any> {
    try {
      const response = await this.client.post('/v1/webhooks', {
        url,
        events,
        secret
      });
      return response.data;
    } catch (error: any) {
      throw new Error(`Failed to configure webhook: ${error.response?.data?.message || error.message}`);
    }
  }

  /**
   * Batch translate multiple texts
   */
  async batchTranslate(requests: TranslationRequest[]): Promise<TranslationResponse[]> {
    try {
      const response = await this.client.post('/v1/translate/batch', { translations: requests });
      return response.data.translations;
    } catch (error: any) {
      throw new Error(`Batch translation failed: ${error.response?.data?.message || error.message}`);
    }
  }
}

export default CulturalTranslate;
