export type CategoryImageSource = 'url' | 'upload';

export type ImageSource = CategoryImageSource;

export type AdminCategoryOption = {
    id: number;
    name: string;
};

export type AdminCategory = {
    id: number;
    name: string;
    slug: string;
    image: string | null;
    image_source: CategoryImageSource;
    image_url: string;
    description: string | null;
    sort_order: number;
    is_active: boolean;
    products_count: number;
    created_at: string;
    updated_at: string;
};

export type CategoryFormData = {
    name: string;
    slug: string;
    image_source: CategoryImageSource;
    image: string;
    image_file: File | null;
    description: string;
    sort_order: number;
    is_active: boolean;
};

export type StockStatus = 'in_stock' | 'stock_out';

export type AdminProductListItem = {
    id: number;
    name: string;
    slug: string;
    category: AdminCategoryOption;
    price: number;
    compare_at_price: number | null;
    stock_status: StockStatus;
    is_active: boolean;
    is_best_seller: boolean;
    is_featured: boolean;
    sold_count: number;
    image: string | null;
};

export type AdminProductImagePayload = {
    id: number;
    source: ImageSource;
    image_path: string;
    preview: string | null;
    alt_text: string | null;
    is_primary: boolean;
    sort_order: number;
};

export type ProductImageFormItem = {
    id: number | null;
    client_key: string;
    source: ImageSource;
    image_path: string;
    preview_url: string | null;
    alt_text: string;
    is_primary: boolean;
    sort_order: number;
    file: File | null;
};

export type AdminProduct = AdminProductListItem & {
    category_id: number;
    short_description: string | null;
    description: string | null;
    images: AdminProductImagePayload[];
    created_at: string;
    updated_at: string;
};

export type ProductFormData = {
    category_id: number | '';
    name: string;
    slug: string;
    short_description: string;
    description: string;
    price: number | '';
    compare_at_price: number | '';
    stock_status: StockStatus;
    is_best_seller: boolean;
    is_featured: boolean;
    is_active: boolean;
    sold_count: number;
    images: ProductImageFormItem[];
};
