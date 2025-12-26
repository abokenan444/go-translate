<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار رفع الملفات - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">اختبار رفع الملفات</h1>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h2 class="font-semibold text-blue-800 mb-2">الإعدادات الحالية:</h2>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>📤 الحد الأقصى لحجم الملف: <strong>30 ميجابايت</strong></li>
                <li>📦 الحد الأقصى للبيانات المرسلة: <strong>35 ميجابايت</strong></li>
                <li>⚙️ PHP Version: <strong>8.3.6</strong></li>
                <li>🌐 Nginx: <strong>مفعّل</strong></li>
            </ul>
        </div>

        <form action="/test-upload" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    اختر ملف للرفع (حتى 30 ميجابايت)
                </label>
                <input type="file" 
                       name="file" 
                       id="file" 
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100
                              cursor-pointer border border-gray-300 rounded-lg p-2"
                       accept="image/*,.pdf,.doc,.docx,.txt,.mp3,.mp4">
            </div>

            <div id="fileInfo" class="hidden bg-gray-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">معلومات الملف:</p>
                <div id="fileDetails" class="text-sm text-gray-600"></div>
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                ✅ رفع الملف
            </button>
        </form>

        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">أنواع الملفات المدعومة:</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="bg-white p-2 rounded text-center">📷 صور</div>
                <div class="bg-white p-2 rounded text-center">📄 PDF</div>
                <div class="bg-white p-2 rounded text-center">📝 Word</div>
                <div class="bg-white p-2 rounded text-center">🎵 صوت</div>
            </div>
        </div>

        @if(session('success'))
        <div class="mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            ❌ {{ $errors->first() }}
        </div>
        @endif
    </div>

    <script>
        const fileInput = document.getElementById('file');
        const fileInfo = document.getElementById('fileInfo');
        const fileDetails = document.getElementById('fileDetails');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                const maxSize = 30;
                
                fileDetails.innerHTML = `
                    <p><strong>الاسم:</strong> ${file.name}</p>
                    <p><strong>الحجم:</strong> ${sizeMB} ميجابايت</p>
                    <p><strong>النوع:</strong> ${file.type || 'غير محدد'}</p>
                    <p class="${sizeMB > maxSize ? 'text-red-600 font-bold' : 'text-green-600 font-bold'}">
                        ${sizeMB > maxSize ? '⚠️ الملف أكبر من الحد المسموح!' : '✅ الحجم مناسب'}
                    </p>
                `;
                fileInfo.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
