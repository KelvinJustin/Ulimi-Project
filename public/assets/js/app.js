(() => {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
      return;
    }
    document.addEventListener('DOMContentLoaded', fn);
  }

  window.switchTab = function switchTab(btn, tabId) {
    document.querySelectorAll('.tab-btn').forEach((b) => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach((p) => p.classList.remove('active'));
    btn.classList.add('active');
    const pane = document.getElementById('tab-' + tabId);
    if (pane) {
      pane.classList.add('active');
    }
  };

  ready(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add('visible');
          }
        });
      },
      { threshold: 0.12 },
    );

    document.querySelectorAll('.fade-up').forEach((el) => observer.observe(el));

    window.addEventListener('scroll', () => {
      const h = document.querySelector('.header');
      if (!h) {
        return;
      }
      h.style.boxShadow = window.scrollY > 20 ? '0 2px 20px rgba(43,42,37,0.08)' : 'none';
    });

    document.querySelectorAll('a[href^="#"]').forEach((a) => {
      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (!href || href === '#') {
          return;
        }
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  });
})();
