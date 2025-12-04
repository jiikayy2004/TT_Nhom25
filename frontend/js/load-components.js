/**
 * Load Components - Dynamically load header and footer from components folder
 * Usage: Add <div id="header-placeholder"></div> and <div id="footer-placeholder"></div>
 *        Then call: loadComponents()
 */

function loadComponents() {
    // Load Header
    fetch('./components/header.html')
        .then(response => response.text())
        .then(data => {
            const headerPlaceholder = document.getElementById('header-placeholder');
            if (headerPlaceholder) {
                headerPlaceholder.innerHTML = data;
                setActiveNav();
            }
        })
        .catch(error => console.error('Error loading header:', error));

    // Load Footer
    fetch('./components/footer.html')
        .then(response => response.text())
        .then(data => {
            const footerPlaceholder = document.getElementById('footer-placeholder');
            if (footerPlaceholder) {
                footerPlaceholder.innerHTML = data;
            }
        })
        .catch(error => console.error('Error loading footer:', error));
}

/**
 * Set active navigation based on current page
 */
function setActiveNav() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    
    const navLinks = {
        'index.html': '.nav-home',
        'service.html': '.nav-service',
        'pricingdetails.html': '.nav-pricing',
        'scheduletrainer.html': '.nav-schedule',
        'labthuchanh.html': '.nav-lab'
    };

    // Remove active class from all links
    document.querySelectorAll('.nav-list a').forEach(link => {
        link.classList.remove('active');
    });

    // Add active class to current page
    const selector = navLinks[currentPage];
    if (selector) {
        const activeLink = document.querySelector(selector);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }
}

// Load components when DOM is ready
document.addEventListener('DOMContentLoaded', loadComponents);
