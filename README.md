# 🌐 Cultural Translate Platform

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-3.x-FFAA00?style=flat)](https://filamentphp.com)
[![OpenAI](https://img.shields.io/badge/OpenAI-GPT--4-412991?style=flat&logo=openai)](https://openai.com)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-success)](https://culturaltranslate.com)
[![Last Updated](https://img.shields.io/badge/Last%20Updated-2025--12--26-blue)](SYNC_STATUS.md)

<div align="center">

**منصة ترجمة ذكية تحافظ على السياق الثقافي والهوية اللغوية**

**Smart translation platform that preserves cultural context and linguistic identity**

[Website](https://culturaltranslate.com) • [Documentation](SYNC_INDEX.md) • [Admin Panel](https://admin.culturaltranslate.com) • [Support](mailto:support@culturaltranslate.com)

</div>

---

## 🌍 Overview

**Cultural Translate** is an enterprise-grade AI-powered translation platform that goes beyond literal translation to preserve cultural context, emotional tone, and brand voice. Built with Laravel 11 and powered by OpenAI's GPT-4, it provides professional translation services for individuals, businesses, government entities, and enterprises.

### ⚡ Quick Actions

| Action | Link | Description |
|--------|------|-------------|
| 🚀 Deploy to Server | [README_SYNC_AR.md](README_SYNC_AR.md) | نسخ وتشغيل أمر واحد - مزامنة كاملة |
| 📚 Sync Documentation | [SYNC_INDEX.md](SYNC_INDEX.md) | Master index of all sync files |
| 📊 Platform Status | [PLATFORM_VERIFICATION_REPORT.md](PLATFORM_VERIFICATION_REPORT.md) | Complete health check report |
| 🔧 Admin Access | [admin.culturaltranslate.com](https://admin.culturaltranslate.com) | Filament admin panel |
| 🌐 Live Platform | [culturaltranslate.com](https://culturaltranslate.com) | Production website |

---

## 📑 Table of Contents

- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Quick Start](#-quick-start)
  - [Server Deployment](#for-server-deployment--fastest-method)
  - [Local Development](#for-local-development)
- [Requirements](#-requirements)
- [Configuration](#-configuration)
- [Deployment & Synchronization](#-deployment--synchronization)
- [Testing](#-testing)
- [Features Overview](#-features-overview)
- [Security & Compliance](#-security--compliance)
- [Admin Panel Access](#️-admin-panel-access)
- [Support & Contact](#-support--contact)
- [Roadmap](#️-roadmap)
- [Version History](#-version-history)
- [License](#-license)

---

### 🎯 Key Features

- **Cultural Adaptation**: Preserves cultural nuances and context
- **Multi-format Support**: Text, voice, images, documents, and videos
- **Real-time Translation**: Live voice translation for meetings
- **Team Collaboration**: Advanced project management and team workflows
- **API Integration**: Comprehensive RESTful API for developers
- **Analytics Dashboard**: Insights and performance metrics
- **Custom Dictionaries**: Brand-specific terminology management

## 🚀 Tech Stack

- **Backend**: Laravel 11.x (PHP 8.3)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: SQLite (Production-ready)
- **AI Engine**: OpenAI GPT-4 / GPT-4o-mini
- **Admin Panel**: Filament 3.x
- **Real-time**: Laravel Broadcasting, Pusher
- **Queue**: Laravel Queue with Redis

## 📋 Requirements

- PHP >= 8.3
- Composer
## 🚀 Quick Start

### For Server Deployment (⚡ Fastest Method)

**نسخ وتشغيل أمر واحد فقط - مزامنة كاملة في 3-5 دقائق:**

```bash
ssh root@145.14.158.101 "cd /var/www/cultural-translate-platform && git pull origin main && composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && sudo systemctl restart nginx php8.3-fpm"
```

**📘 التفاصيل الكاملة**: [README_SYNC_AR.md](README_SYNC_AR.md)

---

### For Local Development

#### 1. Clone the Repository

```bash
git clone https://github.com/abokenan444/go-translate.git
cd go-translate
```

#### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

#### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env file with:
# - OpenAI API Key (OPENAI_API_KEY=sk-...)
# - Database credentials
# - Mail settings
```

#### 4. Database Setup

```bash
# Run migrations (150+ migrations included)
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

#### 5. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

#### 6. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 📋 Requirements

- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **NPM** or Yarn
- **SQLite** or **MySQL**
- **OpenAI API Key**

## 🔧 Configuration

### Environment Variables

Create and configure your `.env` file:

```env
# Application
APP_NAME="Cultural Translate"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://culturaltranslate.com

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Or MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=cultural_translate
# DB_USERNAME=root
# DB_PASSWORD=

# OpenAI Configuration
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4
OPENAI_MAX_TOKENS=2000

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@culturaltranslate.com
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration
QUEUE_CONNECTION=database

# Broadcasting (Optional)
BROADCAST_DRIVER=log
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
```

### Admin Access

Default admin credentials (configured in seeder):
- **Email**: `admin@culturaltranslate.com`
- **Password**: `Admin2024!`

⚠️ **Important**: Change these credentials after first login!

### OpenAI API Setup

1. Get your API key from [OpenAI Platform](https://platform.openai.com/api-keys)
2. Add it to `.env` file: `OPENAI_API_KEY=sk-...`
3. Configure model preferences (gpt-4, gpt-4o-mini, etc.)

---

## 📚 API Documentation

API documentation is available at: `/api/documentation`

### Authentication Endpoints

```bash
POST /api/auth/register      # Register new user
POST /api/auth/login         # Login and get token
POST /api/auth/logout        # Logout
POST /api/auth/refresh       # Refresh token
GET  /api/auth/user          # Get authenticated user
```

### Translation Endpoints

```bash
POST /api/translate          # Translate text
POST /api/translate/batch    # Batch translation
POST /api/translate/voice    # Voice translation
POST /api/translate/image    # Image translation
POST /api/translate/document # Document translation
GET  /api/translations       # Get translation history
DELETE /api/translations/:id # Delete translation
```

### Project Management

```bash
GET    /api/projects         # List all projects
POST   /api/projects         # Create new project
GET    /api/projects/:id     # Get project details
PUT    /api/projects/:id     # Update project
DELETE /api/projects/:id     # Delete project
```

### Dashboard API

```bash
GET  /api/dashboard/stats            # Get statistics
GET  /api/dashboard/recent-activity  # Recent activities
GET  /api/dashboard/projects         # User projects
GET  /api/dashboard/translations     # Translation history
```

**Example Request:**

```bash
curl -X POST https://culturaltranslate.com/api/translate \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Hello, World!",
    "source_language": "en",
    "target_language": "ar",
    "cultural_context": "formal"
  }'
```

---
POST /api/translate
POST /api/translate/batch
POST /api/translate/voice
POST /api/translate/image
```

## 🏗️ Project Structure

```
cultural-translate-platform/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Filament/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── translation.php
└── public/
```

## 🧪 Testing

```bash
# Run all tests
## 🚢 Deployment & Synchronization

### 📦 Server Deployment Methods

**Method 1: One-Command Sync (Recommended)** ⚡
```bash
# See README_SYNC_AR.md for the complete one-line command
```

**Method 2: Using Sync Script**
```bash
ssh root@145.14.158.101
cd /var/www/cultural-translate-platform
bash quick-sync.sh
```

**Method 3: Manual Step-by-Step**
See [SYNC_GUIDE.md](SYNC_GUIDE.md) for detailed instructions.

### 📚 Synchronization Documentation

All sync documentation is indexed in **[SYNC_INDEX.md](SYNC_INDEX.md)**:

| Document | Language | Purpose | Audience |
|----------|----------|---------|----------|
| [README_SYNC_AR.md](README_SYNC_AR.md) | العربية | Copy-paste one command | Beginners |
| [SYNC_SIMPLE_AR.md](SYNC_SIMPLE_AR.md) | العربية | Simple instructions | Non-technical |
| [SYNC_GUIDE.md](SYNC_GUIDE.md) | English | Complete guide | All levels |
| [SYNC_STATUS.md](SYNC_STATUS.md) | English | Technical status | Developers |

### 🔧 Production Checklist

- [x] 150+ Database migrations completed
- [x] Git synchronization configured
- [x] Backup scripts created
- [x] Server deployment scripts ready
- [ ] Execute server sync (awaiting user action)
- [ ] Verify production deployment
- [ ] Test all features

### ⚡ Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Check migrations status
php artisan migrate:status
```

---

## 📊 Features Overview

### 🌐 Translation Services

- **Text Translation**: Cultural context-aware translation (150+ languages)
- **Voice Translation**: Real-time speech-to-speech translation
- **Image Translation**: OCR + translation with layout preservation
- **Document Translation**: PDF, DOCX, PPTX with formatting
- **Video Translation**: Automatic subtitle generation and translation

### 👥 Account Types

- **Customer Account**: Individual translation services
- **Government Account**: Official document translation with certification
- **Translator Account**: Professional translator dashboard
- **Partner Account**: Business collaboration features
- **Affiliate Account**: Referral and commission system

### 🤝 Collaboration Tools

- **Team Management**: Role-based access control
- **Project Management**: Organize translations by project
- **Translation Memory**: Consistency across projects
- **Custom Glossaries**: Brand-specific terminology
- **Review Workflow**: Multi-stage review process

### 📈 Analytics & Insights

- **Usage Statistics**: Track translation volume and costs
- **Quality Metrics**: Translation accuracy and consistency
- **Team Performance**: Individual and team productivity
- **Cost Analysis**: Optimize translation spending
- **Real-time Dashboard**: Live updates and notifications

---

## 🔐 Security & Compliance

- ✅ End-to-end encryption for sensitive data
- ✅ GDPR compliant data handling
- ✅ API rate limiting and authentication
- ✅ CSRF protection
- ✅ Regular security audits
- ✅ Sanctum API authentication

---

## 🛠️ Admin Panel Access

**Filament Admin Panel**: [admin.culturaltranslate.com](https://admin.culturaltranslate.com)

**Default Credentials** (Change after first login):
- Email: `admin@culturaltranslate.com`
- Password: `Admin2024!`

**Admin Features**:
- User management
- System settings
- Translation monitoring
- Database management
- Logs and debugging

---

## 📞 Support & Contact

- **Website**: [culturaltranslate.com](https://culturaltranslate.com)
- **Email**: support@culturaltranslate.com
- **Documentation**: [GitHub Wiki](https://github.com/abokenan444/go-translate/wiki)
- **Issues**: [GitHub Issues](https://github.com/abokenan444/go-translate/issues)

---

## 🌟 Project Status

**Last Updated**: 2025-12-26  
**Status**: ✅ Production Ready  
**Laravel Version**: 11.x  
**PHP Version**: 8.3  
**Database Migrations**: 150+ completed  
**Git Status**: Synchronized with GitHub

**Latest Platform Verification**: [PLATFORM_VERIFICATION_REPORT.md](PLATFORM_VERIFICATION_REPORT.md)

---

## 📝 License

**Proprietary Software** - All rights reserved © 2024-2025 Cultural Translate Platform

This project is proprietary and confidential. Unauthorized copying, distribution, or use is strictly prohibited.

---

## 👥 Team & Contact

**Founders**: Shatha & Yasser  
**Development**: AI-Powered Development Team  
**Email**: abokenan4@gmail.com  
**Support**: support@culturaltranslate.com

---

## 🗺️ Roadmap

### Phase 1 ✅ (Completed)
- [x] Core translation engine
- [x] Multi-account system (5 types)
- [x] Admin panel (Filament 3.x)
- [x] 150+ database migrations
- [x] API documentation
- [x] Dashboard features

### Phase 2 🚧 (In Progress)
- [ ] Execute server synchronization
- [ ] Production monitoring setup
- [ ] Performance optimization
- [ ] SEO implementation
- [ ] Analytics integration

### Phase 3 📋 (Planned)
- [ ] Mobile applications (iOS & Android)
- [ ] Local AI model integration (Llama 3.1)
- [ ] Self-learning translation system
- [ ] Blockchain-based verification
- [ ] Advanced team collaboration
- [ ] Integration marketplace

---

## 📈 Version History

### Version 1.1.0 (2025-12-26) - Current
- ✅ Platform verification completed
- ✅ 150+ database migrations executed
- ✅ Git synchronization configured
- ✅ Comprehensive sync documentation (8 files)
- ✅ Automated deployment scripts
- ✅ Dashboard fixes (all tabs working)
- 🔄 Server deployment ready

### Version 1.0.0 (Initial Release)
- ✅ Core translation functionality
- ✅ Multi-account system
- ✅ Admin panel
- ✅ API implementation

---

## �️ Platform Screenshots

### Dashboard
![Dashboard Preview](https://via.placeholder.com/800x400/4A90E2/FFFFFF?text=Dashboard+Preview)

### Translation Interface
![Translation Interface](https://via.placeholder.com/800x400/50C878/FFFFFF?text=Translation+Interface)

### Admin Panel
![Admin Panel](https://via.placeholder.com/800x400/FF6B6B/FFFFFF?text=Admin+Panel)

> **Note**: Replace placeholder images with actual screenshots after deployment.

---

## 🔗 Important Files Index

| File | Purpose | Language |
|------|---------|----------|
| [SYNC_INDEX.md](SYNC_INDEX.md) | Master sync documentation index | Both |
| [README_SYNC_AR.md](README_SYNC_AR.md) | Quick sync guide | العربية |
| [SYNC_GUIDE.md](SYNC_GUIDE.md) | Complete sync instructions | English |
| [SYNC_STATUS.md](SYNC_STATUS.md) | Technical status report | English |
| [PLATFORM_VERIFICATION_REPORT.md](PLATFORM_VERIFICATION_REPORT.md) | Platform health check | English |

---

## 🤝 Contributing

This is a proprietary project. For feature requests or bug reports, please contact:
- **Email**: abokenan4@gmail.com
- **Support**: support@culturaltranslate.com

---

## 📞 Getting Help

### Documentation Resources
- [Synchronization Guide](SYNC_INDEX.md) - Complete sync documentation
- [Platform Status](PLATFORM_VERIFICATION_REPORT.md) - Current system status
- [API Documentation](https://culturaltranslate.com/api/documentation)

### Support Channels
- **📧 Email**: support@culturaltranslate.com
- **🌐 Website**: [culturaltranslate.com](https://culturaltranslate.com)
- **💬 Admin Panel**: [admin.culturaltranslate.com](https://admin.culturaltranslate.com)

### Common Issues
- **Migration Issues**: See [PLATFORM_VERIFICATION_REPORT.md](PLATFORM_VERIFICATION_REPORT.md)
- **Sync Problems**: Check [SYNC_STATUS.md](SYNC_STATUS.md)
- **Deployment Help**: Follow [README_SYNC_AR.md](README_SYNC_AR.md)

---

<div align="center">

**⭐ Made with ❤️ by Cultural Translate Team**

**🌐 [culturaltranslate.com](https://culturaltranslate.com) | 📧 [support@culturaltranslate.com](mailto:support@culturaltranslate.com)**

---

**© 2024-2025 Cultural Translate Platform. All rights reserved.**

</div>
