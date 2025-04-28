const toggleButton = document.getElementById('menu-toggle');
    const navList = document.getElementById('nav-list');

    toggleButton.addEventListener('click', () => {
        navList.classList.toggle('active');
        toggleButton.classList.toggle('active')
    });

