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

export type OrderStatus =
    | 'pending'
    | 'processing'
    | 'shipped'
    | 'delivered'
    | 'cancelled';

export type PaymentStatus = 'pending' | 'paid' | 'failed' | 'cancelled';

export type PaymentMethod = 'cod' | 'sslcommerz';

export type AdminStatusOption = {
    value: string;
    label: string;
};

export type AdminOrderListItem = {
    id: number;
    order_number: string;
    customer_name: string;
    phone: string;
    email: string;
    total: number;
    items_count: number;
    payment_method: PaymentMethod;
    payment_status: PaymentStatus;
    status: OrderStatus;
    placed_at: string | null;
    created_at: string;
};

export type AdminOrderItem = {
    id: number;
    product_id: number | null;
    product_name: string;
    unit_price: number;
    quantity: number;
    line_total: number;
};

export type AdminOrderStatusHistory = {
    id: number;
    status: OrderStatus;
    note: string | null;
    changed_by: {
        id: number;
        name: string;
    } | null;
    created_at: string;
};

export type AdminOrder = AdminOrderListItem & {
    district: string;
    area: string;
    address: string;
    notes: string | null;
    subtotal: number;
    delivery_charge: number;
    customer: {
        id: number;
        name: string;
        email: string;
    } | null;
    items: AdminOrderItem[];
    status_histories: AdminOrderStatusHistory[];
    updated_at: string;
};

export type AdminOrderFilters = {
    search: string;
    status: string;
    payment_status: string;
};

export type OrderUpdateFormData = {
    status: OrderStatus;
    payment_status: PaymentStatus;
    note: string;
};
