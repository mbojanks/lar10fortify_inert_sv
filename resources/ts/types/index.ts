import type { File } from "buffer";

export type User = {
    useraddress?: string;
    user_place_id?: string | number;
    id: number,
    name: string, // username
    email: string,
    phone?: string,
    first_name?: string,
    last_name?: string,
    full_name?: string,
    role: string,
    status: string,
    date_of_birth?: Date,
    timezone?: string,
    email_verified_at?: Date,
    created_at?: Date,
    updated_at?: Date,
    userimg?: string | FileList,
}

export type UserProfile = {
    useraddress?: string;
    user_place_id?: string | number;
    id?: number,
    name: string, // username
    email: string,
    phone?: string,
    first_name?: string,
    last_name?: string,
    full_name?: string,
    role?: string,
    status?: string,
    date_of_birth?: Date,
    timezone?: string,
    email_verified_at?: Date,
    created_at?: Date,
    updated_at?: Date,
    userimg?: string | FileList,
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export type ResetSystemAction = {
    text: string,
    action: string,
}

export type SystemConfiguration = {
    id: number,
    name: string,
    type: 'bool' | 'text' | 'number',
    value?: string | boolean | number,
    created_at: Date,
    updated_at: Date,
    default?: string,
    description?: string,
    hint?: string,
}

export type PaginationMeta = {
    current_page: number,
    from: 1,
    to: number,
    total: number,
    path: string,
    last_page: number,
    per_page: number,
}

export type TableHeader = {
    text: string
    align: 'left' | 'right' | 'center'
}

export type ContactMessage = {
    id?: number,
    name: string,
    subject: string,
    body: string,
    phone?: string,
    email?: string,
    last_read_at?: Date,
    last_read_at_w3c?: Date,
}


export type Testimonial = {
    id: number,
    user_id?: number,
    names: string,
    position?: string,
    company?: string,
    country?: string,
    content: string,
    is_approved: boolean,
    created_at: Date,
    updated_at?: Date,
}

export type LoginErrors = {
    email?: string,
    password?: string
}

export type ResetPasswordErrors = LoginErrors & {
    password_confirmation?: string
}

export type ConfirmPasswordErrors = {
    password?: string
}

export type RegisterErrors = {
    email?: string,
    password?: string,
    name?: string,
    phone?: string,
    useraddress?: string,
    user_place_id?: string,
    role_id?: string,
    userimg?: string
}
export type RegisterValues = {
    email?: string|undefined,
    password?: string,
    password_confirmation?: string,
    name?: string,
    phone?: string,
    useraddress?: string,
    user_place_id1?: number|{placeid: number},
    user_place_id?: number,
    role_id?: number,
    userimg?: FileList
}

export type UpdatePasswordErrors = {
    current_password?: string,
    password?: string,
    password_confirmation?: string,
}

export type Municipality = {
    municipalityid: number,
    municipality: string,
    country_id: number
}

export type Place = {
    placeid: number,
    place: string,
    placepostnum: string,
    municipality_id: number,
    municipality?: Municipality[]
}
