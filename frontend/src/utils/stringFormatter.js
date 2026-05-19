function getPriceFormatted(price){

    return Number(price).toLocaleString('nl-NL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });    
}

function getImageUrl(imageId) {
    return import.meta.env.VITE_BACK_END_URL + import.meta.env.VITE_USER_UPLOADED_IMAGES_URL + '/' + imageId + '.png'
}

export { getPriceFormatted, getImageUrl };