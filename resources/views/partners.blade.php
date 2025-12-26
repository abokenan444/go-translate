<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Become a Partner - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(config('services.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>
<body class="bg-gray-50">
    @include('components.navigation')

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Become a Partner</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Join our global network of translation partners and grow your business with Cultural Translate</p>
            <a href="#apply" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all inline-block">
                Apply Now <i class="fas fa-arrow-down ml-2"></i>
            </a>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12">Partner Benefits</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-blue-600 mb-4"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Grow Your Revenue</h3>
                    <p class="text-gray-600">Earn competitive commissions on every translation project you refer or complete</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-purple-600 mb-4"><i class="fas fa-globe"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Global Reach</h3>
                    <p class="text-gray-600">Access clients from 100+ countries and expand your business internationally</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-green-600 mb-4"><i class="fas fa-tools"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Advanced Tools</h3>
                    <p class="text-gray-600">Use our AI-powered platform with 100+ languages and cultural intelligence</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-orange-600 mb-4"><i class="fas fa-headset"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Dedicated Support</h3>
                    <p class="text-gray-600">Get 24/7 support from our partner success team</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-red-600 mb-4"><i class="fas fa-certificate"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Training & Certification</h3>
                    <p class="text-gray-600">Free training programs and official certification for your team</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                    <div class="text-5xl text-indigo-600 mb-4"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-2xl font-bold mb-4">Co-Marketing</h3>
                    <p class="text-gray-600">Joint marketing campaigns and brand visibility opportunities</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form Section -->
    <section id="apply" class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-4xl font-bold text-center mb-4">Apply Now</h2>
            <p class="text-center text-gray-600 mb-12">Fill out the form below and our team will contact you within 24 hours</p>

            <form id="partnerForm" class="space-y-6">
                <!-- Honeypot field (hidden from users, bots will fill it) -->
                <input type="text" name="website_url" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                
                <!-- Form start time (for timing check) -->
                <input type="hidden" name="form_start_time" id="formStartTime" value="">
                <!-- Company Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-2xl font-bold mb-4">Company Information</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" name="company_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Website</label>
                            <input type="url" name="website" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <select name="country" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Country</option>
                                <option value="SA">Saudi Arabia</option>
                                <option value="AE">United Arab Emirates</option>
                                <option value="US">United States</option>
                                <option value="GB">United Kingdom</option>
                                <option value="DE">Germany</option>
                                <option value="FR">France</option>
                                <option value="ES">Spain</option>
                                <option value="NL">Netherlands</option>
                                <!-- Add more countries -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Size *</label>
                            <select name="company_size" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Size</option>
                                <option value="1-10">1-10 employees</option>
                                <option value="11-50">11-50 employees</option>
                                <option value="51-200">51-200 employees</option>
                                <option value="201-500">201-500 employees</option>
                                <option value="500+">500+ employees</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-2xl font-bold mb-4">Contact Person</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="contact_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                            <input type="text" name="job_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                            <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Partnership Details -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-2xl font-bold mb-4">Partnership Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partnership Type *</label>
                            <select name="partnership_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="reseller">Reseller Partner</option>
                                <option value="affiliate">Affiliate Partner</option>
                                <option value="technology">Technology Partner</option>
                                <option value="agency">Translation Agency</option>
                                <option value="enterprise">Enterprise Client</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expected Monthly Volume</label>
                            <select name="monthly_volume" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Volume</option>
                                <option value="0-1000">$0 - $1,000</option>
                                <option value="1000-5000">$1,000 - $5,000</option>
                                <option value="5000-10000">$5,000 - $10,000</option>
                                <option value="10000-50000">$10,000 - $50,000</option>
                                <option value="50000+">$50,000+</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tell us about your business and why you want to partner with us *</label>
                            <textarea name="message" required rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-12 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Submit Application <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>

            <div id="successMessage" class="hidden mt-8 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg text-center">
                <i class="fas fa-check-circle text-2xl mb-2"></i>
                <p class="font-semibold">Thank you for your application!</p>
                <p>Our team will review your application and contact you within 24 hours.</p>
            </div>
        </div>
    </section>

    @include('components.footer')

    <script>
    // Set form start time when page loads
    document.getElementById('formStartTime').value = Math.floor(Date.now() / 1000);
    
    document.getElementById('partnerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Get reCAPTCHA token if enabled
            @if(config('services.recaptcha.site_key'))
            const recaptchaToken = await grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'partner_application'});
            data.recaptcha_token = recaptchaToken;
            @endif
            
            const response = await fetch('/api/partner-applications', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                document.getElementById('partnerForm').classList.add('hidden');
                document.getElementById('successMessage').classList.remove('hidden');
                window.scrollTo({ top: document.getElementById('successMessage').offsetTop - 100, behavior: 'smooth' });
            } else {
                alert(result.message || 'An error occurred. Please try again.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again or contact us directly.');
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });
    </script>
</body>
</html>
