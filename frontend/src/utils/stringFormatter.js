function getPriceFormatted(price){

    return Number(price).toLocaleString('nl-NL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });    
}

export { getPriceFormatted };