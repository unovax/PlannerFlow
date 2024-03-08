import { Product } from '@/types/products';

export interface Category {
    id: number;
    code: string;
    name: string;
    products: Product[];
}

export const EmptyCategory: Category = {
    id: 0,
    code: '',
    name: '',
    products: []
};