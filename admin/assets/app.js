document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const topLinks = document.querySelectorAll('#sidebar .side-menu.top li a');
  const defaultActiveLi = document.querySelector('#sidebar .side-menu.top li.active');

  function setActiveLi(li) {
    topLinks.forEach(link => link.parentElement.classList.remove('active'));
    if (li) li.classList.add('active');
  }

  // Set active tab based on current URL (and also for product/category edit/add/delete pages).
  const path = window.location.pathname || '';
  let activeLi = defaultActiveLi;

  // 1) Exact match against non-# sidebar hrefs.
  topLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (!href || href === '#') return;
    try {
      const abs = new URL(href, window.location.href);
      if (abs.pathname === window.location.pathname) {
        activeLi = link.parentElement;
      }
    } catch (e) {
      // Ignore malformed href
    }
  });

  // 2) Section match for pages not linked directly (e.g. product-add/edit/delete).
  if (!activeLi) {
    if (path.includes('product-')) {
      const productLink = Array.from(topLinks).find(a => {
        const href = a.getAttribute('href');
        return href && href !== '#' && href.includes('product');
      });
      activeLi = productLink ? productLink.parentElement : null;
    } else if (path.includes('category-')) {
      const categoryLink = Array.from(topLinks).find(a => {
        const href = a.getAttribute('href');
        return href && href !== '#' && href.includes('category');
      });
      activeLi = categoryLink ? categoryLink.parentElement : null;
    } else if (path.includes('dashboard')) {
      const dashboardLink = Array.from(topLinks).find(a => {
        const href = a.getAttribute('href');
        return href && href !== '#' && href.includes('dashboard');
      });
      activeLi = dashboardLink ? dashboardLink.parentElement : null;
    }
  }

  setActiveLi(activeLi);

  // Keep active state in sync when user clicks sidebar items.
  topLinks.forEach(item => {
    item.addEventListener('click', function () {
      setActiveLi(item.parentElement);
    });
  });

  // Mobile sidebar behavior (CSS expects #sidebar.hide).
  function adjustSidebar() {
    if (!sidebar) return;
    if (window.innerWidth <= 576) {
      sidebar.classList.add('hide');
      sidebar.classList.remove('show');
    } else {
      sidebar.classList.remove('hide');
      sidebar.classList.add('show');
    }
  }

  adjustSidebar();
  window.addEventListener('resize', adjustSidebar);
});