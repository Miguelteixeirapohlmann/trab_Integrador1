/*!
* Start Bootstrap - Creative v7.0.7 (https://startbootstrap.com/theme/creative)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    // Activate SimpleLightbox plugin for portfolio items
    new SimpleLightbox({
        elements: '#portfolio a.portfolio-box'
    });

});
let products = [
    {
        name: "Casa Realengo",
        type: "casa",
        price: 250000,
        image: "imgs/Foto9.jpg"
    },
    {
        name: "Casa Alto Rolantinho",
        type: "casa",
        price: 180000,
        image: "imgs/foto8.jpg"
    },
    {
        name: "Casa Alpha Ville",
        type: "casa",
        price: 150000,
        image: "imgs/foto7.jpg"
    },
    {
        name: "Casa Jardim das Flores",
        type: "casa",
        price: 220000,
        image: "imgs/foto1.jpeg"
    },
    {
        name: "Casa Vista Alegre",
        type: "casa",
        price: 310000,
        image: "imgs/foto2.jpg"
    },
    {
        name: "Casa Solar dos Pássaros",
        type: "casa",
        price: 275000,
        image: "imgs/foto3.jpg"
    },
    {
        name: "Casa Bela Vista",
        type: "casa",
        price: 199000,
        image: "imgs/foto4.jpg"
    },
  {
        name: "Casa do gabriel",
        type: "casa",
        price: 1,
        image: "imgs/foto5.jpg"
    },
    {
        name: "Casa Nova Esperança",
        type: "casa",
        price: 185000,
        image: "imgs/casa6.jpg"
    }
];

let editingIndex = null;
function renderProducts() {
    const tbody = document.querySelector("#productsTable tbody");
    tbody.innerHTML = "";
    products.forEach((prod, idx) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td><img src="${prod.image}" class="product-img-preview" alt="${prod.name}"></td>
            <td>${prod.name}</td>
            <td>${prod.type.charAt(0).toUpperCase() + prod.type.slice(1)}</td>
            <td>R$ ${prod.price.toFixed(2)}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="editProduct(${idx})"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm" onclick="removeProduct(${idx})"><i class="fas fa-trash"></i> Remover</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function removeProduct(idx) {
    if (confirm("Tem certeza que deseja remover este produto?")) {
        products.splice(idx, 1);
        renderProducts();
    }
}


// Adicionar produto
document.getElementById("addProductForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const name = document.getElementById("productName").value;
    const type = document.getElementById("productType").value;
    const price = parseFloat(document.getElementById("productPrice").value);
    const imageInput = document.getElementById("productImage");
    let imageUrl = "";

    // Preview local da imagem (não faz upload real)
    if (imageInput.files && imageInput.files[0]) {
        imageUrl = URL.createObjectURL(imageInput.files[0]);
    } else if (editingIndex !== null) {
        // Se estiver editando e não mudou a imagem, mantém a antiga
        imageUrl = products[editingIndex].image;
    } else {
        imageUrl = "img/placeholder.png";
    }

    if (editingIndex !== null) {
        // Atualiza o produto existente
        products[editingIndex] = { name, type, price, image: imageUrl };
        editingIndex = null;
        // Volta o texto do botão para "Adicionar"
        document.querySelector('#addProductForm button[type="submit"]').innerHTML = '<i class="fas fa-plus"></i> Adicionar';
    } else {
        // Adiciona novo produto
        products.push({ name, type, price, image: imageUrl });
    }
    renderProducts();
    this.reset();
});

// Editar produto
function editProduct(idx) {
    const prod = products[idx];
    document.getElementById("productName").value = prod.name;
    document.getElementById("productType").value = prod.type;
    document.getElementById("productPrice").value = prod.price;
    // Não é possível restaurar a imagem no input file por segurança
    editingIndex = idx;
    // Muda o texto do botão para "Salvar"
    document.querySelector('#addProductForm button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Salvar';
}

renderProducts();
