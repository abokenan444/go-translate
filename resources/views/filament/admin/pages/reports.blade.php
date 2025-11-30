<x-filament-panels::page>
    <div class="space-y-6">
        <!-- تقرير المستخدمين -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📊 تقرير المستخدمين</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي المستخدمين</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalUsers }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">المستخدمون النشطون</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeUsers }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">المستخدمون الموثقون</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $verifiedUsers }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">مستخدمون جدد اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newUsersToday }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">مستخدمون جدد هذا الأسبوع</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newUsersThisWeek }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">مستخدمون جدد هذا الشهر</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newUsersThisMonth }}</p>
                </div>
            </div>
        </div>

        <!-- تقرير الاشتراكات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">💳 تقرير الاشتراكات</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي الاشتراكات</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalSubscriptions }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الاشتراكات النشطة</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeSubscriptions }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الاشتراكات المنتهية</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $expiredSubscriptions }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الاشتراكات الملغاة</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $cancelledSubscriptions }}</p>
                </div>
            </div>
        </div>

        <!-- تقرير الشكاوى -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📞 تقرير الشكاوى والدعم</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي الشكاوى</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $totalComplaints }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الشكاوى المعلقة</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingComplaints }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الشكاوى المحلولة</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $resolvedComplaints }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">شكاوى اليوم</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $complaintsToday }}</p>
                </div>
            </div>
        </div>

        <!-- تقرير المحتوى -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📄 تقرير المحتوى (الصفحات)</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي الصفحات</p>
                    <p class="text-3xl font-bold text-teal-600 dark:text-teal-400">{{ $totalPages }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">الصفحات المنشورة</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $publishedPages }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">المسودات</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $draftPages }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">في الهيدر</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $pagesInHeader }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">في الفوتر</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $pagesInFooter }}</p>
                </div>
            </div>
        </div>

        <!-- تقرير النشاطات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📋 تقرير سجل النشاطات</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-cyan-50 dark:bg-cyan-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي النشاطات</p>
                    <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400">{{ $totalActivities }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">نشاطات اليوم</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $activitiesToday }}</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">نشاطات هذا الأسبوع</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $activitiesThisWeek }}</p>
                </div>
            </div>
        </div>

        <!-- تقرير الأدوار -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">👥 تقرير المستخدمين حسب الدور</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($usersByRole as $roleData)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @switch($roleData->role)
                                @case('super_admin') مدير عام @break
                                @case('admin') مدير @break
                                @case('user') مستخدم @break
                                @case('translator') مترجم @break
                                @case('moderator') مشرف @break
                                @default {{ $roleData->role }}
                            @endswitch
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $roleData->count }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
