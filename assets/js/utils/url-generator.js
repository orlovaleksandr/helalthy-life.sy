export function getUrlViewProduct(viewUrl, productId) {
    return (
        window.location.protocol +
        "//" +
        window.location.host +
        viewUrl +
        "/" +
        productId
    );
}

export function getUrlProductsByCategory(defaultUrl, categoryId, page, limit) {
    return defaultUrl +
        "?category=" + categoryId +
        "&isPublished=true" +
        "&page=" + page +
        "&itemsPerPage=" + limit;
}

export function concatUrlByParams(...params) {
    return params.join("/");
}