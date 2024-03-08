import { Category, EmptyCategory } from "./categories";

export interface Product {
    id: number;
    code: string;
    name: string;
    description: string;
    price: number;
    cost: number;
    image: string;
    category_id: number;
    category: Category;
}

export const EmptyProduct: Product = {
    id: 0,
    code: "",
    name: "",
    description: "",
    price: 0,
    cost: 0,
    image: "",
    category_id: 0,
    category: JSON.parse(JSON.stringify(EmptyCategory)),
}
