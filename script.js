/* =============================================================
   Lino Professional Cleaning Services — Interactivity
   ============================================================= */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    // ---------- Lucide icons ----------
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }

    // ---------- Year in footer ----------
    var yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // ---------- Mobile nav toggle ----------
    var toggle = document.querySelector('.nav-toggle');
    var navList = document.getElementById('nav-list');
    if (toggle && navList) {
      toggle.addEventListener('click', function () {
        var open = navList.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      });
      // Close on link click
      navList.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
          navList.classList.remove('open');
          toggle.setAttribute('aria-expanded', 'false');
        });
      });
    }

    // ---------- Smooth scroll with sticky header offset ----------
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
      link.addEventListener('click', function (e) {
        var href = link.getAttribute('href');
        if (href === '#' || href.length < 2) return;
        var target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        var header = document.querySelector('.site-header');
        var offset = header ? header.offsetHeight + 10 : 0;
        var y = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: y, behavior: 'smooth' });
      });
    });

    // ---------- Scroll reveal ----------
    var revealTargets = document.querySelectorAll(
      '.service-card, .benefit, .step, .review-card, .stat, .cities li, .faq-item, .about-img, .guarantee-img, .area-img'
    );
    revealTargets.forEach(function (el) { el.classList.add('reveal'); });

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });
      revealTargets.forEach(function (el) { io.observe(el); });
    } else {
      revealTargets.forEach(function (el) { el.classList.add('visible'); });
    }

    // ---------- Form validation + AJAX submit ----------
    var form = document.getElementById('quote-form');
    var msgBox = document.getElementById('form-message');

    function setError(field, text) {
      var wrapper = field.closest('.form-field');
      if (!wrapper) return;
      wrapper.classList.add('error');
      var existing = wrapper.querySelector('.field-error');
      if (existing) existing.remove();
      var err = document.createElement('span');
      err.className = 'field-error';
      err.textContent = text;
      wrapper.appendChild(err);
    }
    function clearError(field) {
      var wrapper = field.closest('.form-field');
      if (!wrapper) return;
      wrapper.classList.remove('error');
      var existing = wrapper.querySelector('.field-error');
      if (existing) existing.remove();
    }
    function showMessage(kind, text) {
      if (!msgBox) return;
      msgBox.className = 'form-message visible ' + kind;
      msgBox.textContent = text;
    }

    if (form) {
      // Clear errors on input
      form.querySelectorAll('input, select, textarea').forEach(function (field) {
        field.addEventListener('input', function () { clearError(field); });
        field.addEventListener('change', function () { clearError(field); });
      });

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Honeypot
        var hp = form.querySelector('input[name="website"]');
        if (hp && hp.value) { return; } // silently drop

        var name = form.querySelector('#name');
        var phone = form.querySelector('#phone');
        var email = form.querySelector('#email');
        var ok = true;

        if (!name.value.trim()) { setError(name, 'Please enter your name.'); ok = false; }
        if (!phone.value.trim()) { setError(phone, 'Please enter your phone number.'); ok = false; }
        else if (!/[0-9]{7,}/.test(phone.value.replace(/\D/g, ''))) {
          setError(phone, 'Please enter a valid phone number.'); ok = false;
        }
        if (email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
          setError(email, 'Please enter a valid email.'); ok = false;
        }

        if (!ok) {
          showMessage('error', 'Please fix the highlighted fields.');
          return;
        }

        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending…'; }

        var data = new FormData(form);

        fetch('send.php', {
          method: 'POST',
          body: data,
          headers: { 'Accept': 'application/json' }
        })
          .then(function (res) {
            return res.json().catch(function () { return { ok: false, error: 'Invalid server response.' }; });
          })
          .then(function (json) {
            if (json && json.ok) {
              // Replace form with thank-you
              form.innerHTML =
                '<div class="form-message visible success" style="text-align:center; padding:2rem;">' +
                '<h3 style="margin-bottom:.5rem; color:#065F46;">Thanks! We got your request.</h3>' +
                '<p style="margin:0; color:#065F46;">Expect a text or email from us within the hour during business hours (Mon–Sat, 8am–6pm). ' +
                'Prefer a quicker reply? <a href="sms:+16562240404?&body=Hi,%20I\'d%20like%20a%20free%20cleaning%20quote." style="text-decoration:underline;">Text us at (656) 224-0404</a>.</p>' +
                '</div>';
            } else {
              showMessage('error', (json && json.error) ? json.error : 'Something went wrong. Please text us at (656) 224-0404.');
              if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = 'Send My Quote Request <i data-lucide="arrow-right"></i>'; if (window.lucide) window.lucide.createIcons(); }
            }
          })
          .catch(function () {
            showMessage('error', 'Network error. Please text us at (656) 224-0404.');
            if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = 'Send My Quote Request <i data-lucide="arrow-right"></i>'; if (window.lucide) window.lucide.createIcons(); }
          });
      });
    }

  });
})();
