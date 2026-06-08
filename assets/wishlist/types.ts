export type WishlistProduct = {
    id: string;
    name: string;
    priceCents: number;
    image: string;
    url: string;
};

export type WishlistNotification = {
    id: number;
    message: string;
};

export type WishlistResponse = {
    items: WishlistProduct[];
    productIds: string[];
    count: number;
};
