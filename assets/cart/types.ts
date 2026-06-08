export type CartProduct = {
    id: string;
    name: string;
    priceCents: number;
    image: string;
    url: string;
};

export type CartItem = CartProduct & {
    quantity: number;
    lineTotalCents: number;
};

export type CartNotification = {
    id: number;
    message: string;
};

export type CartResponse = {
    items: CartItem[];
    count: number;
    totalCents: number;
};
