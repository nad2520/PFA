import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import vm from 'node:vm';

class FakeClassList {
    constructor(initial = '') {
        this.classes = new Set(String(initial).split(/\s+/).filter(Boolean));
    }

    add(name) {
        this.classes.add(name);
    }

    remove(name) {
        this.classes.delete(name);
    }

    contains(name) {
        return this.classes.has(name);
    }

    toggle(name, force) {
        if (force === undefined) {
            if (this.classes.has(name)) {
                this.classes.delete(name);
                return false;
            }
            this.classes.add(name);
            return true;
        }

        if (force) {
            this.classes.add(name);
            return true;
        }

        this.classes.delete(name);
        return false;
    }
}

class FakeElement {
    constructor(id = '') {
        this.id = id;
        this.style = { display: '' };
        this.listeners = new Map();
        this.dataset = {};
        this.attributes = {};
        this.value = '';
        this.textContent = '';
        this.classList = new FakeClassList();
        this.virtualButtons = [];
        this._innerHTML = '';
    }

    set innerHTML(value) {
        this._innerHTML = String(value);
        this.virtualButtons = [];

        const buttonRegex = /<button[^>]*data-filter="([^"]+)"[^>]*class="([^"]*)"[^>]*>(.*?)<\/button>/gms;
        let match;
        while ((match = buttonRegex.exec(this._innerHTML)) !== null) {
            const button = new FakeElement();
            button.dataset.filter = match[1];
            button.classList = new FakeClassList(match[2]);
            button.textContent = match[3];
            this.virtualButtons.push(button);
        }
    }

    get innerHTML() {
        return this._innerHTML;
    }

    addEventListener(type, callback) {
        const list = this.listeners.get(type) ?? [];
        list.push(callback);
        this.listeners.set(type, list);
    }

    dispatchEvent(event) {
        const type = typeof event === 'string' ? event : event.type;
        const payload = typeof event === 'string' ? { type, target: this } : { ...event, target: this };
        for (const callback of this.listeners.get(type) ?? []) {
            callback(payload);
        }
    }

    click() {
        this.dispatchEvent({ type: 'click' });
    }

    setAttribute(name, value) {
        this.attributes[name] = String(value);
    }

    getAttribute(name) {
        return this.attributes[name] ?? null;
    }

    querySelectorAll(selector) {
        if (selector === 'button') {
            return this.virtualButtons;
        }

        return [];
    }
}

class FakeDocument {
    constructor() {
        this.elements = new Map();
        this.listeners = new Map();
        this.documentElement = { style: {} };
    }

    register(id) {
        const element = new FakeElement(id);
        this.elements.set(id, element);
        return element;
    }

    getElementById(id) {
        return this.elements.get(id) ?? null;
    }

    addEventListener(type, callback) {
        const list = this.listeners.get(type) ?? [];
        list.push(callback);
        this.listeners.set(type, list);
    }

    dispatchEvent(event) {
        for (const callback of this.listeners.get(event.type) ?? []) {
            callback(event);
        }
    }

    querySelector() {
        return null;
    }
}

function sampleBooks() {
    const books = [];
    for (let i = 1; i <= 9; i++) {
        books.push({
            id: i,
            title: `Trend ${i}`,
            author: `Author ${i}`,
            publicationYear: 2020 + (i % 3),
            genre: i % 2 === 0 ? 'Fantasy' : 'Mystery',
            genres: [i % 2 === 0 ? 'Fantasy' : 'Mystery'],
            trending: true,
            description: '',
            audience: i === 9 ? 'User +18' : 'All',
            cover: '📘',
        });
    }

    books.push({
        id: 10,
        title: 'Alpha Fantasy',
        author: 'Filter Match',
        publicationYear: 2024,
        genre: 'Fantasy',
        genres: ['Fantasy', 'Adventure'],
        trending: true,
        description: '',
        audience: 'All',
        cover: '🐉',
    });

    books.push({
        id: 11,
        title: '<script>alert(1)</script>',
        author: 'Payload Author',
        publicationYear: 2025,
        genre: 'Fantasy',
        genres: ['Fantasy'],
        trending: true,
        description: '',
        audience: 'All',
        cover: '🧪',
    });

    books.push({
        id: 12,
        title: 'Quiet Drama',
        author: 'Side Shelf',
        publicationYear: 2018,
        genre: 'Drama',
        genres: ['Drama'],
        trending: false,
        description: '',
        audience: 'All',
        cover: '🎭',
    });

    return books;
}

function countRenderedCards(html) {
    return (html.match(/<div class="catalog-book-card"/g) ?? []).length;
}

function createHarness({ userRole = 'All' } = {}) {
    const document = new FakeDocument();
    const bookGrid = document.register('bookGrid');
    const noResults = document.register('noResults');
    const searchInput = document.register('bookSearch');
    const filterRow = document.register('filterRow');
    const exploreMore = document.register('exploreMore');

    const window = {
        __lexora: {
            genreCovers: { Fantasy: 'fantasy.png', Mystery: 'mystery.png', Drama: 'drama.png' },
            genreColors: {
                Fantasy: { css: 'background: teal; color: white' },
                Mystery: { css: 'background: maroon; color: white' },
                Drama: { css: 'background: gold; color: black' },
            },
            genres: ['Fantasy', 'Mystery', 'Drama'],
            communityPosts: [],
            books: sampleBooks(),
            bookPrices: Object.fromEntries(sampleBooks().map((book) => [book.id, { cost: 10, xpReward: 5, coinReward: 2 }])),
        },
        LX_USER_ROLE: userRole,
        LX_SESSION: { userCoins: 100, userLevel: 3 },
        location: { href: '' },
        history: { replaceState() {} },
        performance: { getEntriesByType() { return []; } },
        addEventListener() {},
        document,
    };

    const fetch = async () => ({
        ok: false,
        async json() {
            return {};
        },
    });

    const context = vm.createContext({
        window,
        document,
        fetch,
        console,
        setTimeout,
        clearTimeout,
        URLSearchParams,
        CustomEvent: class CustomEvent {
            constructor(type, init = {}) {
                this.type = type;
                this.detail = init.detail;
            }
        },
        performance: window.performance,
        history: window.history,
    });

    const sourcePath = path.resolve('public/assets/js/user_app.js');
    const source = fs.readFileSync(sourcePath, 'utf8');
    vm.runInContext(source, context, { filename: sourcePath });

    return {
        context,
        document,
        bookGrid,
        noResults,
        searchInput,
        filterRow,
        exploreMore,
    };
}

async function flushAsyncCatalogBoot() {
    await new Promise((resolve) => setImmediate(resolve));
    await new Promise((resolve) => setImmediate(resolve));
}

test('catalog filtering paginates trending books and expands with Explore More', async () => {
    const harness = createHarness();

    harness.context.initCatalog();
    await flushAsyncCatalogBoot();

    assert.equal(countRenderedCards(harness.bookGrid.innerHTML), 8);
    assert.equal(harness.exploreMore.style.display, '');

    harness.exploreMore.click();

    assert.equal(countRenderedCards(harness.bookGrid.innerHTML), 11);
    assert.equal(harness.exploreMore.style.display, 'none');
});

test('catalog filtering combines genre and search filters and resets when the search is cleared', async () => {
    const harness = createHarness();

    harness.context.initCatalog();
    await flushAsyncCatalogBoot();

    const fantasyButton = harness.filterRow.querySelectorAll('button').find((button) => button.dataset.filter === 'Fantasy');
    fantasyButton.click();

    assert.match(harness.bookGrid.innerHTML, /Alpha Fantasy/);
    assert.ok(countRenderedCards(harness.bookGrid.innerHTML) >= 1);

    harness.searchInput.value = 'alpha';
    harness.searchInput.dispatchEvent({ type: 'input' });

    assert.equal(countRenderedCards(harness.bookGrid.innerHTML), 1);
    assert.match(harness.bookGrid.innerHTML, /Alpha Fantasy/);

    harness.searchInput.value = '';
    harness.searchInput.dispatchEvent({ type: 'input' });

    assert.ok(countRenderedCards(harness.bookGrid.innerHTML) > 1);
    assert.equal(harness.noResults.style.display, 'none');
});

test('catalog filtering hides adult-only results for underage users and shows an empty-result state', async () => {
    const harness = createHarness({ userRole: 'User -18' });

    harness.context.initCatalog();
    await flushAsyncCatalogBoot();

    assert.doesNotMatch(harness.bookGrid.innerHTML, /Trend 9/);

    harness.searchInput.value = 'not-a-real-book';
    harness.searchInput.dispatchEvent({ type: 'input' });

    assert.equal(countRenderedCards(harness.bookGrid.innerHTML), 0);
    assert.equal(harness.noResults.style.display, 'block');
    assert.equal(harness.exploreMore.style.display, 'none');
});

test('catalog rendering escapes malicious titles in filtered results', async () => {
    const harness = createHarness();

    harness.context.initCatalog();
    await flushAsyncCatalogBoot();

    const fantasyButton = harness.filterRow.querySelectorAll('button').find((button) => button.dataset.filter === 'Fantasy');
    fantasyButton.click();
    harness.searchInput.value = 'script';
    harness.searchInput.dispatchEvent({ type: 'input' });

    assert.equal(countRenderedCards(harness.bookGrid.innerHTML), 1);
    assert.match(harness.bookGrid.innerHTML, /&lt;script&gt;alert\(1\)&lt;\/script&gt;/);
    assert.doesNotMatch(harness.bookGrid.innerHTML, /<script>alert\(1\)<\/script>/);
});
