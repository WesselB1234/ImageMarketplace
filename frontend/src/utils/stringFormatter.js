function getPriceFormatted(price){

    return Number(price).toLocaleString('nl-NL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });    
}

function getImageUrl(imageId) {
    return import.meta.env.VITE_API_URL + '/assets/img/UserUploadedImages/' + imageId + '.png'
}

export { getPriceFormatted, getImageUrl };