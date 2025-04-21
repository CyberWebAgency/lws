<html>
<head>
    <title>Lawgical Station</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<!-- Header -->
<header class="bg-gray-900 shadow-md fixed w-full top-0 z-50" role="banner">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="index.html" class="flex items-center" aria-label="Lawgical Station Home">
                    <h1 class="text-white text-2xl font-bold">Lawgical Station</h1>
                    <!-- <img src="https://i.ibb.co/VqnPLmw/lawgical-logo-white.png" alt="Lawgical Station Logo" class="h-12" width="48" height="48" loading="lazy"> -->
                </a>
            </div>

            <!-- Main Navigation -->
            <nav class="hidden md:flex items-center space-x-6" role="navigation" aria-label="Main navigation">
                <a href="index.html" class="text-white hover:text-blue-600" aria-current="page">Home</a>
                <!-- Services Dropdown -->
                <div class="relative group">
                    <button class="text-white hover:text-blue-600 flex items-center" aria-haspopup="true" aria-expanded="false">
                        Services <i class="fas fa-chevron-down ml-1 text-sm" aria-hidden="true"></i>
                    </button>
                    <div class="absolute left-0 top-full bg-white text-gray-800 p-6 rounded-lg shadow-xl hidden group-hover:grid grid-cols-3 gap-6 w-[800px] z-50" role="menu" aria-orientation="vertical">
                        <!-- Column 1 -->
                        <div class="space-y-4">
                            <a href="services.html#itr-filing" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">ITR Filing</div>
                                <div class="text-sm text-gray-600">Assisted tax filing by qualified tax experts</div>
                            </a>
                            <a href="services.html#schedule-call" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">Schedule a Call</div>
                                <div class="text-sm text-gray-600">Seamless access to consultations with tax experts</div>
                            </a>
                            <a href="services.html#tds-filing" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">TDS Return Filing</div>
                                <div class="text-sm text-gray-600">Seamless TDS Filing Solutions</div>
                            </a>
                        </div>
                        <!-- Column 2 -->
                        <div class="space-y-4">
                            <a href="services.html#tax-planning" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">Tax Planning</div>
                                <div class="text-sm text-gray-600">Expert tax planning services</div>
                            </a>
                            <a href="services.html#gst-filing" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">GST Filing</div>
                                <div class="text-sm text-gray-600">GST registration and timely filing</div>
                            </a>
                            <a href="services.html#business-license" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">Business License Registration</div>
                                <div class="text-sm text-gray-600">Fast, simple business license registration</div>
                            </a>
                        </div>
                        <!-- Column 3 -->
                        <div class="space-y-4">
                            <a href="services.html#income-tax-notice" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">Income Tax Notice</div>
                                <div class="text-sm text-gray-600">Resolve notices of all types</div>
                            </a>
                            <a href="services.html#huf-registration" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">HUF Registration & Filing</div>
                                <div class="text-sm text-gray-600">Streamline HUF Filing, Maximize Returns</div>
                            </a>
                            <a href="services.html#company-registration" class="block hover:text-blue-600" role="menuitem">
                                <div class="font-semibold mb-1">Company Registration Online</div>
                                <div class="text-sm text-gray-600">Easy online company registration</div>
                            </a>
                        </div>
                    </div>
                </div>
                <a href="about.html" class="text-white hover:text-blue-600">About Us</a>
                <a href="tools.html" class="text-white hover:text-blue-600">Tools</a>
                <a href="contact.html" class="text-white hover:text-blue-600">Contact</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4" role="navigation" aria-label="User authentication">
                <!-- <button class="px-4 py-2 text-blue-600 hover:text-blue-700" aria-label="Login to your account">Login</button> -->
                <a href="register.html" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" aria-label="Register new account">Register</a>
            </div>

            <!-- Mobile Menu Button -->
            <button onclick="toggleMobileMenu()" class="md:hidden" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="mobileMenu">
                <i class="fas fa-bars text-2xl text-white" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden" role="dialog" aria-modal="true" aria-label="Mobile navigation menu">
    <div class="bg-white h-full w-4/5 max-w-sm py-6 px-4 transform transition-transform duration-300 -translate-x-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-gray-900 text-2xl font-bold">Lawgical Station</h2>
            <button onclick="toggleMobileMenu()" class="text-gray-600" aria-label="Close mobile menu">
                <i class="fas fa-times text-2xl" aria-hidden="true"></i>
            </button>
        </div>
        <nav class="space-y-4" role="navigation" aria-label="Mobile navigation">
            <a href="index.html" class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">Home</a>
            <a href="services.html" class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">Services</a>
            <a href="about.html" class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">About Us</a>
            <a href="tools.html" class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">Tools</a>
            <a href="contact.html" class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">Contact</a>
        </nav>
        <div class="mt-8 space-y-4" role="navigation" aria-label="Mobile authentication">
            <button class="w-full px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50" aria-label="Login to your account">
                Login
            </button>
            <a href="register.html" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center" aria-label="Register new account">
                Register
            </a>
        </div>
    </div>
</div>