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

  const sizeSelect = form.querySelector('select[name="size"]');
  const sizeId = sizeSelect?.value || 'M';
  const sizeName = sizeSelect?.options[sizeSelect.selectedIndex]?.text || sizeId;
  const item = {
    sku: form.dataset.sku,
    name: form.dataset.name,
    price: Number(form.dataset.price),
    image: form.dataset.image || '',
    color: form.querySelector('input[name="color"]:checked')?.value || 'default',
    size: sizeId,               
    attribute_value: sizeName,  
    qty: Number(form.querySelector('input[name="qty"]')?.value || 1),
  };

  if (item.qty < 1) { alert('Quantity must be at least 1'); return; }

  const cart = loadCart();

  const key = (i)=> `${i.sku}-${i.color}-${i.size}`;
  const idx = cart.findIndex(i => key(i) === key(item));
  if (idx >= 0) cart[idx].qty += item.qty; else cart.push(item);

  saveCart(cart);
  alert(`Added ${item.qty} × ${item.name} (${item.attribute_value || item.size}) to cart.`);
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

document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.querySelector(".contact-form form");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      const nameInput = contactForm.querySelector("input[name='name']");
      const emailInput = contactForm.querySelector("input[name='email']");
      const messageInput = contactForm.querySelector("textarea[name='message']");

      const name = nameInput.value.trim();
      const email = emailInput.value.trim();
      const message = messageInput.value.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;

      let errors = [];

      nameInput.style.border = "";
      emailInput.style.border = "";
      messageInput.style.border = "";

      if (name.length < 2) {
        errors.push("• Name must be at least 2 characters");
        nameInput.style.border = "2px solid red";
      }

      if (!emailRegex.test(email)) {
        errors.push("• Enter a valid email address");
        emailInput.style.border = "2px solid red";
      }

      if (message.length < 5) {
        errors.push("• Message must be at least 5 characters long");
        messageInput.style.border = "2px solid red";
      }

      if (errors.length > 0) {
        e.preventDefault();
        alert("Please fix the following:\n\n" + errors.join("\n"));
        return false;
      }
    });
  }

  const registerForm = document.querySelector('form[action="register_process.php"]');
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      const emailInput = registerForm.querySelector("input[name='email']");
      const email = emailInput.value.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;

      emailInput.style.border = "";

      if (!emailRegex.test(email)) {
        e.preventDefault();
        emailInput.style.border = "2px solid red";
        alert("Please enter a valid email address (example: name@example.com).");
      }
    });
  }

  const searchForm = document.getElementById("searchForm");
  const searchInput = document.getElementById("q");

  if (searchForm && searchInput) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const term = searchInput.value.trim();
      if (!term) return;  
      
      window.location.href = "search.php?q=" + encodeURIComponent(term);
    });
  }
});

document.getElementById("searchForm")?.addEventListener("submit", function (e) {
    e.preventDefault();
    let query = document.getElementById("q").value.trim();

    if (query.length === 0) return;

    window.location.href = "search.php?q=" + encodeURIComponent(query);
});


function fmtPrice(n){
  return '£' + (Number(n || 0)).toFixed(2);
}

function renderCartPreview(){
  const wrap = document.getElementById('cartPreview');
  const list = document.getElementById('cartPreviewItems');
  const totalEl = document.getElementById('cartPreviewTotal');

  if (!wrap || !list || !totalEl) return;

  const items = loadCart();
  list.innerHTML = '';

  if (!items.length){
    list.innerHTML = '<p class="cart-preview-meta">Your basket is empty.</p>';
    totalEl.textContent = fmtPrice(0);
    return;
  }

  let subtotal = 0;

  items.slice(0, 3).forEach(it => {
    const line = (it.price || 0) * (it.qty || 0);
    subtotal += line;

    const div = document.createElement('div');
    div.className = 'cart-preview-item';
    div.innerHTML = `
      <img class="cart-preview-img" src="${it.image || ''}" alt="">
      <div class="cart-preview-text">
        <div class="cart-preview-name">${it.name || ''}</div>
        <div class="cart-preview-meta">
          ${it.attribute_value || it.size || ''} • Qty: ${it.qty || 1}
        </div>
        <div class="cart-preview-meta">${fmtPrice(it.price || 0)}</div>
      </div>
    `;
    list.appendChild(div);
  });

  if (items.length > 3){
    const more = document.createElement('div');
    more.className = 'cart-preview-meta';
    more.style.paddingTop = '4px';
    more.textContent = `+ ${items.length - 3} more item(s)…`;
    list.appendChild(more);
  }

  totalEl.textContent = fmtPrice(
    items.reduce((sum, it) => sum + (it.price || 0) * (it.qty || 0), 0)
  );
}

(function initCartHover(){
  const btn = document.getElementById('cartButton');
  const preview = document.getElementById('cartPreview');
  if (!btn || !preview) return;

  let hideTimer = null;

  function showPreview(){
    clearTimeout(hideTimer);
    renderCartPreview();
    preview.classList.add('open');
  }

  function hidePreview(){
    hideTimer = setTimeout(() => {
      preview.classList.remove('open');
    }, 150);
  }

  [btn, preview].forEach(el => {
    el.addEventListener('mouseenter', showPreview);
    el.addEventListener('mouseleave', hidePreview);
  });
})();
