export interface Data {
    links: Link[];
    data: any[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
}

export interface RequestBody {
    size_page: number,
    search: string,
    link: string,
    page: number,
}

export interface Client {
    id: number | null,
    code: string,
    name: string,
    rfc: string,
    email: string,
    phone: string,
    address: string,
    country: string,
    state: string,
    city: string,
    currency_id: number | string,
    currency: Currency,
}

export interface Currency {
    id: number | null,
    name: string,
    symbol: string,
    code: string,
    exchange_rate: number | string,
}

export interface Link {
    name: string;
    href: string;
    icon: any;
}

export interface Warehouse {
    id: number | null,
    code: string,
    name: string,
    address: string,
    country: string,
    state: string,
    city: string,
}

export interface Tenant {
    id: number | null,
    name: string,
    phone: string,
    country: string,
    state: string,
    city: string,
    address: string,
    user: User,
}

export interface User {
    id: number | null,
    name: string,
    email: string,
    password: string,
    password_confirmation: string,
    role: string,
    tenant_id: number | string,
}

export const emptyUser: User = {
    id: 0,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
    tenant_id: "",
};

export const emptyTenant: Tenant = {
    id: 0,
    name: "",
    phone: "",
    address: "",
    country: "",
    state: "",
    city: "",
    user: emptyUser,
  };



