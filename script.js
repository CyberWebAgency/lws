function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const menuContent = menu.querySelector('div');
    
    if (menu.classList.contains('hidden')) {
        // Open menu
        menu.classList.remove('hidden');
        setTimeout(() => {
            menuContent.classList.remove('-translate-x-full');
        }, 10);
    } else {
        // Close menu
        menuContent.classList.add('-translate-x-full');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 300);
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    const menu = document.getElementById('mobileMenu');
    if (menu) {
        const menuContent = menu.querySelector('div');
        if (menuContent && !menu.classList.contains('hidden') && !menuContent.contains(e.target) && !e.target.closest('button')) {
            toggleMobileMenu();
        }
    }
});

// Prevent clicks inside menu from closing it
const mobileMenuDiv = document.querySelector('#mobileMenu div');
if (mobileMenuDiv) {
    mobileMenuDiv.addEventListener('click', (e) => {
        e.stopPropagation();
    });
}

// Add this function for counter animation
function animateCounter(element, target, duration = 1500) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    let lastUpdate = 0;
    
    const animate = () => {
        current += increment;
        const currentValue = Math.floor(current);
        
        if (currentValue !== lastUpdate) {
            lastUpdate = currentValue;
            element.textContent = currentValue.toLocaleString() + (element.dataset.suffix || '');
        }
        
        if (current >= target) {
            element.textContent = target.toLocaleString() + (element.dataset.suffix || '');
        } else {
            requestAnimationFrame(animate);
        }
    };
    
    animate();
}

// Add intersection observer for statistics
const observerOptions = {
    threshold: 0.2,
    rootMargin: '0px'
};

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('.counter').forEach(counter => {
                const target = parseInt(counter.dataset.target);
                animateCounter(counter, target);
            });
            statsObserver.unobserve(entry.target);
        }
    });
}, observerOptions);

// Start observing statistics section
document.addEventListener('DOMContentLoaded', () => {
    const statsSection = document.querySelector('.statistics-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Initialize FAQ toggles only if they exist
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
});

// Intersection Observer for timeline animations
const timelineObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.5 });

// Observe timeline items
document.querySelectorAll('.timeline-item').forEach(item => {
    timelineObserver.observe(item);
});

// FAQ Toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // Package Toggle functionality
    const monthlyBtn = document.getElementById('monthlyBtn');
    const yearlyBtn = document.getElementById('yearlyBtn');
    const monthlyPrices = document.querySelector('.monthly-price');
    const yearlyPrices = document.querySelector('.yearly-price');

    if (monthlyBtn && yearlyBtn) {
        monthlyBtn.addEventListener('click', () => {
            monthlyBtn.classList.add('bg-blue-600', 'text-white');
            monthlyBtn.classList.remove('bg-gray-200');
            yearlyBtn.classList.remove('bg-blue-600', 'text-white');
            yearlyBtn.classList.add('bg-gray-200');
            monthlyPrices.classList.remove('hidden');
            yearlyPrices.classList.add('hidden');
        });

        yearlyBtn.addEventListener('click', () => {
            yearlyBtn.classList.add('bg-blue-600', 'text-white');
            yearlyBtn.classList.remove('bg-gray-200');
            monthlyBtn.classList.remove('bg-blue-600', 'text-white');
            monthlyBtn.classList.add('bg-gray-200');
            yearlyPrices.classList.remove('hidden');
            monthlyPrices.classList.add('hidden');
        });
    }
});

// Service Category Filter
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.service-category');
    const serviceCards = document.querySelectorAll('.service-card');

    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('bg-blue-600', 'text-white'));
            
            // Add active class to clicked button
            button.classList.add('bg-blue-600', 'text-white');
            
            const category = button.dataset.category;
            
            // Show/hide service cards based on category
            serviceCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                    // Add fade-in animation
                    card.classList.add('animate-fade-in');
                } else {
                    card.classList.add('hidden');
                    card.classList.remove('animate-fade-in');
                }
            });
        });
    });
});

// Function to load templates
async function loadTemplate(elementId, templatePath) {
    try {
        const response = await fetch(templatePath);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const html = await response.text();
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = html;
        }
    } catch (error) {
        console.error('Error loading template:', error);
    }
}