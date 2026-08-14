/**
 * HireAI People Child v1.0.4 — main.js
 * 抽屉导航 / FAQ 检索与分类 / 方案筛选 / 滚动显现
 */
(function () {
	'use strict';

	document.documentElement.classList.add('js');

	var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* 1. Header scroll state */
	var header = document.querySelector('.site-header');
	function syncHeader() {
		if (!header) return;
		header.classList.toggle('is-scrolled', window.scrollY > 8);
	}
	syncHeader();
	window.addEventListener('scroll', syncHeader, { passive: true });

	/* 2. Mobile drawer */
	var navToggle = document.getElementById('nav-toggle');
	var drawer = document.querySelector('[data-mobile-drawer]');
	var overlay = document.querySelector('[data-drawer-overlay]');
	var drawerClose = document.querySelector('[data-drawer-close]');

	function setDrawer(open) {
		if (!drawer) return;
		document.body.classList.toggle('drawer-open', open);
		drawer.classList.toggle('is-open', open);
		drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
		if (overlay) overlay.hidden = !open;
		if (navToggle) navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	if (navToggle) {
		navToggle.addEventListener('click', function () { setDrawer(true); });
	}
	if (drawerClose) {
		drawerClose.addEventListener('click', function () { setDrawer(false); });
	}
	if (overlay) {
		overlay.addEventListener('click', function () { setDrawer(false); });
	}
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) setDrawer(false);
	});
	if (drawer) {
		drawer.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () { setDrawer(false); });
		});
	}

	/* 3. FAQ accordion */
	document.querySelectorAll('.faq-item__toggle').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.faq-item');
			var open = item.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	/* 4. FAQ search + category filter */
	var faqSearch = document.getElementById('faq-search-input');
	var faqCategoryButtons = Array.prototype.slice.call(document.querySelectorAll('.faq-category'));
	var faqItems = Array.prototype.slice.call(document.querySelectorAll('.faq-item'));
	var faqGroups = Array.prototype.slice.call(document.querySelectorAll('[data-faq-group]'));
	var faqEmpty = document.querySelector('[data-faq-empty]');
	var activeFaqCategory = '';

	function syncFaqEmpty() {
		if (!faqEmpty) return;
		var anyVisible = false;
		faqGroups.forEach(function (group) {
			if (group.style.display === 'none') return;
			Array.prototype.forEach.call(group.querySelectorAll('.faq-item'), function (item) {
				if (item.style.display !== 'none') anyVisible = true;
			});
		});
		faqEmpty.style.display = anyVisible ? 'none' : '';
	}

	function highlight(el, query) {
		var text = el.textContent;
		var idx = text.toLowerCase().indexOf(query);
		if (idx === -1) return;
		var before = document.createTextNode(text.slice(0, idx));
		var mark = document.createElement('mark');
		mark.textContent = text.slice(idx, idx + query.length);
		var after = document.createTextNode(text.slice(idx + query.length));
		el.textContent = '';
		el.appendChild(before);
		el.appendChild(mark);
		el.appendChild(after);
	}

	function applyFaqSearch() {
		var query = faqSearch ? faqSearch.value.trim().toLowerCase() : '';
		var totalVisible = 0;

		faqItems.forEach(function (item) {
			var q = item.querySelector('.faq-item__q-text');
			var a = item.querySelector('.faq-item__a-text');
			var qText = q ? (q.getAttribute('data-orig') || '') : '';
			var aText = a ? (a.getAttribute('data-orig') || '') : '';

			if (q && q.getAttribute('data-orig') !== null) q.textContent = qText;
			if (a && a.getAttribute('data-orig') !== null) a.textContent = aText;

			if (!query) {
				item.style.display = '';
				totalVisible++;
				return;
			}

			var qMatch = qText.toLowerCase().indexOf(query) !== -1;
			var aMatch = aText.toLowerCase().indexOf(query) !== -1;
			if (qMatch || aMatch) {
				item.style.display = '';
				totalVisible++;
				if (qMatch && q) highlight(q, query);
				if (aMatch && a) highlight(a, query);
			} else {
				item.style.display = 'none';
			}
		});

		faqGroups.forEach(function (group) {
			var category = group.getAttribute('data-faq-category-group') || '';
			var categoryMatch = activeFaqCategory === '' || category === activeFaqCategory;
			var visible = categoryMatch && Array.prototype.some.call(group.querySelectorAll('.faq-item'), function (i) {
				return i.style.display !== 'none';
			});
			group.style.display = visible ? '' : 'none';
		});

		syncFaqEmpty();
	}

	if (faqItems.length) {
		faqItems.forEach(function (item) {
			var q = item.querySelector('.faq-item__q-text');
			var a = item.querySelector('.faq-item__a-text');
			if (q) q.setAttribute('data-orig', q.textContent);
			if (a) a.setAttribute('data-orig', a.textContent);
		});
	}

	if (faqSearch) faqSearch.addEventListener('input', applyFaqSearch);

	faqCategoryButtons.forEach(function (btn) {
		btn.addEventListener('click', function () {
			faqCategoryButtons.forEach(function (b) {
				b.classList.remove('is-active');
				b.setAttribute('aria-selected', 'false');
			});
			btn.classList.add('is-active');
			btn.setAttribute('aria-selected', 'true');
			activeFaqCategory = btn.getAttribute('data-faq-category') || '';
			applyFaqSearch();
		});
	});

	if (faqItems.length || faqGroups.length) applyFaqSearch();

	/* 5. Solutions filter */
	var chips = Array.prototype.slice.call(document.querySelectorAll('.solution-filter-bar .solution-filter'));
	var productCards = Array.prototype.slice.call(document.querySelectorAll('.hireai-product-grid .product-card'));
	var emptyMsg = document.querySelector('[data-solution-empty]');

	chips.forEach(function (chip) {
		chip.addEventListener('click', function () {
			chips.forEach(function (c) { c.classList.remove('is-active'); });
			chip.classList.add('is-active');

			var filter = chip.getAttribute('data-filter') || '';
			var shown = 0;
			productCards.forEach(function (card) {
				var cats = (card.getAttribute('data-cats') || '').split(' ').filter(Boolean);
				var match = filter === '' || cats.indexOf(filter) !== -1;
				card.style.display = match ? '' : 'none';
				if (match) shown++;
			});
			if (emptyMsg) emptyMsg.style.display = shown ? 'none' : '';
		});
	});

	/* 6. Reveal (reduced motion safe) */
	function showAllReveal() {
		document.querySelectorAll('[data-reveal]').forEach(function (el) { el.classList.add('is-visible'); });
	}

	if (prefersReduced || !('IntersectionObserver' in window)) {
		showAllReveal();
	} else {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

		document.querySelectorAll('[data-reveal]').forEach(function (el) { io.observe(el); });
	}
})();
