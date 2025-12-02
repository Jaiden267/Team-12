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
const CART_KEY = 'lc_cart_v1';

function loadCart(){
  try{ return JSON.parse(localStorage.getItem(CART_KEY)) || []; }catch{ return []; }
}
function saveCart(items){ localStorage.setItem(CART_KEY, JSON.stringify(items)); updateCartCount(); }
function updateCartCount(){
  const count = loadCart().reduce((n, it)=> n + Number(it.qty||0), 0);
  const el = document.getElementById('cartCount');
  if (el) el.textContent = count ? `(${count})` : '';
}
updateCartCount();

document.addEventListener('submit', function(e){
  const form = e.target.closest('.add-to-cart-form');
  if (!form) return;
  e.preventDefault();

  const item = {
    sku: form.dataset.sku,
    name: form.dataset.name,
    price: Number(form.dataset.price),
    image: form.dataset.image || '',
    color: form.querySelector('input[name="color"]:checked')?.value || 'default',
    size: form.querySelector('select[name="size"]')?.value || 'M',
    qty: Number(form.querySelector('input[name="qty"]')?.value || 1),
  };

  if (item.qty < 1) { alert('Quantity must be at least 1'); return; }

  const cart = loadCart();

  const key = (i)=> `${i.sku}-${i.color}-${i.size}`;
  const idx = cart.findIndex(i => key(i) === key(item));
  if (idx >= 0) cart[idx].qty += item.qty; else cart.push(item);

  saveCart(cart);
  alert(`Added ${item.qty} × ${item.name} (${item.color.toUpperCase()}, ${item.size}) to cart.`);
});

document.addEventListener('click', function(e){
  const sw = e.target.closest('.swatch');
  if (!sw) return;
  const wrap = sw.closest('.swatches');
  wrap.querySelectorAll('.swatch').forEach(s=>s.classList.remove('active'));
  sw.classList.add('active');
  const radio = wrap.querySelector(`input[type="radio"][value="${sw.dataset.color}"]`);
  if (radio) radio.checked = true;
});

// Validation for contact form
document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.querySelector(".contact-form form");
  if (!contactForm) return;

  contactForm.addEventListener("submit", function (e) {
    const nameInput = contactForm.querySelector("input[name='name']");
    const emailInput = contactForm.querySelector("input[name='email']");
    const messageInput = contactForm.querySelector("textarea[name='message']");

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const message = messageInput.value.trim();

    // Email regex pattern
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let errors = [];

    // Reset borders
    nameInput.style.border = "";
    emailInput.style.border = "";
    messageInput.style.border = "";

    // Validate name
    if (name.length < 2) {
      errors.push("• Name must be at least 2 characters");
      nameInput.style.border = "2px solid red";
    }

    // Validate email
    if (!emailRegex.test(email)) {
      errors.push("• Enter a valid email address");
      emailInput.style.border = "2px solid red";
    }

    // Validate message
    if (message.length < 5) {
      errors.push("• Message must be at least 5 characters long");
      messageInput.style.border = "2px solid red";
    }

    // If there are ANY errors → show one popup message
    if (errors.length > 0) {
      e.preventDefault(); // stop form submission

      alert("Please fix the following:\n\n" + errors.join("\n"));
      return false;
    }
  });
});
// End of contact form validation

// DARK MODE
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");

// Check saved preference
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
    setSunIcon();
}

themeToggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
        setSunIcon();
    } else {
        localStorage.setItem("theme", "light");
        setMoonIcon();
    }
});

// Swap SVG icons
function setSunIcon() {
    themeIcon.innerHTML = `
        <circle cx="12" cy="12" r="5" fill="none" stroke="currentColor" stroke-width="2"/>
        <line x1="12" y1="1" x2="12" y2="4" stroke="currentColor" stroke-width="2"/>
        <line x1="12" y1="20" x2="12" y2="23" stroke="currentColor" stroke-width="2"/>
        <line x1="1" y1="12" x2="4" y2="12" stroke="currentColor" stroke-width="2"/>
        <line x1="20" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"/>
    `;
}

function setMoonIcon() {
    themeIcon.innerHTML = `
        <path d="M21 12.79A9 9 0 0111.21 3a7 7 0 100 14A9 9 0 0121 12.79z"
              fill="currentColor"/>
    `;
}


// ACCESSIBILITY ZOOM
let zoomLevel = 1;

document.getElementById("zoomIn").addEventListener("click", () => {
    zoomLevel += 0.1;
    document.body.style.transform = `scale(${zoomLevel})`;
    document.body.style.transformOrigin = "top center";
});

document.getElementById("zoomOut").addEventListener("click", () => {
    zoomLevel = Math.max(1, zoomLevel - 0.1);
    document.body.style.transform = `scale(${zoomLevel})`;
    document.body.style.transformOrigin = "top center";
});
