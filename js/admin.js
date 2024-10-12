const loader = document.querySelector('.loader');
const productListing = document.querySelector('.product-listing');
const card=document.querySelector('.product-card');

const deleteProduct= document.querySelector('.delete-popup-btn');
deleteProduct.addEventListener('click', () => {
    if (confirm('Bạn có chắc là muốn xoá sản phẩm này không?')) {
    showAlert('Đã xoá thành công');
    card.classList.add('hide');
    let emtyBG=document.querySelector('.no-product-image');
    setTimeout(() => {
        emtyBG.classList.remove('hide');
      }, 1200);}
    else{
        showAlert('Xoá thất bại');
    }
})

//alert

function submitForm() {
    document.getElementById("ok").submit();
  }


function showForm() {
    var formContainer = document.querySelector('.form-container');
    formContainer.style.display = 'block';
}

function hideForm() {
    var formContainer = document.querySelector('.form-container');
    formContainer.style.display = 'none';
}