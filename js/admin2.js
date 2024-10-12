function showPopup(productId) {
    var overlay = document.getElementById('overlay');
    overlay.style.display = 'block';

    var hideButton = document.getElementById('hideButton');
    hideButton.onclick = function() {
        updateProductStatus(productId, 0);
        overlay.style.display = 'none';
        alert('Ẩn sản phẩm thành công!');
    };

    var showButton = document.getElementById('showButton');
    showButton.onclick = function() {
        updateProductStatus(productId, 1);
        overlay.style.display = 'none';
        alert('Hiển thị sản phẩm thành công!');
    };

    var closePopup = document.getElementById('closePopup');
    closePopup.onclick = function() {
        overlay.style.display = 'none';
    };
}

function updateProductStatus(productId, status) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'admin.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload();
        }
    };
    xhr.send('update_status=1&product_id=' + productId + '&status=' + status);
}


var imageInput = document.getElementById('image');
imageInput.addEventListener('change', function (event) {
  var file = event.target.files[0];
  var reader = new FileReader();
  reader.onload = function (e) {
    var imageData = e.target.result;
  };
  reader.readAsDataURL(file);
});
//alert
const showAlert = (msg) => {
  let alertBox = document.querySelector('.alert-box');
  let alertMsg = document.querySelector('.alert-msg');
  alertMsg.innerHTML = msg;
  alertBox.classList.add('show');
  setTimeout(() => {
    alertBox.classList.remove('show');
  }, 3000);
}