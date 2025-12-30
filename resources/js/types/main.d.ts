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
    category?: Category;
    measure_unit?: MeasureUnit;
    retail_measure_unit?: MeasureUnit;
}

export interface MeasureUnit {
    id: number;
    name: string;
    abbreviation: string;
}