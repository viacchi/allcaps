<?php
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support - Logistics Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-green': '#2D7A5C',
                        'light-green': '#E8F5F0',
                        'dark-green': '#1F5240',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-headset text-primary-green"></i>
                    Contact Support
                </h2>
                <p class="text-gray-600 mt-2">We're here to help! Get in touch with our support team</p>
            </div>

            <!-- Quick Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Email Support -->
                <div class="bg-white rounded-lg p-6 shadow-sm border-t-4 border-blue-500 hover:shadow-lg transition-all cursor-pointer" onclick="openEmailModal()">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Email Support</h3>
                    <p class="text-sm text-gray-600 mb-3">Send us an email and we'll respond within 24 hours</p>
                    <a href="mailto:support@logistics.com" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        support@logistics.com
                    </a>
                </div>

                <!-- Phone Support -->
                <div class="bg-white rounded-lg p-6 shadow-sm border-t-4 border-green-500 hover:shadow-lg transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-green-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Phone Support</h3>
                    <p class="text-sm text-gray-600 mb-3">Call us for immediate assistance</p>
                    <a href="tel:+639171234567" class="text-sm font-semibold text-green-600 hover:text-green-700">
                        +63 917 123 4567
                    </a>
                    <p class="text-xs text-gray-500 mt-2">Mon-Fri: 8:00 AM - 6:00 PM</p>
                </div>

                <!-- Live Chat -->
                <div class="bg-white rounded-lg p-6 shadow-sm border-t-4 border-purple-500 hover:shadow-lg transition-all cursor-pointer" onclick="openLiveChat()">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-comments text-purple-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                            <i class="fas fa-circle text-green-600 text-xs"></i> Online
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Live Chat</h3>
                    <p class="text-sm text-gray-600 mb-3">Chat with our support team in real-time</p>
                    <span class="text-sm font-semibold text-purple-600">
                        Start Chat →
                    </span>
                </div>

                <!-- Knowledge Base -->
                <div class="bg-white rounded-lg p-6 shadow-sm border-t-4 border-yellow-500 hover:shadow-lg transition-all cursor-pointer" onclick="openKnowledgeBase()">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-yellow-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Knowledge Base</h3>
                    <p class="text-sm text-gray-600 mb-3">Browse articles and find answers instantly</p>
                    <span class="text-sm font-semibold text-yellow-600">
                        Browse Articles →
                    </span>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Form -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-primary-green"></i>
                        Send Us a Message
                    </h3>
                    <form id="contactForm" onsubmit="submitContact(event)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="contactName" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="John Doe" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="contactEmail" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" id="contactPhone" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="+63 917 123 4567">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                                <select id="contactSubject" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent bg-white" required>
                                    <option value="">Select a subject</option>
                                    <option value="technical">Technical Issue</option>
                                    <option value="billing">Billing Question</option>
                                    <option value="feature">Feature Request</option>
                                    <option value="training">Training & Support</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
                            <textarea id="contactMessage" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Please describe your issue or question..." rows="6" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Attach Files (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:border-primary-green transition-colors cursor-pointer" onclick="document.getElementById('attachments').click()">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-500">Screenshots, documents (Max 10MB)</p>
                                <input type="file" id="attachments" multiple accept="image/*,.pdf,.doc,.docx" class="hidden">
                                <p id="fileList" class="mt-2 text-sm text-gray-600"></p>
                            </div>
                        </div>

                        <div class="flex items-start mb-4">
                            <input type="checkbox" id="contactAgree" class="w-4 h-4 text-primary-green border-gray-300 rounded focus:ring-primary-green mt-1" required>
                            <label for="contactAgree" class="ml-2 text-sm text-gray-700">
                                I agree to the <a href="#" class="text-primary-green hover:text-dark-green font-semibold">Privacy Policy</a> and <a href="#" class="text-primary-green hover:text-dark-green font-semibold">Terms of Service</a>
                            </label>
                        </div>

                        <button type="submit" class="w-full px-6 py-3 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>

                <!-- Support Info Sidebar -->
                <div class="space-y-6">
                    <!-- Support Hours -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-primary-green"></i>
                            Support Hours
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monday - Friday:</span>
                                <span class="font-semibold text-gray-900">8:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Saturday:</span>
                                <span class="font-semibold text-gray-900">9:00 AM - 3:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sunday:</span>
                                <span class="font-semibold text-red-600">Closed</span>
                            </div>
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-800">
                                    <i class="fas fa-info-circle"></i> Emergency support available 24/7 via phone
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-link text-primary-green"></i>
                            Quick Links
                        </h4>
                        <div class="space-y-2">
                            <a href="#" class="block text-sm text-gray-700 hover:text-primary-green hover:bg-gray-50 p-2 rounded transition-colors">
                                <i class="fas fa-question-circle mr-2"></i> Frequently Asked Questions
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-primary-green hover:bg-gray-50 p-2 rounded transition-colors">
                                <i class="fas fa-book-open mr-2"></i> User Manual
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-primary-green hover:bg-gray-50 p-2 rounded transition-colors">
                                <i class="fas fa-video mr-2"></i> Video Tutorials
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-primary-green hover:bg-gray-50 p-2 rounded transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i> Submit a Ticket
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-primary-green hover:bg-gray-50 p-2 rounded transition-colors">
                                <i class="fas fa-comments mr-2"></i> Community Forum
                            </a>
                        </div>
                    </div>

                    <!-- Response Time -->
                    <div class="bg-gradient-to-br from-primary-green to-dark-green text-white rounded-lg shadow-sm p-6">
                        <h4 class="text-lg font-bold mb-2 flex items-center gap-2">
                            <i class="fas fa-stopwatch"></i>
                            Average Response Time
                        </h4>
                        <div class="text-3xl font-bold mb-2">2 Hours</div>
                        <p class="text-sm text-white text-opacity-80">We typically respond to emails within 2 hours during business hours</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-question-circle text-primary-green"></i>
                    Frequently Asked Questions
                </h3>
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-colors" onclick="toggleFAQ(1)">
                            <span class="font-semibold text-gray-900">How do I reset my password?</span>
                            <i class="fas fa-chevron-down text-gray-600 transition-transform" id="faqIcon1"></i>
                        </button>
                        <div class="hidden px-4 pb-3 text-sm text-gray-700" id="faqContent1">
                            <p>You can reset your password by clicking on "Forgot Password" on the login page. Follow the instructions sent to your email to create a new password.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-colors" onclick="toggleFAQ(2)">
                            <span class="font-semibold text-gray-900">How do I add a new vehicle to the fleet?</span>
                            <i class="fas fa-chevron-down text-gray-600 transition-transform" id="faqIcon2"></i>
                        </button>
                        <div class="hidden px-4 pb-3 text-sm text-gray-700" id="faqContent2">
                            <p>Navigate to Fleet & Vehicle Management → Vehicle Registry and click the "Add Vehicle" button. Fill in the required information and submit the form.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-colors" onclick="toggleFAQ(3)">
                            <span class="font-semibold text-gray-900">Can I export reports to Excel or PDF?</span>
                            <i class="fas fa-chevron-down text-gray-600 transition-transform" id="faqIcon3"></i>
                        </button>
                        <div class="hidden px-4 pb-3 text-sm text-gray-700" id="faqContent3">
                            <p>Yes! Most pages have "Export PDF" and "Export Excel" buttons. Simply click the button to download your report in the desired format.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-colors" onclick="toggleFAQ(4)">
                            <span class="font-semibold text-gray-900">How do I assign a driver to a vehicle?</span>
                            <i class="fas fa-chevron-down text-gray-600 transition-transform" id="faqIcon4"></i>
                        </button>
                        <div class="hidden px-4 pb-3 text-sm text-gray-700" id="faqContent4">
                            <p>Go to Reservation & Dispatch → Dispatch & Assignment Dashboard. Select an available vehicle and click "Assign" to choose a driver from the list.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-colors" onclick="toggleFAQ(5)">
                            <span class="font-semibold text-gray-900">What browsers are supported?</span>
                            <i class="fas fa-chevron-down text-gray-600 transition-transform" id="faqIcon5"></i>
                        </button>
                        <div class="hidden px-4 pb-3 text-sm text-gray-700" id="faqContent5">
                            <p>We support the latest versions of Chrome, Firefox, Safari, and Edge. For the best experience, we recommend using Chrome or Firefox.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-all inline-flex items-center gap-2">
                        <i class="fas fa-book"></i> View All FAQs
                    </button>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gradient-to-r from-primary-green to-dark-green text-white rounded-lg shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-lg font-bold mb-3 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i>
                            Visit Us
                        </h4>
                        <p class="text-sm text-white text-opacity-90">
                            123 Logistics Street<br>
                            Quezon City, Metro Manila<br>
                            Philippines 1100
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold mb-3 flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            Email Us
                        </h4>
                        <p class="text-sm text-white text-opacity-90">
                            General: support@logistics.com<br>
                            Sales: sales@logistics.com<br>
                            Technical: tech@logistics.com
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold mb-3 flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            Call Us
                        </h4>
                        <p class="text-sm text-white text-opacity-90">
                            Main: +63 917 123 4567<br>
                            Support: +63 917 234 5678<br>
                            Emergency: +63 917 345 6789
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Success Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="successModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Message Sent!</h3>
            <p class="text-gray-600 mb-6">Thank you for contacting us. We'll get back to you within 2 hours.</p>
            <button class="px-6 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all" onclick="closeSuccessModal()">
                Close
            </button>
        </div>
    </div>

    <script>
        // Display selected files
        document.getElementById('attachments').addEventListener('change', function(e) {
            const fileNames = Array.from(e.target.files).map(file => file.name).join(', ');
            if (fileNames) {
                document.getElementById('fileList').textContent = `Selected: ${fileNames}`;
            }
        });

        function submitContact(event) {
            event.preventDefault();
            
            // Simulate form submission
            setTimeout(() => {
                document.getElementById('contactForm').reset();
                document.getElementById('fileList').textContent = '';
                document.getElementById('successModal').classList.remove('hidden');
                document.getElementById('successModal').classList.add('flex');
            }, 500);
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
        }

        function toggleFAQ(index) {
            const content = document.getElementById(`faqContent${index}`);
            const icon = document.getElementById(`faqIcon${index}`);
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function openEmailModal() {
            // Focus on the contact form
            document.getElementById('contactEmail').focus();
            window.scrollTo({ top: document.getElementById('contactForm').offsetTop - 100, behavior: 'smooth' });
        }

        function openLiveChat() {
            alert('Live chat feature coming soon! For now, please use the contact form or call us directly.');
        }

        function openKnowledgeBase() {
            alert('Knowledge Base opening soon! For now, please check our FAQs below or contact support.');
        }

        // Close success modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) closeSuccessModal();
        });
    </script>
</body>
</html>