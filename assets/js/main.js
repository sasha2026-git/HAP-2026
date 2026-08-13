/**
 * HireAI People Child — main.js
 * 导航交互 / 滚动效果 / FAQ 手风琴与实时检索 / 解决方案筛选 / 滚动显现
 */
(function () {
	'use strict';

	document.documentElement.classList.add('js');

	var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* 1. Sticky header —— 滚动后加深玻璃拟态 */
	var header = document.querySelector('.site-header');
	function onScroll() {
		if (!header) {
			return;
		}
		if (window.scrollY > 8) {
			header.classList.add('is-scrolled');
		} else {
			header.classList.remove('is-scrolled');
		}
	}
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });

	/* 2. 移动端导航 */
	var navToggle = document.querySelector('.nav-toggle');
	if (navToggle) {
		navToggle.addEventListener('click', function () {
			var open = document.body.classList.toggle('nav-open');
			navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		// 点击菜单链接后收起
		document.querySelectorAll('.hireai-nav a').forEach(function (link) {
			link.addEventListener('click', function () {
				document.body.classList.remove('nav-open');
				navToggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	/* 3. FAQ 手风琴（页面 + 首页精选共用） */
	document.querySelectorAll('.faq-item__toggle').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.faq-item');
			var open = item.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	/* 4. FAQ 关键词实时检索（纯 JS 前端过滤 + 高亮） */
	var faqSearch = document.getElementById('faq-search-input');
	if (faqSearch) {
		var faqItems  = Array.prototype.slice.call(document.querySelectorAll('.faq-item'));
		var faqGroups = Array.prototype.slice.call(document.querySelectorAll('[data-faq-group]'));
		var faqEmpty  = document.querySelector('[data-faq-empty]');

		// 缓存原始文本，便于恢复后重新高亮
		faqItems.forEach(function (item) {
			var q = item.querySelector('.faq-item__q-text');
			var a = item.querySelector('.faq-item__a-text');
			if (q) {
				q.setAttribute('data-orig', q.textContent);
			}
			if (a) {
				a.setAttribute('data-orig', a.textContent);
			}
		});

		faqSearch.addEventListener('input', function () {
			var query = faqSearch.value.trim().toLowerCase();
			var totalVisible = 0;

			faqItems.forEach(function (item) {
				var q = item.querySelector('.faq-item__q-text');
				var a = item.querySelector('.faq-item__a-text');
				var qText = q ? (q.getAttribute('data-orig') || '') : '';
				var aText = a ? (a.getAttribute('data-orig') || '') : '';

				// 恢复原始文本
				if (q && q.getAttribute('data-orig') !== null) {
					q.textContent = qText;
				}
				if (a && a.getAttribute('data-orig') !== null) {
					a.textContent = aText;
				}

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
					if (qMatch && q) {
						highlight(q, query);
					}
					if (aMatch && a) {
						highlight(a, query);
					}
				} else {
					item.style.display = 'none';
				}
			});

			// 分组显隐
			faqGroups.forEach(function (group) {
				var visible = Array.prototype.some.call(group.querySelectorAll('.faq-item'), function (i) {
					return i.style.display !== 'none';
				});
				group.style.display = visible ? '' : 'none';
			});

			if (faqEmpty) {
				faqEmpty.style.display = totalVisible ? 'none' : '';
			}
		});
	}

	/* 5. 解决方案场景筛选 */
	var chips        = Array.prototype.slice.call(document.querySelectorAll('.solution-filters .chip'));
	var productCards = Array.prototype.slice.call(document.querySelectorAll('.hireai-product-grid .product-card'));
	var emptyMsg     = document.querySelector('[data-solution-empty]');

	chips.forEach(function (chip) {
		chip.addEventListener('click', function () {
			chips.forEach(function (c) {
				c.classList.remove('is-active');
			});
			chip.classList.add('is-active');

			var filter = chip.getAttribute('data-filter') || '';
			var shown = 0;

			productCards.forEach(function (card) {
				var cats = (card.getAttribute('data-cats') || '').split(' ').filter(Boolean);
				var match = filter === '' || cats.indexOf(filter) !== -1;
				card.style.display = match ? '' : 'none';
				if (match) {
					shown++;
				}
			});

			if (emptyMsg) {
				emptyMsg.style.display = shown ? 'none' : '';
			}
		});
	});

	/* 6. 滚动显现（尊重减少动态效果偏好） */
	function showAllReveal() {
		document.querySelectorAll('[data-reveal]').forEach(function (el) {
			el.classList.add('is-visible');
		});
	}

	if (prefersReduced) {
		showAllReveal();
	} else if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

		document.querySelectorAll('[data-reveal]').forEach(function (el) {
			io.observe(el);
		});
	} else {
		showAllReveal();
	}

	/**
	 * 将 el 中首个匹配关键词高亮为 <mark>
	 */
	function highlight(el, query) {
		var text = el.textContent;
		var idx = text.toLowerCase().indexOf(query);
		if (idx === -1) {
			return;
		}
		var before = document.createTextNode(text.slice(0, idx));
		var mark = document.createElement('mark');
		mark.textContent = text.slice(idx, idx + query.length);
		var after = document.createTextNode(text.slice(idx + query.length));
		el.textContent = '';
		el.appendChild(before);
		el.appendChild(mark);
		el.appendChild(after);
	}
})();
