import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { shopPriceRanges } from '@/data/shop/catalog';
import type {
    ShopCatalogProduct,
    ShopCategoryFilter,
    ShopFilters,
    ShopPaginationMeta,
    ShopPriceRange,
    ShopSortOption,
} from '@/types/shop';

// ─── Module-level singletons (shared across all components) ───────────────────

const isFilterDrawerOpen = ref(false);
const searchInput = ref('');
let searchTimer: ReturnType<typeof setTimeout> | undefined;
let searchInitialized = false;

const DEFAULT_FILTERS: ShopFilters = {
    categories: [],
    price: 'all',
    inStock: false,
    sort: 'newest',
    search: '',
    page: 1,
};

function buildQueryParams(filters: ShopFilters): Record<string, unknown> {
    const params: Record<string, unknown> = {};

    if (filters.categories.length > 0) {
        params.categories = filters.categories;
    }

    if (filters.price !== 'all') {
        params.price = filters.price;
    }

    if (filters.inStock) {
        params.in_stock = '1';
    }

    if (filters.sort !== 'newest') {
        params.sort = filters.sort;
    }

    if (filters.search.trim() !== '') {
        params.search = filters.search.trim();
    }

    if (filters.page > 1) {
        params.page = filters.page;
    }

    return params;
}

// ─── Composable ───────────────────────────────────────────────────────────────

export function useShopCatalog() {
    const inertiaPage = usePage();

    // ── Server props ────────────────────────────────────────────────────────

    const products = computed(
        () =>
            (inertiaPage.props.products as ShopCatalogProduct[] | undefined) ??
            [],
    );

    const categories = computed(
        () =>
            (inertiaPage.props.categories as
                | ShopCategoryFilter[]
                | undefined) ?? [],
    );

    const meta = computed(
        (): ShopPaginationMeta =>
            (inertiaPage.props.meta as ShopPaginationMeta | undefined) ?? {
                total: 0,
                perPage: 8,
                currentPage: 1,
                lastPage: 1,
            },
    );

    const serverFilters = computed(
        (): ShopFilters =>
            (inertiaPage.props.filters as ShopFilters | undefined) ??
            DEFAULT_FILTERS,
    );

    // Initialise search input from server on first composable access
    if (!searchInitialized && inertiaPage.props.filters) {
        searchInitialized = true;
        const sf = inertiaPage.props.filters as ShopFilters;
        searchInput.value = sf.search ?? '';
    }

    // ── Derived state ───────────────────────────────────────────────────────

    const selectedCategories = computed(() => serverFilters.value.categories);
    const priceRange = computed(
        () => serverFilters.value.price as ShopPriceRange,
    );
    const inStockOnly = computed(() => serverFilters.value.inStock);
    const sort = computed(() => serverFilters.value.sort as ShopSortOption);
    const page = computed(() => meta.value.currentPage);
    const totalPages = computed(() => meta.value.lastPage);
    const total = computed(() => meta.value.total);
    const hasActiveFilters = computed(
        () =>
            serverFilters.value.categories.length > 0 ||
            serverFilters.value.price !== 'all' ||
            serverFilters.value.inStock ||
            serverFilters.value.search.trim() !== '',
    );

    // ── Actions ─────────────────────────────────────────────────────────────

    function applyFilters(overrides: Partial<ShopFilters>): void {
        const next: ShopFilters = { ...serverFilters.value, ...overrides };

        router.get('/shop', buildQueryParams(next), {
            preserveScroll: true,
            replace: true,
            only: ['products', 'meta', 'filters'],
        });
    }

    function toggleCategory(slug: string): void {
        const current = [...serverFilters.value.categories];
        const idx = current.indexOf(slug);

        if (idx === -1) {
            current.push(slug);
        } else {
            current.splice(idx, 1);
        }

        applyFilters({ categories: current, page: 1 });
    }

    function setPriceRange(value: ShopPriceRange): void {
        applyFilters({ price: value, page: 1 });
    }

    function setInStockOnly(value: boolean): void {
        applyFilters({ inStock: value, page: 1 });
    }

    function setSort(value: ShopSortOption): void {
        applyFilters({ sort: value, page: 1 });
    }

    function setSearch(value: string): void {
        searchInput.value = value;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            applyFilters({ search: value.trim(), page: 1 });
        }, 400);
    }

    function setPage(value: number): void {
        applyFilters({ page: value });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function clearFilters(): void {
        searchInput.value = '';
        clearTimeout(searchTimer);
        applyFilters(DEFAULT_FILTERS);
    }

    function openFilterDrawer(): void {
        isFilterDrawerOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeFilterDrawer(): void {
        isFilterDrawerOpen.value = false;
        document.body.style.overflow = '';
    }

    const activeFilters = computed(() => {
        const sf = serverFilters.value;
        const chips: Array<{ label: string; clear: () => void }> = [];

        sf.categories.forEach((slug) => {
            const cat = categories.value.find((c) => c.slug === slug);
            chips.push({
                label: cat?.name ?? slug,
                clear: () =>
                    applyFilters({
                        categories: sf.categories.filter((s) => s !== slug),
                        page: 1,
                    }),
            });
        });

        if (sf.price !== 'all') {
            const label =
                shopPriceRanges.find((r) => r.value === sf.price)?.label ??
                sf.price;
            chips.push({
                label,
                clear: () => applyFilters({ price: 'all', page: 1 }),
            });
        }

        if (sf.inStock) {
            chips.push({
                label: 'In Stock',
                clear: () => applyFilters({ inStock: false, page: 1 }),
            });
        }

        if (sf.search.trim() !== '') {
            chips.push({
                label: `"${sf.search.trim()}"`,
                clear: () => {
                    searchInput.value = '';
                    applyFilters({ search: '', page: 1 });
                },
            });
        }

        return chips;
    });

    function removeFilter(index: number): void {
        activeFilters.value[index]?.clear();
    }

    return {
        // Server data
        products,
        categories,
        meta,
        total,
        // Filter state (from server)
        selectedCategories,
        priceRange,
        inStockOnly,
        sort,
        // Search (local ref for responsive input)
        search: searchInput,
        // Pagination (from server meta)
        page,
        totalPages,
        // UI state
        isFilterDrawerOpen,
        hasActiveFilters,
        activeFilters,
        // Actions
        toggleCategory,
        setPriceRange,
        setInStockOnly,
        setSort,
        setSearch,
        setPage,
        clearFilters,
        openFilterDrawer,
        closeFilterDrawer,
        removeFilter,
        applyFilters,
    };
}
