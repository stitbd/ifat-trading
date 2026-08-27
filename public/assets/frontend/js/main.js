// Main JavaScript file

document.addEventListener('DOMContentLoaded', function () {
    // Smooth scrolling for navbar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 70, // Offset for fixed navbar
                    behavior: 'smooth'
                });
            }
        });
    });

    const counters = document.querySelectorAll('.counter-number');
    const counterSpeed = 60; // smaller = faster

    let hasAnimated = false;

    function animateCounters() {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let count = 0;

            const updateCount = () => {
                const increment = Math.ceil(target / 100); 
                count += increment;

                if (count < target) {
                    counter.innerText = count + '+';
                    setTimeout(updateCount, counterSpeed);
                } else {
                    counter.innerText = target + '+';
                }
            };

            updateCount();
        });
    }

    // Function to check if element is in viewport
    function isInViewport(el) {
        const rect = el.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    }

    // Scroll event
    window.addEventListener('scroll', () => {
        const section = document.querySelector('.counter-section');
        if (!hasAnimated && isInViewport(section)) {
            animateCounters();
            hasAnimated = true;
        }
    });


    // Create placeholder images for service icons
    const serviceIcons = document.querySelectorAll('.service-icon img');

    serviceIcons.forEach((icon, index) => {
        // Set placeholder icons based on service type
        switch (index) {
            case 0: // Technical Services
                icon.src = 'https://cdn-icons-png.flaticon.com/512/4341/4341025.png';
                break;
            case 1: // Online PSV Testing
                icon.src = 'https://cdn-icons-png.flaticon.com/512/2452/2452227.png';
                break;
            case 2: // Industrial Valves
                icon.src = 'https://cdn-icons-png.flaticon.com/512/5266/5266248.png';
                break;
            case 3: // Service & Repairs
                icon.src = 'https://cdn-icons-png.flaticon.com/512/1077/1077198.png';
                break;
        }
    });

    // Create M-Tech logo placeholder
    // const logoImg = document.querySelector('.about-logo img');
    // if (logoImg) {
    //     logoImg.src = 'https://via.placeholder.com/300/5e35b1/ffffff?text=M-TECH';
    // }

    // Initialize navbar behavior
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
});