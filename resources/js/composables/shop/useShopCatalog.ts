import { computed, ref } from 'vue';
import { catalogProducts, shopPriceRanges } from '@/data/shop/catalog';
import type {
    ShopCatalogProduct,
    ShopPriceRange,
    ShopSortOption,
} from '@/types/shop';

const PAGE_SIZE = 8;

const category = ref<string>('all');
const priceRange = ref<ShopPriceRange>('all');
const inStockOnly = ref(false);
const sort = ref<ShopSortOption>('newest');
const search = ref('');
const page = ref(1);
const isFilterDrawerOpen = ref(false);

function inPriceRange(price: number, range: ShopPriceRange): boolean {
    if (range === 'all') {
        return true;
    }

    const [min, max] = range.split('-');

    if (price < Number(min)) {
        return false;
    }

    if (max !== '' && price > Number(max)) {
        return false;
    }

    return true;
}

function sortProducts(list: ShopCatalogProduct[]): ShopCatalogProduct[] {
    const sorted = [...list];

    switch (sort.value) {
        case 'price-asc':
            sorted.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            sorted.sort((a, b) => b.price - a.price);
            break;
        case 'best':
            sorted.sort((a, b) => b.sold - a.sold);
            break;
        default:
            sorted.sort((a, b) => b.id - a.id);
    }

    return sorted;
}

export function useShopCatalog() {
    const filteredProducts = computed(() => {
        const query = search.value.trim().toLowerCase();

        return sortProducts(
            catalogProducts.filter(
                (product) =>
                    (category.value === 'all' ||
                        product.category === category.value) &&
                    inPriceRange(product.price, priceRange.value) &&
                    (!inStockOnly.value || product.inStock) &&
                    (query === '' ||
                        product.name.toLowerCase().includes(query)),
            ),
        );
    });

    const totalPages = computed(() =>
        Math.max(1, Math.ceil(filteredProducts.value.length / PAGE_SIZE)),
    );

    const paginatedProducts = computed(() => {
        const currentPage = Math.min(page.value, totalPages.value);
        const start = (currentPage - 1) * PAGE_SIZE;

        return filteredProducts.value.slice(start, start + PAGE_SIZE);
    });

    const activeFilters = computed(() => {
        const filters: Array<{ label: string; clear: () => void }> = [];

        if (category.value !== 'all') {
            filters.push({
                label: category.value,
                clear: () => {
                    category.value = 'all';
                },
            });
        }

        if (priceRange.value !== 'all') {
            const priceLabel =
                shopPriceRanges.find((r) => r.value === priceRange.value)
                    ?.label ?? priceRange.value;

            filters.push({
                label: priceLabel,
                clear: () => {
                    priceRange.value = 'all';
                },
            });
        }

        if (inStockOnly.value) {
            filters.push({
                label: 'In Stock',
                clear: () => {
                    inStockOnly.value = false;
                },
            });
        }

        if (search.value.trim()) {
            filters.push({
                label: `“${search.value.trim()}”`,
                clear: () => {
                    search.value = '';
                },
            });
        }

        return filters;
    });

    function resetPage(): void {
        page.value = 1;
    }

    function setCategory(value: string): void {
        category.value = value;
        resetPage();
    }

    function setPriceRange(value: ShopPriceRange): void {
        priceRange.value = value;
        resetPage();
    }

    function setInStockOnly(value: boolean): void {
        inStockOnly.value = value;
        resetPage();
    }

    function setSort(value: ShopSortOption): void {
        sort.value = value;
        resetPage();
    }

    function setSearch(value: string): void {
        search.value = value;
        resetPage();
    }

    function setPage(value: number): void {
        page.value = value;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function clearFilters(): void {
        category.value = 'all';
        priceRange.value = 'all';
        inStockOnly.value = false;
        search.value = '';
        resetPage();
    }

    function openFilterDrawer(): void {
        isFilterDrawerOpen.value = true;
        document.body.style.overflow = 'hidden';
    }

    function closeFilterDrawer(): void {
        isFilterDrawerOpen.value = false;
        document.body.style.overflow = '';
    }

    function removeFilter(index: number): void {
        activeFilters.value[index]?.clear();
        resetPage();
    }

    return {
        category,
        priceRange,
        inStockOnly,
        sort,
        search,
        page,
        isFilterDrawerOpen,
        filteredProducts,
        paginatedProducts,
        totalPages,
        activeFilters,
        setCategory,
        setPriceRange,
        setInStockOnly,
        setSort,
        setSearch,
        setPage,
        clearFilters,
        openFilterDrawer,
        closeFilterDrawer,
        removeFilter,
    };
}
