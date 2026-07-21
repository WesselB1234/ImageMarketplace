function getPriceFormatted(price){

    return Number(price).toLocaleString('nl-NL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });    
}

function getImageUrl(imageId) {
    return import.meta.env.VITE_API_URL + '/assets/img/UserUploadedImages/' + imageId + '.png'
}

function getDateTimeFormatted(isoString) {
    
    if (!isoString) return '';

    return new Intl.DateTimeFormat('nl-NL', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(isoString))
}

export { getPriceFormatted, getImageUrl, getDateTimeFormatted }