document.querySelectorAll('.question').forEach(button =>{
    button.addEventListener('click', ()=>{
        const item = button.parentElement;
        item.classList.toggle('active');
        });
});