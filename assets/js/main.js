/**
 * Lahore Catering - Vanilla JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Menu Toggle
    const toggleBtn = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', function () {
            navMenu.classList.toggle('active');
            const expanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            toggleBtn.setAttribute('aria-expanded', !expanded);
        });

        // Close menu on link click
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });
    }

    // 2. Cookie Consent Banner
    const cookieBanner = document.getElementById('cookieBanner');
    const cookieAcceptBtn = document.getElementById('cookieAcceptBtn');

    if (cookieBanner && cookieAcceptBtn) {
        if (!localStorage.getItem('lc_cookies_accepted')) {
            cookieBanner.style.display = 'flex';
        } else {
            cookieBanner.style.display = 'none';
        }

        cookieAcceptBtn.addEventListener('click', function () {
            localStorage.setItem('lc_cookies_accepted', 'true');
            cookieBanner.style.display = 'none';
        });
    }

    // 3. Reservation & Contact AJAX Form Submissions
    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = reservationForm.querySelector('button[type="submit"]');
            const feedback = document.getElementById('formFeedback');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = 'Even geduld... Bezig met verzenden';

            const formData = new FormData(reservationForm);

            fetch('/api/reserve.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;

                if (data.success) {
                    feedback.innerHTML = '<div style="background:#dcfce7; color:#15803d; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">' + data.message + '</div>';
                    reservationForm.reset();
                } else {
                    feedback.innerHTML = '<div style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">' + (data.message || 'Er is een fout opgetreden. Probeer het opnieuw.') + '</div>';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                feedback.innerHTML = '<div style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">Er is een netwerkfout opgetreden. Bel ons gerust direct via 06 331 667 30.</div>';
            });
        });
    }

    // 4. Contact Form AJAX Submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = contactForm.querySelector('button[type="submit"]');
            const feedback = document.getElementById('contactFeedback');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = 'Versturen...';

            const formData = new FormData(contactForm);

            fetch('/api/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;

                if (data.success) {
                    feedback.innerHTML = '<div style="background:#dcfce7; color:#15803d; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">' + data.message + '</div>';
                    contactForm.reset();
                } else {
                    feedback.innerHTML = '<div style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">' + (data.message || 'Er is een fout opgetreden.') + '</div>';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                feedback.innerHTML = '<div style="background:#fee2e2; color:#b91c1c; padding:1rem 1.5rem; border-radius:8px; margin-top:1.5rem; font-weight:600; text-align:center;">Er is een netwerkfout opgetreden.</div>';
            });
        });
    }
});
