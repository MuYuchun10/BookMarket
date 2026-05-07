(function () {
    /* Ключи localStorage для корзины и истории заказов */
    const STORAGE_KEY = 'bookMarketCart';
    const PRODUCT_CATALOG = Array.isArray(window.BOOKMARKET_PRODUCTS) ? window.BOOKMARKET_PRODUCTS : [];
    const PRODUCT_INDEX = PRODUCT_CATALOG.reduce((accumulator, product) => {
        accumulator[product.id] = product;
        return accumulator;
    }, {});

    /* Экранирование HTML перед вставкой в шаблонные строки */
    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* Экранирование текста для вставки внутрь SVG */
    function escapeSvgText(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    /* Извлечение числа цены из текста */
    function parsePrice(text) {
        return Number(String(text || '').replace(/[^\d]/g, '')) || 0;
    }

    /* Форматирование цены в вид "1 234 ₽" */
    function formatPrice(value) {
        return new Intl.NumberFormat('ru-RU').format(Math.max(0, Number(value) || 0)) + ' ₽';
    }

    /* Склонение слова по числу */
    function pluralize(count, one, few, many) {
        const normalized = Math.abs(Number(count)) % 100;
        const lastDigit = normalized % 10;

        if (normalized > 10 && normalized < 20) {
            return many;
        }
        if (lastDigit > 1 && lastDigit < 5) {
            return few;
        }
        if (lastDigit === 1) {
            return one;
        }
        return many;
    }

    /* Преобразование SVG-строки в data URL */
    function svgToDataUrl(svg) {
        return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg)
            .replace(/%0A/g, '')
            .replace(/%20/g, ' ');
    }

    /* Приведение строки к slug-формату для id и URL-параметров */
    function slugify(value) {
        return String(value || '')
            .toLowerCase()
            .replace(/ё/g, 'е')
            .replace(/[^a-zа-я0-9]+/gi, '-')
            .replace(/^-+|-+$/g, '');
    }

    /* Обновление query-параметров в адресной строке без перезагрузки */
    function getCatalogProductById(productId) {
        const normalizedId = String(productId || '').trim();
        return normalizedId ? PRODUCT_INDEX[normalizedId] || null : null;
    }

    /* Создание ссылки на страницу книги */
    function buildBookPageUrl(productId) {
        const normalizedId = String(productId || '').trim();
        return normalizedId ? `book.php?id=${encodeURIComponent(normalizedId)}` : 'book.php';
    }

    /* Приведение товара к нормальному виду */
    function enrichProductData(product) {
        if (!product) {
            return null;
        }

        const catalogProduct = getCatalogProductById(product.id);
        const merged = {
            ...(catalogProduct || {}),
            ...product
        };

        merged.id = String(merged.id || '').trim() || slugify(merged.name) || 'book-item';
        merged.name = String(merged.name || 'Книга').trim();
        merged.author = String(merged.author || 'Неизвестный автор').trim();
        merged.price = Math.max(0, Number(merged.price) || 0);
        merged.quantity = Math.max(1, Number(merged.quantity) || 1);
        merged.category = String(merged.category || 'Книги').trim();
        merged.categorySlug = String(merged.categorySlug || slugify(merged.category)).trim();
        merged.description = String(merged.description || `Книга «${merged.name}»`).trim();
        merged.link = String(merged.link || buildBookPageUrl(merged.id)).trim();
        merged.image = String(merged.image || merged.images?.front || '').trim();
        merged.badge = String(merged.badge || merged.category).trim();
        merged.statusText = String(merged.statusText || (merged.available ? 'В наличии' : 'Нет в наличии')).trim();
        merged.available = merged.available !== false;
        merged.fullDescription = Array.isArray(merged.fullDescription) && merged.fullDescription.length
            ? merged.fullDescription
            : [merged.description];
        merged.specs = Array.isArray(merged.specs) ? merged.specs : [];
        merged.images = {
            front: String(merged.images?.front || merged.image || '').trim(),
            back: String(merged.images?.back || '').trim(),
            spread: String(merged.images?.spread || '').trim()
        };

        return merged;
    }

    /* Обновление Url */
    function updateUrlFromParams(params) {
        const query = params.toString();
        const nextUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
        window.history.replaceState({}, '', nextUrl);
    }

    /* Показ временного toast-уведомления */
    function showToast(message) {
        let toast = document.querySelector('.toast-message');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'toast-message';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(showToast.timer);
        showToast.timer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2400);
    }

    /* Генерация заглушки обложки книги в SVG */
    function createCoverDataUrl(label, tone) {
        const safeLabel = escapeSvgText(label || 'BookMarket');
        const fillTone = tone || '#ebe5dd';
        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" width="900" height="1200" viewBox="0 0 900 1200">
                <rect width="900" height="1200" rx="40" fill="${fillTone}" />
                <text x="450" y="560" text-anchor="middle" font-size="46" font-family="Inter, Arial, sans-serif" font-weight="600" fill="#2b2b2b">${safeLabel}</text>
                <text x="450" y="630" text-anchor="middle" font-size="24" font-family="Inter, Arial, sans-serif" fill="#5f5a55">Изображение временно отсутствует</text>
            </svg>
        `.replace(/\s+/g, ' ').trim();

        return svgToDataUrl(svg);
    }

    /* Генерация заглушки изображения для галереи книги */
    function createGalleryImageUrl(title, view, tone) {
        const safeTitle = escapeSvgText(title || 'BookMarket');
        const fillTone = tone || '#ebe5dd';
        const currentView = view || 'front';
        const labels = {
            front: 'Вид спереди',
            back: 'Вид сзади',
            spread: 'Разворот'
        };
        const viewLabel = labels[currentView] || labels.front;

        return svgToDataUrl(`
            <svg xmlns="http://www.w3.org/2000/svg" width="1200" height="900" viewBox="0 0 1200 900">
                <rect width="1200" height="900" rx="36" fill="${fillTone}" />
                <text x="600" y="430" text-anchor="middle" font-size="56" font-family="Inter, Arial, sans-serif" font-weight="600" fill="#2b2b2b">${safeTitle}</text>
                <text x="600" y="520" text-anchor="middle" font-size="28" font-family="Inter, Arial, sans-serif" fill="#5f5a55">${viewLabel}</text>
                <text x="600" y="600" text-anchor="middle" font-size="24" font-family="Inter, Arial, sans-serif" fill="#5f5a55">Изображение временно отсутствует</text>
            </svg>
        `.replace(/\s+/g, ' ').trim());
    }

    /* Формирование HTML-разметки обложки книги */
    function createCoverMarkup(title, extraClass, imageSrc, tone) {
        const wrapperClass = extraClass || 'quick-view__cover';
        const fallback = createCoverDataUrl(title, tone);
        const source = imageSrc || fallback;
        return `<div class="${escapeHtml(wrapperClass)}"><img class="cover-media" src="${escapeHtml(source)}" alt="Обложка книги ${escapeHtml(title)}" loading="lazy" onerror="this.onerror=null;this.src='${escapeHtml(fallback)}'" /></div>`;
    }

    /* Поиск контейнера товара по кнопке или внутреннему элементу */
    function getProductContainer(trigger) {
        return trigger.closest('[data-product-id]');
    }

    /* Определение доступности товара и текста статуса */
    function getProductAvailability(container) {
        if (!container) {
            return {
                available: false,
                statusText: 'Нет в наличии'
            };
        }

        const statusElement = container.querySelector('.product-card__status, .book-card__stock');
        const statusText = (container.dataset.productStatus || (statusElement ? statusElement.textContent : '') || '').trim();
        const explicitValue = container.dataset.productAvailable;

        let available;
        if (explicitValue === 'true' || explicitValue === 'false') {
            available = explicitValue === 'true';
        } else {
            const statusClassOut = Boolean(statusElement && statusElement.classList.contains('product-card__status--out'));
            const statusSaysOut = /нет в наличии/i.test(statusText);
            available = !(statusClassOut || statusSaysOut);
        }

        return {
            available,
            statusText: statusText || (available ? 'В наличии' : 'Нет в наличии')
        };
    }

    /* Сбор данных товара из data-атрибутов и DOM */
    function getProductData(container) {
        if (!container) {
            return null;
        }

        const title = container.dataset.productTitle || (container.querySelector('.product-card__title, .book-card__title') || {}).textContent || 'Книга';
        const author = container.dataset.productAuthor || (container.querySelector('.product-card__author, .book-card__author') || {}).textContent || 'Неизвестный автор';
        const price = Number(container.dataset.productPrice) || parsePrice((container.querySelector('.product-card__price, .book-card__price') || {}).textContent);
        const category = container.dataset.productCategory || 'Книги';
        const description = container.dataset.productDescription || `Книга «${title.trim()}» автора ${author.trim()}.`;
        const link = container.dataset.productLink || buildBookPageUrl(container.dataset.productId || title.trim().toLowerCase().replace(/\s+/g, '-'));
        const image = container.dataset.productImage || '';
        const availability = getProductAvailability(container);

        return enrichProductData({
            id: container.dataset.productId || title.trim().toLowerCase().replace(/\s+/g, '-'),
            name: title.trim(),
            author: author.trim(),
            price,
            quantity: 1,
            category,
            description,
            link,
            image,
            available: availability.available,
            statusText: availability.statusText
        });
    }

    /* Нормализация товара корзины перед сохранением */
    function normalizeCartItem(item) {
        if (!item || typeof item !== 'object') {
            return null;
        }

        const price = Number(item.price) || 0;
        const quantity = Math.max(1, Number(item.quantity) || 1);
        const name = String(item.name || 'Книга').trim();

        return {
            id: String(item.id || slugify(name) || 'book-item'),
            name,
            author: String(item.author || 'Неизвестный автор').trim(),
            price,
            quantity,
            category: String(item.category || 'Книги').trim(),
            description: String(item.description || `Книга «${name}»`).trim(),
            link: String(item.link || 'catalog.php').trim(),
            image: String(item.image || '').trim(),
            available: item.available !== false,
            statusText: String(item.statusText || 'В наличии').trim()
        };
    }

    /* Генерация карточки товара */
    function createProductCardMarkup(product) {
        const normalizedProduct = enrichProductData(product);

        if (!normalizedProduct) {
            return '';
        }

        const productUrl = buildBookPageUrl(normalizedProduct.id);
        const statusClass = normalizedProduct.available ? '' : ' product-card__status--out';

        return `
        <article
            class="product-card"
            data-product-id="${escapeHtml(normalizedProduct.id)}"
            data-product-title="${escapeHtml(normalizedProduct.name)}"
            data-product-author="${escapeHtml(normalizedProduct.author)}"
            data-product-price="${normalizedProduct.price}"
            data-product-category="${escapeHtml(normalizedProduct.category)}"
            data-product-category-slug="${escapeHtml(normalizedProduct.categorySlug)}"
            data-product-description="${escapeHtml(normalizedProduct.description)}"
            data-product-image="${escapeHtml(normalizedProduct.images.front || normalizedProduct.image || '')}"
            data-product-link="${escapeHtml(productUrl)}"
            data-product-available="${normalizedProduct.available ? 'true' : 'false'}"
            data-product-status="${escapeHtml(normalizedProduct.statusText)}"
        >
            <a
                aria-label="Открыть страницу книги ${escapeHtml(normalizedProduct.name)}"
                class="product-card__image"
                href="${escapeHtml(productUrl)}"
            >
                Обложка книги
            </a>

            <div class="product-card__content">
                <h3 class="product-card__title">
                    <a href="${escapeHtml(productUrl)}">${escapeHtml(normalizedProduct.name)}</a>
                </h3>

                <p class="product-card__author">${escapeHtml(normalizedProduct.author)}</p>

                <div class="product-card__meta">
                    <p class="product-card__price">${formatPrice(normalizedProduct.price)}</p>
                    <p class="product-card__status${statusClass}">
                        ${escapeHtml(normalizedProduct.statusText)}
                    </p>
                </div>

                <div class="product-card__actions">
                    <button class="product-card__btn add-to-cart" type="button">
                        Добавить в корзину
                    </button>
                    <button class="product-card__quick-btn open-modal" type="button">
                        Быстрый просмотр
                    </button>
                </div>
            </div>
        </article>
    `;
    }

    /* Рендер карточек */
    function renderProductCards() {
        const catalogContainer = document.getElementById('catalog-products');
        const featuredContainer = document.getElementById('featured-products');

        if (catalogContainer) {
            catalogContainer.innerHTML = PRODUCT_CATALOG
                .map(createProductCardMarkup)
                .join('');
        }

        if (featuredContainer) {
            const limit = Number(featuredContainer.dataset.productsLimit) || 4;

            featuredContainer.innerHTML = PRODUCT_CATALOG
                .filter((product) => product.available).slice(0, limit)
                .map(createProductCardMarkup)
                .join('');
        }
    }

    /* Добавление товара в корзину с проверкой наличия */
    function tryAddProductToCart(product) {
        if (!product) {
            return false;
        }

        if (!product.available) {
            showToast(`«${product.name}» сейчас нет в наличии`);
            return false;
        }

        cart.add(product);
        showToast(`«${product.name}» добавлена в корзину`);
        return true;
    }

    /* Поиск товара в корзине по id */
    function getCartItem(productId) {
        const normalizedId = String(productId || '').trim();

        if (!normalizedId) {
            return null;
        }

        return cart.items.find((item) => item.id === normalizedId) || null;
    }

    /* Разметка мини-счётчика вместо кнопки "Добавить в корзину" */
    function createAddToCartCounterMarkup(productId, quantity) {
        return `
            <div class="add-to-cart-counter" data-product-id="${escapeHtml(productId)}">
                <button
                    class="add-to-cart-counter__btn"
                    type="button"
                    data-cart-control-action="decrease"
                    data-product-id="${escapeHtml(productId)}"
                    aria-label="Уменьшить количество"
                >−</button>

                <span class="add-to-cart-counter__value">
                    <span class="add-to-cart-counter__label">В корзине</span>
                    <span class="add-to-cart-counter__count">${quantity}</span>
                </span>

                <button
                    class="add-to-cart-counter__btn"
                    type="button"
                    data-cart-control-action="increase"
                    data-product-id="${escapeHtml(productId)}"
                    aria-label="Увеличить количество"
                >+</button>
            </div>
        `;
    }

    /* Синхронизация всех кнопок "Добавить в корзину" с состоянием корзины */
    function updateAddToCartControls() {
        document.querySelectorAll('[data-product-id]').forEach((container) => {
            const button = container.querySelector('.add-to-cart');

            if (!button) {
                return;
            }

            const product = getProductData(container);

            if (!product) {
                return;
            }

            const actions = button.parentElement;
            const counter = actions.querySelector('.add-to-cart-counter');
            const item = getCartItem(product.id);

            if (!item) {
                button.hidden = false;
                button.classList.remove('add-to-cart--hidden');
                button.textContent = 'Добавить в корзину';

                if (counter) {
                    counter.remove();
                }

                return;
            }

            button.hidden = true;
            button.classList.add('add-to-cart--hidden');

            const counterMarkup = createAddToCartCounterMarkup(product.id, item.quantity);

            if (counter) {
                counter.outerHTML = counterMarkup;
            } else {
                button.insertAdjacentHTML('afterend', counterMarkup);
            }
        });
    }

    /* Обработка плюса и минуса в мини-счётчике товара */
    function initAddToCartCounters() {
        if (document.body.dataset.addToCartCountersBound) {
            return;
        }

        document.addEventListener('click', function (event) {
            const controlButton = event.target.closest('[data-cart-control-action]');

            if (!controlButton) {
                return;
            }

            const productId = controlButton.dataset.productId;
            const action = controlButton.dataset.cartControlAction;
            const item = getCartItem(productId);

            if (!item) {
                return;
            }

            if (action === 'increase') {
                cart.changeQuantity(productId, item.quantity + 1);
            }

            if (action === 'decrease') {
                cart.changeQuantity(productId, item.quantity - 1);
            }
        });

        document.body.dataset.addToCartCountersBound = 'true';
    }

    /* Объект корзины */
    const cart = {
        items: [],

        /* Загрузка корзины из localStorage */
        load() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                const parsed = saved ? JSON.parse(saved) : [];
                this.items = Array.isArray(parsed)
                    ? parsed.map(normalizeCartItem).filter(Boolean)
                    : [];
            } catch (error) {
                this.items = [];
            }
        },

        /* Сохранение корзины в localStorage */
        save() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items));
        },

        /* Добавление товара в корзину */
        add(product) {
            const normalized = normalizeCartItem(product);
            if (!normalized) {
                return;
            }
            const existing = this.items.find((item) => item.id === normalized.id);
            if (existing) {
                existing.quantity += normalized.quantity;
            } else {
                this.items.push(normalized);
            }
            this.save();
            this.updateCounter();
            this.renderCart();
            renderCheckoutSummary();
            updateAddToCartControls();
        },

        /* Удаление товара из корзины */
        remove(productId) {
            this.items = this.items.filter((item) => item.id !== productId);
            this.save();
            this.updateCounter();
            this.renderCart();
            renderCheckoutSummary();
            updateAddToCartControls();
        },

        /* Полная очистка корзины */
        clear() {
            this.items = [];
            this.save();
            this.updateCounter();
            this.renderCart();
            renderCheckoutSummary();
            updateAddToCartControls();
        },

        /* Изменение количества товара */
        changeQuantity(productId, newQty) {
            const item = this.items.find((entry) => entry.id === productId);
            if (!item) {
                return;
            }

            if (newQty <= 0) {
                this.remove(productId);
                return;
            }

            item.quantity = newQty;
            this.save();
            this.updateCounter();
            this.renderCart();
            renderCheckoutSummary();
            updateAddToCartControls();
        },

        /* Общее количество товаров */
        getTotalCount() {
            return this.items.reduce((sum, item) => sum + item.quantity, 0);
        },

        /* Сумма без скидки */
        getSubtotal() {
            return this.items.reduce((sum, item) => sum + item.price * item.quantity, 0);
        },

        /* Размер скидки */
        getDiscount() {
            return Math.round(this.getSubtotal() * 0.1);
        },

        /* Итоговая сумма со скидкой */
        getTotalSum() {
            return this.getSubtotal() - this.getDiscount();
        },

        /* Обновление счётчика корзины в шапке */
        updateCounter() {
            document.querySelectorAll('.cart-count').forEach((counter) => {
                counter.textContent = this.getTotalCount();
            });
        },

        /* Рендер корзины и блока итогов */
        renderCart() {
            const cartItems = document.getElementById('cart-items');
            const subtotalEl = document.getElementById('cart-subtotal');
            const discountEl = document.getElementById('cart-discount');
            const totalEl = document.getElementById('cart-total');
            const checkoutBtn = document.getElementById('checkout-btn');

            if (!cartItems) {
                return;
            }

            if (!cartItems.dataset.bound) {
                cartItems.addEventListener('click', (event) => {
                    const actionButton = event.target.closest('[data-action]');
                    if (!actionButton) {
                        return;
                    }

                    const productId = actionButton.dataset.id;
                    const action = actionButton.dataset.action;
                    const item = this.items.find((entry) => entry.id === productId);

                    if (action === 'increase' && item) {
                        this.changeQuantity(productId, item.quantity + 1);
                    }

                    if (action === 'decrease' && item) {
                        this.changeQuantity(productId, item.quantity - 1);
                    }

                    if (action === 'remove') {
                        this.remove(productId);
                        showToast('Товар удалён из корзины');
                    }
                });
                cartItems.dataset.bound = 'true';
            }

            if (!this.items.length) {
                cartItems.classList.add('cart-items--empty');
                cartItems.innerHTML = `
                    <article class="cart-empty">
                        ${createCoverMarkup('BookMarket', 'cart-empty__cover')}
                        <div class="cart-empty__content">
                            <h2 class="cart-empty__title">Корзина пока пуста</h2>
                            <p class="cart-empty__text">Добавьте книги из каталога, и они сразу появятся здесь. Корзина сохраняется в localStorage даже после перезагрузки страницы.</p>
                            <a href="catalog.php" class="btn btn--accent">Перейти в каталог</a>
                        </div>
                    </article>
                `;
            } else {
                cartItems.classList.remove('cart-items--empty');
                cartItems.innerHTML = this.items.map((item) => `
                    <article class="cart-item">
                        <a href="${escapeHtml(item.link || 'catalog.php')}" class="cart-item__image" aria-label="Открыть страницу книги ${escapeHtml(item.name)}">
                            ${createCoverMarkup(item.name, 'cart-item__cover', item.image)}
                        </a>
                        <div class="cart-item__content">
                            <h2 class="cart-item__title"><a href="${escapeHtml(item.link || 'catalog.php')}">${escapeHtml(item.name)}</a></h2>
                            <p class="cart-item__author">${escapeHtml(item.author || 'Неизвестный автор')}</p>
                            <p class="cart-item__status">${escapeHtml(item.statusText || 'В наличии')}</p>
                        </div>
                        <div class="cart-item__counter" aria-label="Количество книги ${escapeHtml(item.name)}">
                            <button type="button" class="cart-item__counter-btn" data-action="decrease" data-id="${escapeHtml(item.id)}">−</button>
                            <span class="cart-item__counter-value">${item.quantity}</span>
                            <button type="button" class="cart-item__counter-btn" data-action="increase" data-id="${escapeHtml(item.id)}">+</button>
                        </div>
                        <div class="cart-item__summary">
                            <p class="cart-item__price">${formatPrice(item.price * item.quantity)}</p>
                            <button type="button" class="cart-item__remove" data-action="remove" data-id="${escapeHtml(item.id)}">Удалить</button>
                        </div>
                    </article>
                `).join('');
            }

            if (subtotalEl) {
                subtotalEl.textContent = formatPrice(this.getSubtotal());
            }
            if (discountEl) {
                discountEl.textContent = formatPrice(this.getDiscount());
            }
            if (totalEl) {
                totalEl.textContent = formatPrice(this.getTotalSum());
            }
            if (checkoutBtn) {
                if (!checkoutBtn.dataset.bound) {
                    checkoutBtn.addEventListener('click', (event) => {
                        if (!this.items.length) {
                            event.preventDefault();
                            showToast('Сначала добавьте книги в корзину');
                        }
                    });
                    checkoutBtn.dataset.bound = 'true';
                }
                checkoutBtn.classList.toggle('is-disabled', !this.items.length);
                checkoutBtn.setAttribute('aria-disabled', String(!this.items.length));
            }
        }
    };

    /* Подстановка заглушек/обложек в карточки товаров */
    function initProductLinks() {
        document.querySelectorAll('.product-card[data-product-id]').forEach((card) => {
            const product = getProductData(card);
            const targetUrl = buildBookPageUrl(product.id);

            card.dataset.productLink = targetUrl;
            card.setAttribute('role', 'link');
            card.setAttribute('tabindex', '0');

            if (!card.dataset.navigationBound) {
                card.addEventListener('click', function (event) {
                    if (event.target.closest('button, a')) {
                        return;
                    }
                    window.location.href = targetUrl;
                });

                card.addEventListener('keydown', function (event) {
                    if (event.key !== 'Enter' && event.key !== ' ') {
                        return;
                    }
                    if (event.target.closest('button, a')) {
                        return;
                    }
                    event.preventDefault();
                    window.location.href = targetUrl;
                });

                card.dataset.navigationBound = 'true';
            }

            const imageHolder = card.querySelector('.product-card__image');
            if (imageHolder) {
                if (imageHolder.tagName === 'A') {
                    imageHolder.setAttribute('href', targetUrl);
                } else if (!imageHolder.dataset.navigationBound) {
                    imageHolder.setAttribute('role', 'link');
                    imageHolder.setAttribute('tabindex', '0');

                    imageHolder.addEventListener('click', function () {
                        window.location.href = targetUrl;
                    });

                    imageHolder.addEventListener('keydown', function (event) {
                        if (event.key !== 'Enter' && event.key !== ' ') {
                            return;
                        }
                        event.preventDefault();
                        window.location.href = targetUrl;
                    });

                    imageHolder.dataset.navigationBound = 'true';
                }
            }

            const title = card.querySelector('.product-card__title');
            if (title && !title.querySelector('a')) {
                title.innerHTML = `<a href="${escapeHtml(targetUrl)}">${escapeHtml(product.name)}</a>`;
            } else if (title) {
                const titleLink = title.querySelector('a');
                if (titleLink) {
                    titleLink.setAttribute('href', targetUrl);
                    titleLink.textContent = product.name;
                }
            }
        });
    }

    function initBookPage() {
        const bookPage = document.querySelector('.book-page');
        const bookCard = document.querySelector('.book-card[data-product-id]');
        if (!bookPage || !bookCard) {
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const requestedId = params.get('id') || bookCard.dataset.productId;
        const product = enrichProductData(getCatalogProductById(requestedId) || getProductData(bookCard));
        if (!product) {
            return;
        }

        bookCard.dataset.productId = product.id;
        bookCard.dataset.productTitle = product.name;
        bookCard.dataset.productAuthor = product.author;
        bookCard.dataset.productPrice = String(product.price);
        bookCard.dataset.productCategory = product.category;
        bookCard.dataset.productCategorySlug = product.categorySlug;
        bookCard.dataset.productDescription = product.description;
        bookCard.dataset.productLink = buildBookPageUrl(product.id);
        bookCard.dataset.productImage = product.images.front || product.image || '';
        bookCard.dataset.productAvailable = product.available ? 'true' : 'false';
        bookCard.dataset.productStatus = product.statusText;

        const currentTitle = document.querySelector('.breadcrumbs__current');
        if (currentTitle) {
            currentTitle.textContent = product.name;
        }

        const categoryLink = document.querySelector('.breadcrumbs__link[href*="category="]');
        if (categoryLink) {
            categoryLink.textContent = product.category;
            categoryLink.setAttribute('href', `catalog.php?category=${encodeURIComponent(product.categorySlug)}`);
        }

        const badge = document.querySelector('.book-card__badge');
        if (badge) {
            badge.textContent = product.badge;
        }

        const title = document.querySelector('.book-card__title');
        if (title) {
            title.textContent = product.name;
        }

        const author = document.querySelector('.book-card__author');
        if (author) {
            author.textContent = product.author;
        }

        const price = document.querySelector('.book-card__price');
        if (price) {
            price.textContent = formatPrice(product.price);
        }

        const stock = document.querySelector('.book-card__stock');
        if (stock) {
            stock.textContent = product.statusText;
            stock.classList.toggle('book-card__stock--out', !product.available);
        }

        const description = document.querySelector('.book-card__description');
        if (description) {
            description.innerHTML = product.fullDescription
                .map((paragraph) => `<p>${escapeHtml(paragraph)}</p>`)
                .join('');
        }

        const specs = document.querySelector('.book-specs');
        if (specs) {
            specs.innerHTML = product.specs
                .map((item) => `
                    <div class="book-spec">
                        <p class="book-spec__label">${escapeHtml(item.label)}</p>
                        <p class="book-spec__value">${escapeHtml(item.value)}</p>
                    </div>
                `)
                .join('');
        }

        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.dataset.fallbackTitle = product.name;
        }

        const thumbs = document.querySelector('.book-gallery__thumbs');
        if (thumbs) {
            const views = [
                { key: 'front', label: 'Спереди', tone: '#d9c3aa', description: 'Вид спереди' },
                { key: 'back', label: 'Сзади', tone: '#cfbea6', description: 'Вид сзади' },
                { key: 'spread', label: 'Разворот', tone: '#d8c9b7', description: 'Открытая книга' }
            ];

            thumbs.innerHTML = views.map((view, index) => `
                <button
                    class="product__thumb${index === 0 ? ' active' : ''}"
                    data-image="${escapeHtml(product.images[view.key] || '')}"
                    data-label="${escapeHtml(view.description)}"
                    data-tone="${escapeHtml(view.tone)}"
                    data-view="${escapeHtml(view.key)}"
                    type="button"
                >${escapeHtml(view.label)}</button>
            `).join('');
        }

        document.title = `${product.name} - BookMarket`;
        const metaDescription = document.querySelector('meta[name="description"]');
        if (metaDescription) {
            metaDescription.setAttribute('content', `${product.name} - ${product.description}`);
        }
    }

    function initStaticCoverPlaceholders() {
        document.querySelectorAll('.product-card').forEach((card) => {
            const imageHolder = card.querySelector('.product-card__image');
            if (!imageHolder) {
                return;
            }

            const product = getProductData(card);
            imageHolder.innerHTML = createCoverMarkup(product.name, 'product-card__cover', product.image);
            if (imageHolder.tagName === 'A') {
                imageHolder.setAttribute('aria-label', `Открыть страницу книги ${product.name}`);
            }
        });
    }

    /* Инициализация кнопок "Добавить в корзину" */
    function initAddToCartButtons() {
        document.querySelectorAll('.add-to-cart').forEach((button) => {
            if (button.dataset.addToCartBound) {
                return;
            }

            button.addEventListener('click', function () {
                const product = getProductData(getProductContainer(this));
                tryAddProductToCart(product);
            });

            button.dataset.addToCartBound = 'true';
        });
    }

    /* Инициализация модального окна быстрого просмотра */
    function initQuickViewModal() {
        const modal = document.getElementById('quickViewModal');
        if (!modal) {
            return;
        }

        const modalBody = modal.querySelector('.modal__body');
        const closeBtn = modal.querySelector('.modal__close');

        function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
        }

        function openModal(product) {
            modalBody.innerHTML = `
                <article class="quick-view">
                    ${createCoverMarkup(product.name, 'quick-view__cover', product.image)}
                    <div class="quick-view__content">
                        <span class="quick-view__category">${escapeHtml(product.category)}</span>
                        <h3 class="quick-view__title">${escapeHtml(product.name)}</h3>
                        <p class="quick-view__author">${escapeHtml(product.author)}</p>
                        <p class="quick-view__price">${formatPrice(product.price)}</p>
                        <p class="quick-view__status${product.available ? '' : ' quick-view__status--out'}">${escapeHtml(product.statusText)}</p>
                        <p class="quick-view__description">${escapeHtml(product.description)}</p>
                        <button
                            type="button"
                            class="btn btn--accent quick-view__button js-modal-add-to-cart"
                            data-product-id="${escapeHtml(product.id)}"
                            data-product-title="${escapeHtml(product.name)}"
                            data-product-author="${escapeHtml(product.author)}"
                            data-product-price="${product.price}"
                            data-product-category="${escapeHtml(product.category)}"
                            data-product-description="${escapeHtml(product.description)}"
                            data-product-link="${escapeHtml(product.link || 'catalog.php')}"
                            data-product-image="${escapeHtml(product.image || '')}"
                            data-product-available="${product.available ? 'true' : 'false'}"
                            data-product-status="${escapeHtml(product.statusText)}"
                        >
                            Добавить в корзину
                        </button>
                    </div>
                </article>
            `;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
        }

        document.querySelectorAll('.open-modal').forEach((button) => {
            button.addEventListener('click', function () {
                const product = getProductData(getProductContainer(this));
                if (product) {
                    openModal(product);
                }
            });
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        modalBody.addEventListener('click', function (event) {
            const button = event.target.closest('.js-modal-add-to-cart');
            if (!button) {
                return;
            }

            const product = {
                id: button.dataset.productId,
                name: button.dataset.productTitle,
                author: button.dataset.productAuthor,
                price: Number(button.dataset.productPrice),
                quantity: 1,
                category: button.dataset.productCategory,
                description: button.dataset.productDescription,
                link: button.dataset.productLink,
                image: button.dataset.productImage,
                available: button.dataset.productAvailable === 'true',
                statusText: button.dataset.productStatus || 'В наличии'
            };

            const added = tryAddProductToCart(product);
            if (added) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('show')) {
                closeModal();
            }
        });
    }

    /* Очистка старых сообщений об ошибках формы */
    function clearErrors(form) {
        form.querySelectorAll('.error-message').forEach((el) => el.remove());
        form.querySelectorAll('.input-error').forEach((el) => {
            el.classList.remove('input-error');
            el.removeAttribute('aria-invalid');
            el.removeAttribute('aria-describedby');
        });
    }

    /* Простая проверка email */
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /* Простая проверка телефона по количеству цифр */
    function isValidPhone(phone) {
        const digits = String(phone || '').replace(/\D/g, '');
        return digits.length >= 10;
    }

    /* Отображение ошибки валидации рядом с полем */
    function showError(input, message) {
        const group = input.closest('.contact-form__group, .checkout-form__group');
        const error = document.createElement('div');
        const errorId = `${input.id || 'field'}-error`;
        error.className = 'error-message';
        error.id = errorId;
        error.textContent = message;
        input.classList.add('input-error');
        input.setAttribute('aria-invalid', 'true');
        input.setAttribute('aria-describedby', errorId);

        if (group) {
            group.appendChild(error);
        } else {
            input.insertAdjacentElement('afterend', error);
        }
    }


    /* Логика галереи книги и переключения миниатюр */
    function initGallery() {
        const mainImage = document.getElementById('mainImage');
        const thumbs = document.querySelectorAll('.product__thumb');
        const titleElement = document.querySelector('.book-card__title');
        const bookTitle = (titleElement ? titleElement.textContent.trim() : mainImage?.dataset.fallbackTitle) || 'BookMarket';

        if (!mainImage || !thumbs.length) {
            return;
        }

        function applyThumb(thumb) {
            const view = thumb.dataset.view || 'front';
            const tone = thumb.dataset.tone || '#d9c3aa';
            const label = thumb.dataset.label || thumb.textContent.trim();
            const fallbackSrc = createGalleryImageUrl(bookTitle, view, tone);
            const preferredSrc = thumb.dataset.image || fallbackSrc;

            mainImage.src = preferredSrc;
            mainImage.onerror = function () {
                this.onerror = null;
                this.src = fallbackSrc;
            };
            mainImage.alt = `${bookTitle}: ${label}`;
            thumbs.forEach((item) => item.classList.remove('active'));
            thumb.classList.add('active');
        }

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', function () {
                applyThumb(this);
            });
        });

        applyThumb(document.querySelector('.product__thumb.active') || thumbs[0]);
    }

    /* Кнопка "Купить сейчас": добавить в корзину и перейти к checkout */
    function initBuyNowButtons() {
        document.querySelectorAll('.book-card__actions .btn--secondary').forEach((button) => {
            button.addEventListener('click', function () {
                const product = getProductData(getProductContainer(this));
                if (!product) {
                    return;
                }

                if (!product.available) {
                    showToast(`Сейчас нельзя оформить заказ: «${product.name}» нет в наличии`);
                    return;
                }

                const added = tryAddProductToCart(product);
                if (added) {
                    window.location.href = 'checkout.php';
                }
            });
        });
    }

    /* Фильтрация и сортировка каталога на клиенте */
    function initCatalogControls() {
        const form = document.querySelector('.catalog-filter-form');
        const catalogProducts = document.querySelector('.catalog-products');

        if (!form || !catalogProducts) {
            return;
        }

        const title = document.querySelector('.catalog-toolbar__title');
        const text = document.querySelector('.catalog-toolbar__text');
        const sortButtons = Array.from(document.querySelectorAll('.sort-btn[data-sort]'));
        const params = new URLSearchParams(window.location.search);

        if (params.has('price') && !params.has('price-to')) {
            params.set('price-to', params.get('price'));
            params.delete('price');
            updateUrlFromParams(params);
        }

        let sortField = form.querySelector('input[name="sort"]');

        if (!sortField) {
            sortField = document.createElement('input');
            sortField.type = 'hidden';
            sortField.name = 'sort';
            form.appendChild(sortField);
        }

        sortField.value = params.get('sort') || 'price-asc';

        Array.from(form.elements).forEach((field) => {
            if (!field.name || field.type === 'submit' || field.type === 'reset' || field.name === 'sort') {
                return;
            }

            if (params.has(field.name)) {
                field.value = params.get(field.name);
            }
        });

        let emptyState = document.querySelector('.catalog-empty');

        if (!emptyState) {
            emptyState = document.createElement('div');
            emptyState.className = 'catalog-empty';
            emptyState.hidden = true;
            emptyState.innerHTML = `
            <h3 class="catalog-empty__title">По заданным параметрам книги не найдены</h3>
            <p class="catalog-empty__text">Попробуйте убрать часть фильтров или выполните новый поиск по каталогу.</p>
            <a class="btn btn--secondary" href="catalog.php">Сбросить фильтры</a>
        `;
            catalogProducts.after(emptyState);
        }

        function getCards() {
            return Array.from(catalogProducts.querySelectorAll('.product-card'));
        }

        function getNumberFieldValue(name) {
            const value = Number(form.querySelector(`[name="${name}"]`)?.value || 0);
            return Number.isFinite(value) ? value : 0;
        }

        function collectFilters() {
            return {
                query: (form.querySelector('[name="query"]')?.value || '').trim().toLowerCase(),
                category: (form.querySelector('[name="category"]')?.value || '').trim(),
                author: (form.querySelector('[name="author"]')?.value || '').trim().toLowerCase(),
                priceFrom: getNumberFieldValue('price-from'),
                priceTo: getNumberFieldValue('price-to'),
                sort: sortField.value || 'price-asc'
            };
        }

        function matchesFilters(card, filters) {
            const product = getProductData(card);

            if (!product) {
                return false;
            }

            const productTitle = product.name.toLowerCase();
            const productAuthor = product.author.toLowerCase();
            const productCategorySlug = card.dataset.productCategorySlug || product.categorySlug || slugify(product.category);

            if (filters.query && !productTitle.includes(filters.query)) {
                return false;
            }

            if (filters.category && productCategorySlug !== filters.category) {
                return false;
            }

            if (filters.author && !productAuthor.includes(filters.author)) {
                return false;
            }

            if (filters.priceFrom && product.price < filters.priceFrom) {
                return false;
            }

            if (filters.priceTo && product.price > filters.priceTo) {
                return false;
            }

            return true;
        }

        function updateToolbar(filters, count) {
            if (title) {
                title.textContent = `Найдено ${count} ${pluralize(count, 'книга', 'книги', 'книг')}`;
            }

            if (!text) {
                return;
            }

            const parts = [];

            if (filters.query) {
                parts.push(`название «${filters.query}»`);
            }

            if (filters.category) {
                const categoryOption = Array.from(form.querySelectorAll('[name="category"] option'))
                    .find((option) => option.value === filters.category);

                if (categoryOption) {
                    parts.push(`категория «${categoryOption.textContent.trim()}»`);
                }
            }

            if (filters.author) {
                parts.push(`автор «${filters.author}»`);
            }

            if (filters.priceFrom || filters.priceTo) {
                const from = filters.priceFrom ? `от ${filters.priceFrom}` : '';
                const to = filters.priceTo ? `до ${filters.priceTo}` : '';
                parts.push(`цена ${[from, to].filter(Boolean).join(' ')}`.trim());
            }

            text.textContent = parts.length
                ? `Активные фильтры: ${parts.join(', ')}`
                : 'Фильтры можно комбинировать';
        }

        function updateSortButtons(activeSort) {
            sortButtons.forEach((button) => {
                const isActive = button.dataset.sort === activeSort;

                button.classList.toggle('sort-btn--active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });
        }

        function buildFilterParams(filters) {
            const nextParams = new URLSearchParams();

            if (filters.query) {
                nextParams.set('query', filters.query);
            }

            if (filters.category) {
                nextParams.set('category', filters.category);
            }

            if (filters.author) {
                nextParams.set('author', filters.author);
            }

            if (filters.priceFrom) {
                nextParams.set('price-from', String(filters.priceFrom));
            }

            if (filters.priceTo) {
                nextParams.set('price-to', String(filters.priceTo));
            }

            if (filters.sort && filters.sort !== 'price-asc') {
                nextParams.set('sort', filters.sort);
            }

            return nextParams;
        }

        function applyFilters(shouldUpdateUrl = false) {
            const filters = collectFilters();
            const cards = getCards();

            const visibleCards = cards
                .filter((card) => matchesFilters(card, filters))
                .sort((a, b) => {
                    const firstPrice = getProductData(a).price;
                    const secondPrice = getProductData(b).price;

                    return filters.sort === 'price-desc'
                        ? secondPrice - firstPrice
                        : firstPrice - secondPrice;
                });

            cards.forEach((card) => {
                card.hidden = true;
            });

            visibleCards.forEach((card) => {
                card.hidden = false;
                catalogProducts.appendChild(card);
            });

            emptyState.hidden = visibleCards.length > 0;

            updateToolbar(filters, visibleCards.length);
            updateSortButtons(filters.sort);

            if (shouldUpdateUrl) {
                updateUrlFromParams(buildFilterParams(filters));
            }
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            applyFilters(true);
        });

        sortButtons.forEach((button) => {
            button.addEventListener('click', function () {
                sortField.value = this.dataset.sort || 'price-asc';
                applyFilters(true);
            });
        });

        const clearLink = form.querySelector('.catalog-filter__clear');

        if (clearLink) {
            clearLink.addEventListener('click', function (event) {
                event.preventDefault();

                form.reset();
                sortField.value = 'price-asc';

                updateUrlFromParams(new URLSearchParams());
                applyFilters(false);
            });
        }

        applyFilters(false);
    }

    /* Перерисовка сводки заказа на checkout-странице */
    function renderCheckoutSummary() {
        const itemsContainer = document.getElementById('checkout-summary-items');
        if (!itemsContainer) {
            return;
        }

        const subtotalEl = document.getElementById('checkout-subtotal');
        const discountEl = document.getElementById('checkout-discount');
        const totalEl = document.getElementById('checkout-total');
        const submitBtn = document.getElementById('checkout-submit');

        itemsContainer.querySelectorAll('.checkout-summary__row--item, .checkout-summary__empty').forEach((el) => el.remove());

        if (!cart.items.length) {
            const empty = document.createElement('div');
            empty.className = 'checkout-summary__empty';
            empty.textContent = 'В корзине пока нет товаров. Вернитесь в каталог и добавьте книги перед оформлением заказа.';
            itemsContainer.prepend(empty);
        } else {
            const fragment = document.createDocumentFragment();
            cart.items.forEach((item) => {
                const row = document.createElement('div');
                row.className = 'checkout-summary__row checkout-summary__row--item';
                row.innerHTML = `<span>${escapeHtml(item.name)} × ${item.quantity}</span><span>${formatPrice(item.price * item.quantity)}</span>`;
                fragment.appendChild(row);
            });
            itemsContainer.prepend(fragment);
        }

        if (subtotalEl) {
            subtotalEl.textContent = formatPrice(cart.getSubtotal());
        }
        if (discountEl) {
            discountEl.textContent = formatPrice(cart.getDiscount());
        }
        if (totalEl) {
            totalEl.textContent = formatPrice(cart.getTotalSum());
        }
        if (submitBtn) {
            submitBtn.classList.toggle('is-disabled', !cart.items.length);
            submitBtn.setAttribute('aria-disabled', String(!cart.items.length));
        }
    }

    /* Логика страницы оформления заказа */
    function initCheckoutPage() {
        const form = document.getElementById('checkout-form');

        if (!form) {
            return;
        }

        renderCheckoutSummary();

        form.addEventListener('submit', function (event) {
            const cartInput = document.getElementById('checkout-cart-data');

            if (cartInput) {
                cartInput.value = JSON.stringify(cart.items);
            }

            if (!cart.items.length) {
                event.preventDefault();
                showToast('Сначала добавьте книги в корзину');
            }
        });
    }

    /* Синхронизация состояния корзины между вкладками браузера */
    window.addEventListener('storage', function (event) {
        if (event.key === STORAGE_KEY) {
            cart.load();
            cart.updateCounter();
            cart.renderCart();
            renderCheckoutSummary();
        }
    });

    /* Точка входа: инициализация приложения после загрузки DOM */
    document.addEventListener('DOMContentLoaded', function () {
        cart.load();
        cart.updateCounter();

        renderProductCards();

        initProductLinks();
        initBookPage();
        initStaticCoverPlaceholders();
        cart.renderCart();
        initAddToCartButtons();
        initAddToCartCounters();
        updateAddToCartControls();
        initQuickViewModal();
        initGallery();
        initBuyNowButtons();
        initCatalogControls();
        initCheckoutPage();
    });
})();
