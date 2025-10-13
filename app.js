document.getElementById('year').textContent = new Date().getFullYear();

const searchToggle = document.getElementById('searchToggle');
const searchBar = document.getElementById('searchBar');
searchToggle.addEventListener('click', () => {
  const isOpen = !searchBar.hasAttribute('hidden');
  if (isOpen) {
    searchBar.setAttribute('hidden', '');
    searchToggle.setAttribute('aria-expanded', 'false');
  } else {
    searchBar.removeAttribute('hidden');
    searchToggle.setAttribute('aria-expanded', 'true');
    setTimeout(() => document.getElementById('q').focus(), 0);
  }
});

const MEGA_OPEN_CLASS = 'open';
const menuButtons = document.querySelectorAll('.has-mega > button[data-menu]');

function closeAllMegas() {
  document.querySelectorAll('.mega').forEach(m => m.classList.remove(MEGA_OPEN_CLASS));
  menuButtons.forEach(b => b.setAttribute('aria-expanded', 'false'));
}

menuButtons.forEach(btn => {
  const id = 'mega-' + btn.dataset.menu;
  const panel = document.getElementById(id);

  btn.parentElement.addEventListener('mouseenter', () => {
    if (window.matchMedia('(min-width:981px)').matches) {
      closeAllMegas();
      panel.classList.add(MEGA_OPEN_CLASS);
      btn.setAttribute('aria-expanded', 'true');
    }
  });
  btn.parentElement.addEventListener('mouseleave', () => {
    if (window.matchMedia('(min-width:981px)').matches) {
      panel.classList.remove(MEGA_OPEN_CLASS);
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  btn.addEventListener('click', (e) => {
    if (!window.matchMedia('(min-width:981px)').matches) {
      const open = panel.classList.contains(MEGA_OPEN_CLASS);
      closeAllMegas();
      if (!open) {
        panel.classList.add(MEGA_OPEN_CLASS);
        btn.setAttribute('aria-expanded', 'true');
      }
    }
  });
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeAllMegas();
});

const hamburger = document.getElementById('hamburger');
const primaryNav = document.querySelector('.primary-nav');
const desktopMenu = document.querySelector('.menu');

hamburger.addEventListener('click', () => {
  const open = desktopMenu.style.display === 'block';
  desktopMenu.style.display = open ? 'none' : 'block';
  hamburger.setAttribute('aria-expanded', String(!open));
});

document.addEventListener('click', (e) => {
  const insideMega = e.target.closest('.has-mega');
  if (!insideMega && window.matchMedia('(min-width:981px)').matches) {
    closeAllMegas();
  }
});
