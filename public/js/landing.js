// ===== EXCELLENCE ACADEMY LANDING PAGE =====
// Modern animations and interactions with vanilla JavaScript

// Utility Functions
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);

// ===== MOBILE MENU TOGGLE =====
const initMobileMenu = () => {
    const mobileMenuBtn = $('#mobileMenuBtn');
    const mobileMenu = $('#mobileMenu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking on links
        $$('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    }
};

// ===== NAVBAR SCROLL EFFECT =====
const initNavbarScroll = () => {
    const navbar = $('#navbar');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            navbar.classList.remove('shadow-lg');
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
            navbar.classList.add('shadow-lg');
        }

        lastScroll = currentScroll;
    });
};

// ===== SMOOTH SCROLLING =====
const initSmoothScroll = () => {
    $$('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');

            if (targetId === '#') return;

            const targetElement = $(targetId);
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
};

// ===== SCROLL ANIMATIONS =====
const initScrollAnimations = () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                // Unobserve after animation to improve performance
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all animated elements
    $$('.animated').forEach(element => {
        observer.observe(element);
    });
};

// ===== COUNTER ANIMATION =====
const initCounterAnimation = () => {
    const counters = $$('[data-count]');
    const speed = 200; // Animation speed

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-count'));
        const increment = target / speed;
        let count = 0;

        const updateCount = () => {
            count += increment;
            if (count < target) {
                counter.textContent = Math.ceil(count);
                requestAnimationFrame(updateCount);
            } else {
                counter.textContent = target;
            }
        };

        updateCount();
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
};

// ===== PARALLAX EFFECT =====
const initParallax = () => {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = $$('.parallax');

        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
};

// ===== CONTACT FORM HANDLER =====
const initContactForm = () => {
    const contactForm = $('#contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Show success message
            const formData = new FormData(contactForm);

            // Create success notification
            showNotification('Thank you for your message! We will get back to you soon.', 'success');

            // Reset form
            contactForm.reset();
        });
    }
};

// ===== NOTIFICATION SYSTEM =====
const showNotification = (message, type = 'success') => {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.style.animation = 'fadeInDown 0.3s ease-out';
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
};

// ===== ACTIVE NAVIGATION LINK =====
const initActiveNav = () => {
    const sections = $$('section[id]');
    const navLinks = $$('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

            if (window.pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('text-blue-600');
            link.classList.add('text-gray-700');

            if (link.getAttribute('href') === `#${current}`) {
                link.classList.remove('text-gray-700');
                link.classList.add('text-blue-600');
            }
        });
    });
};

// ===== CURSOR EFFECT =====
const initCursorEffect = () => {
    const cards = $$('.card-hover');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * 5;
            const rotateY = ((centerX - x) / centerX) * 5;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });
};

// ===== LOADING ANIMATION =====
const initLoadingAnimation = () => {
    window.addEventListener('load', () => {
        // Trigger initial animations
        setTimeout(() => {
            $$('.animated').forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('show');
                }, index * 50);
            });
        }, 100);
    });
};

// ===== BLOB ANIMATION ENHANCEMENT =====
const initBlobAnimation = () => {
    const blobs = $$('.blob');

    blobs.forEach((blob, index) => {
        // Add random movement
        setInterval(() => {
            const randomX = Math.random() * 20 - 10;
            const randomY = Math.random() * 20 - 10;
            blob.style.transform = `translate(${randomX}px, ${randomY}px)`;
        }, 3000 + (index * 1000));
    });
};

// ===== SCROLL TO TOP BUTTON =====
const initScrollToTop = () => {
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    `;
    scrollBtn.className = 'fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300 z-40 opacity-0 pointer-events-none flex items-center justify-center';
    scrollBtn.id = 'scrollToTop';

    document.body.appendChild(scrollBtn);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 500) {
            scrollBtn.style.opacity = '1';
            scrollBtn.style.pointerEvents = 'auto';
        } else {
            scrollBtn.style.opacity = '0';
            scrollBtn.style.pointerEvents = 'none';
        }
    });

    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
};

// ===== INITIALIZE ALL FEATURES =====
const init = () => {
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }
};

const runInit = () => {
    console.log('🎓 Excellence Academy - Landing Page Initialized');

    // Initialize all features
    initMobileMenu();
    initNavbarScroll();
    initSmoothScroll();
    initScrollAnimations();
    initCounterAnimation();
    initParallax();
    initContactForm();
    initActiveNav();
    initCursorEffect();
    initLoadingAnimation();
    initBlobAnimation();
    initScrollToTop();

    // Performance optimization: Remove scroll listener after animations are done
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Performance optimizations can be added here
        }, 150);
    }, { passive: true });
};

// Start initialization
init();

// Make some functions globally available if needed
window.LandingPage = {
    showNotification,
    init
};
