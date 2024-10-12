//nav popup
const userImageButton = document.querySelector('#user-img');
const userPop = document.querySelector('.login-logout-popup');
const popuptext = document.querySelector('.account-info');
const actionBtn = document.querySelector('#user-btn');

userImageButton.addEventListener('click', () =>{
userPop.classList.toggle('hide');
})

//cho nay se code lay ten tu sever xuong

// search box

const searchBtn = document.querySelector('.search-btn');
const searchBox = document.querySelector('.search-box');
searchBtn.addEventListener('click', () =>{
if(searchBox.value.length){
    location.href = `search.html?data=${searchBox.value}`
}
})

const Btn = document.querySelector('.btn');
Btn.addEventListener('click', () =>{
    location.href = `login.html`
})
