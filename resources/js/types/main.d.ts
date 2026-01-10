export interface Provider {
    id: number;
    name: string;
    email?: string;
    phone?: string;
    address?: string;
    contact_person?: string;
};

export interface Category {
    id: number;
    name: string;
    description?: string;
}

export interface MeasureUnit {
    id: number;
    name: string;
    abbreviation: string;
}

export interface Product {
    id: number;
    name: string;
    sold_by_retail: boolean;
    retail_units_per_box?: number;
    stock?: number;
    retail_remaining_stock?: number;
    cost_price?: number;
    first_wholesale_percentage?: number;
    second_wholesale_percentage?: number;
    third_wholesale_percentage?: number;
    retail_percentage?: number;
    first_wholesale_price?: number;
    second_wholesale_price?: number;
    third_wholesale_price?: number;
    retail_price?: number;
    category?: Category;
    measure_unit?: MeasureUnit;
    retail_measure_unit?: MeasureUnit;
    current_price_modification?: PriceModification
}

export interface PriceModification {
    id: number;
    is_current: boolean;
    sold_by_retail: boolean;
    retail_units_per_box: number;
    remaining_stock: number;
    retail_remaining_stock: number;
    cost_price: number;
    first_wholesale_percentage: number;
    second_wholesale_percentage: number;
    third_wholesale_percentage: number;
    retail_percentage: number;
}

export interface PurchaseItem {
    id: number;
    quantity: number;
    product: Product;
    price_modification: PriceModification;
}

export interface Purchase {
    id: number;
    total: number;
    created_by: string;
    provider: Provider;
    purchase_items: PurchaseItem[];
    created_at: string;
}

export interface SaleItem {
    id: number;
    quantity: number;
    is_retail_sale: boolean;
    selected_percentage: 'first_wholesome_percentage' | 'second_wholesome_percentage' | 'third_wholesome_percentage' | 'retail_percentage';
    total: number;
    product: Product;
    price_modification: PriceModification;
}

export interface FormSaleItem {
    quantity?: number;
    is_retail_sale?: boolean;
    selected_percentage?: 'first_wholesome_percentage' | 'second_wholesome_percentage' | 'third_wholesome_percentage' | 'retail_percentage';
    total?: number;
    product_id?: number;
    price_modification_id?: number;
}

export interface Sale {
    id: number;
    client: string;
    total: number;
    paid_amount: number;
    change_amount: number;
    created_by: string;
    created_at: string;
}
