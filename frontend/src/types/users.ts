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

export interface Permission {
    id: number | null,
    name: string,
    description: string,
}

export interface Role {
    id: number | null,
    code: string,
    name: string,
    description: string,
    permissions: Permission[],
}

export interface User {
    id: number | null,
    name: string,
    email: string,
    password: string,
    password_confirmation: string,
    role: string,
    tenant_id: number | string,
    token: string,
    roles: Role[],
}

export const EmptyUser: User = {
    id: 0,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
    tenant_id: "",
    token: "",
    roles: [],
};

export const EmptyTenant: Tenant = {
    id: 0,
    name: "",
    phone: "",
    address: "",
    country: "",
    state: "",
    city: "",
    user: EmptyUser,
};

export const EmptyRole: Role = {
    id: 0,
    code: "",
    name: "",
    description: "",
    permissions: [],
};

