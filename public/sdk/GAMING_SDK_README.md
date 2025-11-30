# Cultural Translate Gaming SDK

Real-time translation SDK for game developers. Supports voice and text translation with cultural adaptation.

## Features

- ✅ Real-time voice translation
- ✅ Text translation with cultural context
- ✅ WebSocket support for low latency
- ✅ Multiple platform support (Web, Unity, Unreal)
- ✅ Easy integration
- ✅ Cultural adaptation levels

## Installation

### JavaScript (Web Games)

```html
<script src="https://culturaltranslate.com/sdk/cultural-translate-sdk.js"></script>
```

Or via npm:

```bash
npm install @culturaltranslate/gaming-sdk
```

### Unity

1. Download `CulturalTranslateSDK.cs`
2. Place it in your Unity project's `Assets/Scripts` folder
3. Attach the script to a GameObject in your scene

### Unreal Engine

Coming soon...

## Quick Start

### JavaScript

```javascript
// Initialize SDK
const sdk = new CulturalTranslateSDK({
    apiKey: 'your_api_key',
    gameId: 'your_game_id',
    baseUrl: 'https://culturaltranslate.com/api'
});

// Set up callbacks
sdk.on('translation', (result) => {
    console.log('Translation:', result.translated_text);
});

sdk.on('error', (error) => {
    console.error('Error:', error);
});

// Create session
await sdk.createSession({
    sourceLanguage: 'en',
    targetLanguage: 'ar',
    culturalLevel: 'high'
});

// Translate text
const translation = await sdk.translateText('Hello, how are you?');

// Start voice recording
await sdk.startVoiceRecording();

// Stop and translate
sdk.stopVoiceRecording();

// End session
await sdk.endSession();
```

### Unity

```csharp
using CulturalTranslate;

public class GameManager : MonoBehaviour
{
    private CulturalTranslateSDK sdk;
    
    void Start()
    {
        // Get SDK component
        sdk = GetComponent<CulturalTranslateSDK>();
        
        // Set configuration
        sdk.apiKey = "your_api_key";
        sdk.gameId = "your_game_id";
        sdk.sourceLanguage = "en";
        sdk.targetLanguage = "ar";
        
        // Subscribe to events
        sdk.OnTranslation += HandleTranslation;
        sdk.OnError += HandleError;
        
        // Create session
        sdk.CreateSession();
    }
    
    void HandleTranslation(TranslationResult result)
    {
        Debug.Log($"Translation: {result.translatedText}");
    }
    
    void HandleError(string error)
    {
        Debug.LogError($"Error: {error}");
    }
    
    public void TranslatePlayerMessage(string message)
    {
        sdk.TranslateText(message, (translation) => {
            // Display translation in chat
            DisplayInChat(translation);
        });
    }
}
```

## API Reference

### JavaScript SDK

#### Constructor

```javascript
new CulturalTranslateSDK(config)
```

**Parameters:**
- `config.apiKey` (string): Your API key
- `config.gameId` (string): Your game ID
- `config.baseUrl` (string, optional): API base URL
- `config.wsUrl` (string, optional): WebSocket URL

#### Methods

##### createSession(options)
Create a new translation session.

**Parameters:**
- `options.sourceLanguage` (string): Source language code (e.g., 'en')
- `options.targetLanguage` (string): Target language code (e.g., 'ar')
- `options.culturalLevel` (string): 'minimal', 'standard', 'high', 'maximum'

**Returns:** Promise<Session>

##### translateText(text, options)
Translate text.

**Parameters:**
- `text` (string): Text to translate
- `options` (object, optional): Translation options

**Returns:** Promise<string>

##### startVoiceRecording()
Start recording voice for translation.

**Returns:** Promise<void>

##### stopVoiceRecording()
Stop recording and translate.

**Returns:** void

##### endSession()
End the current session.

**Returns:** Promise<void>

#### Events

```javascript
sdk.on('translation', (result) => {});
sdk.on('error', (error) => {});
sdk.on('connect', () => {});
sdk.on('disconnect', () => {});
```

### Unity SDK

#### Properties

- `apiKey` (string): Your API key
- `gameId` (string): Your game ID
- `sourceLanguage` (string): Source language
- `targetLanguage` (string): Target language

#### Methods

- `CreateSession(Action<string> onSuccess)`
- `TranslateText(string text, Action<string> onSuccess)`
- `TranslateVoice(byte[] audioData, Action<string> onSuccess)`
- `EndSession()`

#### Events

- `OnTranslation` (Action<TranslationResult>)
- `OnError` (Action<string>)
- `OnConnected` (Action)
- `OnDisconnected` (Action)

## Use Cases

### Multiplayer Chat Translation

```javascript
// Player sends a message
playerChat.on('message', async (message) => {
    const translation = await sdk.translateText(message);
    broadcastToOtherPlayers(translation);
});
```

### Voice Chat Translation

```javascript
// Push-to-talk button
voiceButton.on('press', () => {
    sdk.startVoiceRecording();
});

voiceButton.on('release', () => {
    sdk.stopVoiceRecording();
});

sdk.on('translation', (result) => {
    playAudio(result.audioUrl);
    displaySubtitle(result.translated_text);
});
```

### In-Game UI Translation

```javascript
// Translate UI elements
async function translateUI() {
    const buttons = document.querySelectorAll('.translate');
    for (const button of buttons) {
        const translation = await sdk.translateText(button.textContent);
        button.textContent = translation;
    }
}
```

## Cultural Adaptation Levels

- **minimal**: Basic translation only
- **standard**: Translation with basic cultural context
- **high**: Full cultural adaptation with idioms
- **maximum**: Deep cultural adaptation with regional variations

## Pricing

Visit [culturaltranslate.com/pricing](https://culturaltranslate.com/pricing) for pricing details.

## Support

- Documentation: [culturaltranslate.com/docs](https://culturaltranslate.com/docs)
- Email: support@culturaltranslate.com
- Discord: [discord.gg/culturaltranslate](https://discord.gg/culturaltranslate)

## License

MIT License - See LICENSE file for details.
