const wrapper = document.querySelector('.wrapper');  
const loginLink = document.querySelector('.login-link');  
const registerLink = document.querySelector('.register-link');
const iconClose = document.querySelector('.icon-close');
const mostrar = document.querySelectorAll("#pass");
const icono = document.querySelectorAll('.ver');

registerLink.addEventListener('click', ()=>{
    wrapper.classList.add('active');        
});

loginLink.addEventListener('click', () => {
    wrapper.classList.remove('active');
});

icono.forEach(element => element.addEventListener('click', ()=> {
    let v = mostrar[0].getAttribute('type');
    if(v === 'text'){
        mostrar.forEach(element => element.setAttribute('type', 'password'));
    }else{
        mostrar.forEach(element => element.setAttribute('type', 'text'));
    }
}));
