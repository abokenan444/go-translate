# Contributing to Cultural Translate Platform

First off, thank you for considering contributing to Cultural Translate Platform! 🎉

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Pull Request Process](#pull-request-process)
- [Coding Standards](#coding-standards)
- [Testing Guidelines](#testing-guidelines)

## 📜 Code of Conduct

This project is proprietary and contributions are by invitation only. Please respect:

- Professional communication
- Confidentiality of proprietary code
- Non-disclosure agreements (if applicable)

## 🤝 How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title** describing the issue
- **Detailed description** of the problem
- **Steps to reproduce** the behavior
- **Expected vs actual behavior**
- **Environment details** (OS, browser, versions)
- **Screenshots** (if applicable)

### Suggesting Features

Feature suggestions are welcome! Please provide:

- **Clear description** of the feature
- **Use cases** and benefits
- **Mockups or examples** (if applicable)
- **Priority level** and reasoning

## 🛠️ Development Setup

### Prerequisites

- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 18.x
- Git

### Local Setup

```bash
# Clone the repository
git clone https://github.com/abokenan444/go-translate.git
cd go-translate

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start development server
php artisan serve
```

## 🔄 Pull Request Process

1. **Fork the repository** (if external contributor)
2. **Create a feature branch**: `git checkout -b feature/your-feature-name`
3. **Make your changes** following coding standards
4. **Write or update tests** for your changes
5. **Run test suite**: `php artisan test`
6. **Commit your changes**: `git commit -m "Add: feature description"`
7. **Push to branch**: `git push origin feature/your-feature-name`
8. **Create Pull Request** with detailed description

### PR Requirements

- [ ] Code follows project coding standards
- [ ] All tests pass
- [ ] New features include tests
- [ ] Documentation updated (if needed)
- [ ] No merge conflicts
- [ ] Descriptive commit messages

## 💻 Coding Standards

### PHP (PSR-12)

```php
<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Collection;

class TranslationService
{
    /**
     * Process translation request.
     *
     * @param string $text
     * @param string $targetLanguage
     * @return Translation
     */
    public function translate(string $text, string $targetLanguage): Translation
    {
        // Implementation
    }
}
```

### Blade Templates

```blade
{{-- Use proper indentation --}}
<div class="container">
    <h1>{{ $title }}</h1>
    
    @foreach ($items as $item)
        <div class="item">
            {{ $item->name }}
        </div>
    @endforeach
</div>
```

### JavaScript

```javascript
// Use modern ES6+ syntax
const translateText = async (text, language) => {
    try {
        const response = await fetch('/api/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ text, language }),
        });
        
        return await response.json();
    } catch (error) {
        console.error('Translation error:', error);
        throw error;
    }
};
```

## 🧪 Testing Guidelines

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class TranslationTest extends TestCase
{
    /** @test */
    public function user_can_translate_text()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/api/translate', [
                'text' => 'Hello World',
                'target_language' => 'ar',
            ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'translated_text',
                'source_language',
                'target_language',
            ]);
    }
}
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/TranslationTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter=user_can_translate_text
```

## 📝 Commit Message Guidelines

Use clear, descriptive commit messages:

```
Add: New feature or functionality
Fix: Bug fix
Update: Changes to existing feature
Refactor: Code refactoring
Docs: Documentation changes
Test: Adding or updating tests
Style: Formatting, missing semicolons, etc.
Chore: Maintenance tasks
```

**Examples:**
```
Add: Voice translation feature
Fix: Dashboard statistics not loading
Update: OpenAI API integration to GPT-4
Refactor: Translation service class structure
Docs: Update API documentation
Test: Add translation history tests
```

## 🔍 Code Review Process

All contributions go through code review:

1. **Automated checks** run on PR creation
2. **Manual review** by maintainers
3. **Feedback** provided for improvements
4. **Approval** required before merge
5. **Merge** into main branch

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [OpenAI API Documentation](https://platform.openai.com/docs)
- [Project Wiki](https://github.com/abokenan444/go-translate/wiki)

## 📞 Questions?

- **Email**: abokenan4@gmail.com
- **Support**: support@culturaltranslate.com

## 🙏 Thank You!

Your contributions make Cultural Translate Platform better for everyone. Thank you for your time and effort! 🌟
