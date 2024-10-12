//price inputs
const acctualPrice = document.querySelector('#actual-price');
const discountPercentage = document.querySelector('#discount');
const sellingPrice = document.querySelector('#selling-price');

discountPercentage.addEventListener('input', () => {
    if(discountPercentage.value >100){
        discountPercentage.value = 90;
    }
    else{
        let discount = acctualPrice.value * discountPercentage.value /100;
        sellingPrice.value=acctualPrice.value - discount;
    }
})

//upload image handle
// mấy cái xử lý này trừ cái phần upload hình ảnh ra thì khá giống với login, sigup nên thôi không làm.


