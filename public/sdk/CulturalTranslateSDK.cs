using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
using UnityEngine;
using UnityEngine.Networking;

namespace CulturalTranslate
{
    /// <summary>
    /// Cultural Translate SDK for Unity
    /// Real-time translation for multiplayer games
    /// </summary>
    public class CulturalTranslateSDK : MonoBehaviour
    {
        [Header("Configuration")]
        public string apiKey;
        public string gameId;
        public string baseUrl = "https://culturaltranslate.com/api";
        
        [Header("Settings")]
        public string sourceLanguage = "en";
        public string targetLanguage = "ar";
        public string sourceCulture = "en-US";
        public string targetCulture = "ar-SA";
        public string culturalAdaptationLevel = "standard";
        
        // Events
        public event Action<TranslationResult> OnTranslation;
        public event Action<string> OnError;
        public event Action OnConnected;
        public event Action OnDisconnected;
        
        private string sessionId;
        private bool isInitialized = false;
        
        void Start()
        {
            if (string.IsNullOrEmpty(apiKey) || string.IsNullOrEmpty(gameId))
            {
                Debug.LogError("[CulturalTranslate] API Key and Game ID are required!");
                return;
            }
            
            Debug.Log("[CulturalTranslate] SDK Initialized");
            isInitialized = true;
        }
        
        /// <summary>
        /// Create a new translation session
        /// </summary>
        public void CreateSession(Action<string> onSuccess = null)
        {
            if (!isInitialized)
            {
                OnError?.Invoke("SDK not initialized");
                return;
            }
            
            StartCoroutine(CreateSessionCoroutine(onSuccess));
        }
        
        private IEnumerator CreateSessionCoroutine(Action<string> onSuccess)
        {
            var requestData = new SessionRequest
            {
                source_language = sourceLanguage,
                target_language = targetLanguage,
                source_culture = sourceCulture,
                target_culture = targetCulture,
                type = "gaming",
                cultural_adaptation_level = culturalAdaptationLevel
            };
            
            string jsonData = JsonUtility.ToJson(requestData);
            
            using (UnityWebRequest request = new UnityWebRequest($"{baseUrl}/gaming/sessions", "POST"))
            {
                byte[] bodyRaw = Encoding.UTF8.GetBytes(jsonData);
                request.uploadHandler = new UploadHandlerRaw(bodyRaw);
                request.downloadHandler = new DownloadHandlerBuffer();
                request.SetRequestHeader("Content-Type", "application/json");
                request.SetRequestHeader("Authorization", $"Bearer {apiKey}");
                request.SetRequestHeader("X-Game-ID", gameId);
                
                yield return request.SendWebRequest();
                
                if (request.result == UnityWebRequest.Result.Success)
                {
                    var response = JsonUtility.FromJson<SessionResponse>(request.downloadHandler.text);
                    sessionId = response.public_id;
                    
                    Debug.Log($"[CulturalTranslate] Session created: {sessionId}");
                    OnConnected?.Invoke();
                    onSuccess?.Invoke(sessionId);
                }
                else
                {
                    Debug.LogError($"[CulturalTranslate] Error creating session: {request.error}");
                    OnError?.Invoke(request.error);
                }
            }
        }
        
        /// <summary>
        /// Translate text
        /// </summary>
        public void TranslateText(string text, Action<string> onSuccess = null)
        {
            if (string.IsNullOrEmpty(sessionId))
            {
                OnError?.Invoke("No active session. Create a session first.");
                return;
            }
            
            StartCoroutine(TranslateTextCoroutine(text, onSuccess));
        }
        
        private IEnumerator TranslateTextCoroutine(string text, Action<string> onSuccess)
        {
            var requestData = new TextTranslationRequest
            {
                session_id = sessionId,
                text = text,
                source_language = sourceLanguage,
                target_language = targetLanguage
            };
            
            string jsonData = JsonUtility.ToJson(requestData);
            
            using (UnityWebRequest request = new UnityWebRequest($"{baseUrl}/gaming/translate/text", "POST"))
            {
                byte[] bodyRaw = Encoding.UTF8.GetBytes(jsonData);
                request.uploadHandler = new UploadHandlerRaw(bodyRaw);
                request.downloadHandler = new DownloadHandlerBuffer();
                request.SetRequestHeader("Content-Type", "application/json");
                request.SetRequestHeader("Authorization", $"Bearer {apiKey}");
                request.SetRequestHeader("X-Game-ID", gameId);
                
                yield return request.SendWebRequest();
                
                if (request.result == UnityWebRequest.Result.Success)
                {
                    var response = JsonUtility.FromJson<TranslationResponse>(request.downloadHandler.text);
                    
                    Debug.Log($"[CulturalTranslate] Translation: {text} -> {response.translated_text}");
                    
                    var result = new TranslationResult
                    {
                        sourceText = text,
                        translatedText = response.translated_text,
                        sourceLanguage = sourceLanguage,
                        targetLanguage = targetLanguage
                    };
                    
                    OnTranslation?.Invoke(result);
                    onSuccess?.Invoke(response.translated_text);
                }
                else
                {
                    Debug.LogError($"[CulturalTranslate] Translation error: {request.error}");
                    OnError?.Invoke(request.error);
                }
            }
        }
        
        /// <summary>
        /// Translate audio (voice)
        /// </summary>
        public void TranslateVoice(byte[] audioData, Action<string> onSuccess = null)
        {
            if (string.IsNullOrEmpty(sessionId))
            {
                OnError?.Invoke("No active session. Create a session first.");
                return;
            }
            
            StartCoroutine(TranslateVoiceCoroutine(audioData, onSuccess));
        }
        
        private IEnumerator TranslateVoiceCoroutine(byte[] audioData, Action<string> onSuccess)
        {
            List<IMultipartFormSection> formData = new List<IMultipartFormSection>();
            formData.Add(new MultipartFormDataSection("session_id", sessionId));
            formData.Add(new MultipartFormFileSection("audio", audioData, "voice.wav", "audio/wav"));
            
            using (UnityWebRequest request = UnityWebRequest.Post($"{baseUrl}/gaming/translate/voice", formData))
            {
                request.SetRequestHeader("Authorization", $"Bearer {apiKey}");
                request.SetRequestHeader("X-Game-ID", gameId);
                
                yield return request.SendWebRequest();
                
                if (request.result == UnityWebRequest.Result.Success)
                {
                    var response = JsonUtility.FromJson<VoiceTranslationResponse>(request.downloadHandler.text);
                    
                    Debug.Log($"[CulturalTranslate] Voice translation completed");
                    
                    var result = new TranslationResult
                    {
                        sourceText = response.source_text,
                        translatedText = response.translated_text,
                        sourceLanguage = sourceLanguage,
                        targetLanguage = targetLanguage,
                        audioUrl = response.audio_url
                    };
                    
                    OnTranslation?.Invoke(result);
                    onSuccess?.Invoke(response.translated_text);
                }
                else
                {
                    Debug.LogError($"[CulturalTranslate] Voice translation error: {request.error}");
                    OnError?.Invoke(request.error);
                }
            }
        }
        
        /// <summary>
        /// End the current session
        /// </summary>
        public void EndSession()
        {
            if (string.IsNullOrEmpty(sessionId))
            {
                return;
            }
            
            StartCoroutine(EndSessionCoroutine());
        }
        
        private IEnumerator EndSessionCoroutine()
        {
            using (UnityWebRequest request = UnityWebRequest.Delete($"{baseUrl}/gaming/sessions/{sessionId}"))
            {
                request.SetRequestHeader("Authorization", $"Bearer {apiKey}");
                request.SetRequestHeader("X-Game-ID", gameId);
                
                yield return request.SendWebRequest();
                
                if (request.result == UnityWebRequest.Result.Success)
                {
                    Debug.Log("[CulturalTranslate] Session ended");
                    sessionId = null;
                    OnDisconnected?.Invoke();
                }
                else
                {
                    Debug.LogError($"[CulturalTranslate] Error ending session: {request.error}");
                }
            }
        }
        
        void OnDestroy()
        {
            EndSession();
        }
    }
    
    // Data classes
    [Serializable]
    public class SessionRequest
    {
        public string source_language;
        public string target_language;
        public string source_culture;
        public string target_culture;
        public string type;
        public string cultural_adaptation_level;
    }
    
    [Serializable]
    public class SessionResponse
    {
        public string public_id;
        public string status;
    }
    
    [Serializable]
    public class TextTranslationRequest
    {
        public string session_id;
        public string text;
        public string source_language;
        public string target_language;
    }
    
    [Serializable]
    public class TranslationResponse
    {
        public string translated_text;
    }
    
    [Serializable]
    public class VoiceTranslationResponse
    {
        public string source_text;
        public string translated_text;
        public string audio_url;
    }
    
    [Serializable]
    public class TranslationResult
    {
        public string sourceText;
        public string translatedText;
        public string sourceLanguage;
        public string targetLanguage;
        public string audioUrl;
    }
}
